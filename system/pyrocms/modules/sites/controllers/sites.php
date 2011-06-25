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
		$this->site_validation_rules = array(
			array(
				  'field' => 'id',
				  'label' => 'Site ID'
			),
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'domain',
				'label' => 'Domain',
				'rules' => 'trim|callback__valid_domain|max_length[100]|required'
			),
			array(
				'field' => 'ref',
				'label' => 'Site Ref',
				'rules' => 'trim|alpha_dash|min_length[4]|max_length[20]|required'
			)
		);
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
			// make sure it doesn't already exist
			if ($this->sites_m->get_by('ref', $this->input->post('ref')))
			{
				$data->messages['notice'] = sprintf(lang('site.exists'), $this->input->post('ref'));
			}
			else
			{			
				// Try to create the site
				$message = $this->sites_m->create_site($_POST);
				
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
		$data = $this->sites_m->get($id);

		// Set the validation rules
		$this->form_validation->set_rules($this->site_validation_rules);

		if($this->form_validation->run())
		{
			$message = $this->sites_m->edit_site($_POST, $data);
			
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
}
