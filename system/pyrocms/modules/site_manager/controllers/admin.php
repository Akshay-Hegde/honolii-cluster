<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Admin extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		// Load all the required classes
		$this->load->model('site_manager_m');
		$this->load->model('users_m');
		$this->load->library('form_validation');
		$this->lang->load('site_manager');
		
		// Make sure this user has permissions
		if ( ! $this->users_m->is_super_admin() )
		{
			$this->session->set_flashdata('error', lang('site.not_super_admin'));
			redirect(site_url());
		}

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

		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
	}

	/**
	 * List all sites
	 */
	public function index()
	{
		$data->sites = $this->site_manager_m->get_sites();

		// Load the view
		$this->template->title($this->module_details['name'])
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
			$message = $this->site_manager_m->create_site($_POST);
			if($message === TRUE)
			{
				// All good...
				$this->session->set_flashdata('success', lang('site.create_success'));
				redirect('admin/site_manager');
			}
			// Must be folders that aren't writeable
			else
			{
				$this->session->set_flashdata('error', $message);
				redirect('admin/site_manager/create');
			}
		}

		foreach ($this->site_validation_rules AS $rule)
		{
			$data->{$rule['field']} = $this->input->post($rule['field']);
		}

		// Load the view
		$this->template->title($this->module_details['name'], lang('site.create_site'))
						->build('admin/form', $data);
	}

	/**
	 * Nothing much... just edit an existing site
	 */
	public function edit($id = '')
	{
		$data = $this->site_manager_m->get_site($id);

		// Set the validation rules
		$this->form_validation->set_rules($this->site_validation_rules);

		if($this->form_validation->run())
		{
			// Update the db
			$message = $this->site_manager_m->edit_site($_POST, $data);
			if($message === TRUE)
			{
				// All good...
				$this->session->set_flashdata('success', sprintf(lang('site.edit_success'), $data->name));
				redirect('admin/site_manager');
			}
			// Something went wrong..
			elseif($message === FALSE)
			{
				$this->session->set_flashdata('error', lang('site.rename_error'));
				redirect('admin/site_manager/edit/'.$id);
			}
			$this->session->set_flashdata('notice', $message);
			redirect('admin/site_manager/edit/'.$id);
		}

		// Load the view
		$this->template->title($this->module_details['name'], sprintf(lang('site.edit_site'), $data->name))
						->build('admin/form', $data);
	}
	
	/**
	 * Redirects the admin to an extra confirmation page
	 */
	public function delete($id = '')
	{
		if (is_numeric($id))
		{
			redirect('admin/site_manager/confirm/'.$id);
		}		
		redirect('admin/site_manager');
	}
	
	/**
	 * If they're absolutely certain then we'll trash the site
	 */
	public function confirm($id = '')
	{
		$site = $this->site_manager_m->get_site($id);
		
		if ($this->input->post('id') AND $this->input->post('btnAction'))
		{
			$message = $this->site_manager_m->delete($id, $site);
			
			if ($message === TRUE)
			{
				$this->session->set_flashdata('success', lang('site.site_deleted'));
				redirect('admin/site_manager');
			}
			else
			{
				$this->session->set_flashdata('success', lang('site.site_deleted'));
				$this->session->set_flashdata('notice', $message);
			}
			// get them out of here
			redirect('admin/site_manager');
		}
		$this->template->build('admin/confirm', $site);
	}
	
	public function _valid_domain($url)
	{
		return preg_replace('([^a-z0-9._-]+)', '', $url);
	}
}
