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
				'rules' => 'trim|stripslashes|mysql_real_escape_string|max_length[100]|required'
			),
			array(
				'field' => 'domain',
				'label' => 'Domain',
				'rules' => 'trim|callback__valid_domain|required'
			),
			array(
				'field' => 'ref',
				'label' => 'Site Ref',
				'rules' => 'trim|alpha_dash|max_length[255]|required'
			)
		);
	}

	/**
	 * List all sites
	 */
	public function index()
	{
		$data->sites = $this->core_sites_m->get_sites();

		// Load the view
		$this->template->title(lang('site.sites'))
						->set('description', lang('site.create_site_desc'))
						->build('admin/sites', $data);
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
			// Try to create the site
			$message = $this->core_sites_m->create_site($_POST);
			if($message === TRUE)
			{
				// All good...
				$this->session->set_flashdata('success', lang('site.create_success'));
				redirect('admin/sites');
			}
			// Must be folders that aren't writeable
			else
			{
				$this->session->set_flashdata('error', $message);
				redirect('admin/sites/create');
			}
		}

		foreach ($this->site_validation_rules AS $rule)
		{
			$data->{$rule['field']} = $this->input->post($rule['field']);
		}

		// Load the view
		$this->template->title(lang('site.sites'), lang('site.create_site'))
						->build('admin/form', $data);
	}

	/**
	 * Nothing much... just edit an existing site
	 */
	public function edit($id = '')
	{
		$data = $this->core_sites_m->get_site($id);

		// Set the validation rules
		$this->form_validation->set_rules($this->site_validation_rules);

		if($this->form_validation->run())
		{
			// Update the db
			$message = $this->core_sites_m->edit_site($_POST, $data);
			if($message === TRUE)
			{
				// All good...
				$this->session->set_flashdata('success', sprintf(lang('site.edit_success'), $data->name));
				redirect('admin/sites');
			}
			// Something went wrong..
			elseif($message === FALSE)
			{
				$this->session->set_flashdata('error', lang('site.rename_error'));
				redirect('admin/sites/edit/'.$id);
			}
			$this->session->set_flashdata('notice', $message);
			redirect('admin/sites/edit/'.$id);
		}

		// Load the view
		$this->template->title(lang('site.sites'), sprintf(lang('site.edit_site'), $data->name))
						->build('admin/form', $data);
	}
	
	/**
	 * Redirects the admin to an extra confirmation page
	 */
	public function delete($id = '')
	{
		if (is_numeric($id))
		{
			redirect('admin/sites/confirm/'.$id);
		}		
		redirect('admin/sites');
	}
	
	/**
	 * If they're absolutely certain then we'll trash the site
	 */
	public function confirm($id = '')
	{
		$site = $this->core_sites_m->get_site($id);
		
		if ($this->input->post('id') AND $this->input->post('btnAction'))
		{
			$message = $this->core_sites_m->delete($id, $site);
			
			if ($message === TRUE)
			{
				$this->session->set_flashdata('success', lang('site.site_deleted'));
				redirect('admin/sites');
			}
			else
			{
				$this->session->set_flashdata('success', lang('site.site_deleted'));
				$this->session->set_flashdata('notice', $message);
			}
			// get them out of here
			redirect('admin/sites');
		}
		$this->template->build('admin/confirm', $site);
	}
	
	public function _valid_domain($url)
	{
		return preg_replace('([^a-z0-9._-]+)', '', $url);
	}
}
