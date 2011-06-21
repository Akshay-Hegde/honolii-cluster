<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Admin_users extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		// Load all the required classes
		$this->load->model('users_m');
		$this->lang->load('site_manager');
		
		// Make sure this user has permissions
		if ( ! $this->users_m->is_super_admin() )
		{
			$this->session->set_flashdata('error', lang('site.not_super_admin'));
			redirect(site_url());
		}

		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
	}

	/**
	 * List all sites
	 */
	public function index($id = 0)
	{
		$data->users = $this->users_m->get_users($id);

		// Load the view
		$this->template->title($this->module_details['name'])
						->build('admin/users', $data);
	}
	
	/**
	 * Create the database records and all folders for a new site
	 */
	public function add()
	{
		$form_data = array();
		$this->load->model('site_manager_m');
			
		$sites = $this->site_manager_m->get_sites();
		
		foreach ($sites AS $site)
		{
			$form_data[$site->ref] = $site->name;
		}
		
		$data->form_data = $form_data;
		
		// Load the view
		$this->template->title($this->module_details['name'], lang('site.create_site'))
						->append_metadata(js('filter.js', 'admin'))
						->set_partial('filters', 'admin/partials/filters')
						->build('admin/form', $data);
	}
	
	/**
	 * Return filtered users
	 */
	public function	filter()
	{
		dump($users = $this->users_m->filter($this->input->post()));
	}
	
	/**
	 * Enable an existing user
	 */
	public function enable($id = 0)
	{
		if (is_numeric($id))
		{
			$this->users_m->update($id, 1);
		}
		redirect('admin/site_manager/users');
	}

	/**
	 * Disable an existing user
	 */
	public function disable($id = 0)
	{
		if (is_numeric($id))
		{
			$this->users_m->update($id, 0);
		}
		redirect('admin/site_manager/users');
	}
	
	/**
	 * Redirects the admin to an extra confirmation page
	 */
	public function delete($id = '')
	{
		if (is_numeric($id))
		{
			$this->users_m->delete($id);
		}		
		redirect('admin/site_manager/users');
	}
}
