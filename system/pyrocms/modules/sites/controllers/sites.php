<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Sites extends Sites_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Set the validation rules
		$val_rules = array(
			array(
				  'field' => 'id',
			),
			array(
				'field'	=>	'user_id',
			),
			array(
				'field' => 'name',
				'label' => 'lang:site.descriptive_name',
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'domain',
				'label' => 'lang:site.domain',
				'rules' => 'trim|callback__valid_domain|max_length[100]|required'
			),
			array(
				'field' => 'ref',
				'label' => 'lang:site.ref',
				'rules' => 'trim|alpha_dash|callback__underscore|min_length[4]|max_length[20]|required'
			),
			array(
				'field' => 'username',
				'label'	=> 'lang:user_username',
				'rules'	=> 'trim|required'
			),
			array(
				'field' => 'first_name',
				'label'	=> 'lang:user_first_name',
				'rules'	=> 'trim|required'
			),
			array(
				'field' => 'last_name',
				'label'	=> 'lang:user_last_name',
				'rules'	=> 'trim|required'
			),
			array(
				'field' => 'email',
				'label'	=> 'lang:user_email',
				'rules'	=> 'trim|required|valid_email'
			)
		);
		
		$val_create = array(
			array(
				'field' => 'password',
				'label'	=> 'lang:user_password',
				'rules'	=> 'trim|min_length[4]|required'
			),
			array(
				'field' => 'confirm_password',
				'label'	=> 'lang:user_confirm_password',
				'rules'	=> 'trim|min_length[4]|required|matches[password]'
			)
		);
		
		$val_edit = array(
			array(
				'field' => 'password',
				'label'	=> 'lang:user_password',
				'rules'	=> 'trim|matches[confirm_password]'
			),
			array(
				'field' => 'confirm_password',
				'label'	=> 'lang:user_confirm_password',
				'rules'	=> 'trim|matches[password]'
			)
		);
		
		if ($this->method == 'create')
		{
			$this->site_validation_rules = array_merge($val_rules, $val_create);
		}
		else
		{
			$this->site_validation_rules = array_merge($val_rules, $val_edit);
		}
	}

	/**
	 * List all sites
	 */
	public function index()
	{
		$data->sites = $this->sites_m->get_all();

		// Load the view
		$this->template->title(lang('site.sites'))
						->set('description', lang('site.edit_site_desc'))
						->build('sites', $data);
	}
	
	/**
	 * Create the database records and all folders for a new site
	 */
	public function create()
	{
		// Set the validation rules
		$this->form_validation->set_rules($this->site_validation_rules);

		if($this->form_validation->run())
		{
			$ref = $this->input->post('ref');
			
			$this->load->library('module_import', $ref);
			$this->load->config('migrations');
	
			// make sure there aren't orphaned folders from a previous install
			foreach ($this->locations AS $folder_check => $sub_folders)
			{
				
				if (is_dir($folder_check.'/'.$ref))
				{
					$this->session->set_flashdata('error', sprintf(lang('site.folder_exists'),
																   $folder_check.'/'.$ref));
					redirect('sites/create');
				}
			}
				
			// make sure it doesn't already exist
			if ($this->sites_m->get_by('domain', $this->input->post('domain')))
			{
				$data->messages['notice'] = sprintf(lang('site.exists'), $ref);
			}
			else
			{			
				// Try to create the site
				$message = $this->sites_m->create_site($this->input->post());
				
				if($message === TRUE)
				{
					// All good...
					$this->session->set_flashdata('success', lang('site.create_success'));
					redirect('sites');
				}
				// There must be folders that aren't writeable
				elseif(is_array($message))
				{
					$html = '<ul>';
	
					foreach ($message AS $line)
					{
						$html .= '<li>' . $line . '</li>';
					}
	
					$html .= '</ul>';
					
					$data->messages['notice'] = sprintf(lang('site.create_manually'), $line);
				}
				else
				{
					$data->messages['error'] = lang('site.db_error');
				}
			}
		}

		foreach ($this->site_validation_rules AS $rule)
		{
			$data->{$rule['field']} = set_value($rule['field']);
		}

		// Load the view
		$this->template->title(lang('site.sites'), lang('site.create_site'))
						->set('description', lang('site.create_site_desc'))
						->build('form', $data);
	}

	/**
	 * Nothing much... just edit an existing site
	 */
	public function edit($id = 0)
	{
		$data = $this->sites_m->get_site($id);

		// Set the validation rules
		$this->form_validation->set_rules($this->site_validation_rules);

		if($this->form_validation->run())
		{
			$ref = $this->input->post('ref');
			
			$message = $this->sites_m->edit_site($this->input->post(), $data);
			
			if($message === TRUE)
			{
				// All good...
				$this->session->set_flashdata('success', sprintf(lang('site.edit_success'), $data->name));
				redirect('sites');
			}
			elseif(is_array($message))
			{
				$html = '<ul>';

				foreach ($message AS $old => $new)
				{
					$html .= '<li>' . sprintf(lang('site.rename_manually'), $old, $new) . '</li>';
				}

				$html .= '</ul>';
				
				$data->messages['notice'] = sprintf(lang('site.rename_notice'), $html);
			}
			else
			{
				// couldn't save the record
				$data->messages['error'] = lang('site.db_error');
			}
		}

		// Load the view
		$this->template->title(lang('site.sites'), sprintf(lang('site.edit_site'), $data->name))
						->build('form', $data);
	}
	
	/**
	 * Redirects the admin to an extra confirmation page
	 */
	public function delete($id = 0)
	{
		if ($id != 0)
		{
			redirect('sites/confirm/'.$id);
		}		
		redirect('sites');
	}
	
	/**
	 * If they're absolutely certain then we'll trash the site
	 */
	public function confirm($id = '')
	{
		$site = $this->sites_m->get($id);
		
		if ($this->input->post('id') AND $this->input->post('btnAction'))
		{
			$message = $this->sites_m->delete_site($id, $site);
			
			if ($message === TRUE)
			{
				$this->session->set_flashdata('success', lang('site.site_deleted'));
				redirect('sites');
			}
			elseif(is_array($message))
			{
				$html = '<ul>';

				foreach ($message AS $folder)
				{
					$html .= '<li>' . $folder . '</li>';
				}

				$html .= '</ul>';
				
				$data->messages['notice'] = sprintf(lang('site.delete_manually'), $html);
			}
			else
			{
				// couldn't delete the record
				$data->messages['error'] = lang('site.delete_error');
			}
		}
		$this->template->build('confirm', $site);
	}
	
	public function _valid_domain($url)
	{
		return preg_replace('([^a-z0-9._-]+)', '', $url);
	}
	
	public function _underscore($ref)
	{
		return str_replace('-', '_', $ref);
	}
}
