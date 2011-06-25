<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Users extends Sites_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Create a new super-admin
	 */
	public function add()
	{
		
	}
	
	/**
	 * Return filtered users
	 */
	public function	index()
	{
		$form_data = array();
		$this->load->model('sites_m');
			
		$sites = $this->sites_m->get_sites();
		
		foreach ($sites AS $site)
		{
			$form_data[$site->ref] = lang('site.site').': '.$site->name;
		}
		
		$data->form_data = $form_data;
		
		// Load the view
		$this->template->title(lang('site.sites'), lang('site.create_site'))
						->append_metadata(js('filter.js', 'admin'))
						->set_partial('filters', 'partials/filters')
						->build('users', $data);
	}
	
	/**
	 * Filter the users
	 */
	public function filter()
	{
		$data = $this->users_m->filter($this->input->post());

		return print($this->load->view('users', $data, TRUE));
	}
	
	/**
	 * Duplicate a user into the core_users table
	 *
	 * @param	string	$ref	The user's database prefix
	 * @param	string	$id		The user id
	 */
	public function make($ref = '', $id = 0)
	{
		$this->users_m->make_super_admin($ref, $id);
		redirect('sites/users');
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
		redirect('sites/users');
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
		redirect('sites/users');
	}
	
	/**
	 * Delete a user from core_users
	 */
	public function delete($id = '')
	{
		if (is_numeric($id))
		{
			$this->users_m->delete($id);
		}		
		redirect('sites/users');
	}
}
