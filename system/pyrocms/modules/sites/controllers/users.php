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
		
		// Set the validation rules
		$val_rules = array(
			array(
				  'field' => 'id',
			),
			array(
				'field' => 'username',
				'label'	=> 'lang:user_username',
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
		
		if ($this->method == 'add')
		{
			$this->admin_validation_rules = array_merge($val_rules, $val_create);
		}
		else
		{
			$this->admin_validation_rules = array_merge($val_rules, $val_edit);
		}
	}

	/**
	 * List super-admins
	 */
	public function	index()
	{
		$data->users = $this->users_m->get_all();
		
		// Load the view
		$this->template->title(lang('site.sites'), lang('site.user_manager'))
						->set('description', lang('site.super_admin_list'))
						->build('users', $data);
	}
	
	/**
	 * Create a new super-admin
	 */
	public function add()
	{
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_validation_rules);
		
		if($this->form_validation->run())
		{
			if ($this->users_m->add_admin($this->input->post()))
			{
				$this->session->set_flashdata('success', sprintf(lang('site.admin_create_success'), $_POST['username']));
				redirect('sites/users');
			}
			$this->session->set_flashdata('error', lang('site.db_error'));
			redirect('sites/users');
		}
		
		foreach ($this->admin_validation_rules AS $rule)
		{
			$data->{$rule['field']} = set_value($rule['field']);
		}

		// Load the view
		$this->template->title(lang('site.sites'), lang('site.create_admin'))
						->set('description', lang('site.create_admin_desc'))
						->build('user_form', $data);
	}
	
	/**
	 * Edit a super-admin
	 */
	public function edit($id = '')
	{
		$data = $this->users_m->get($id);
		$data->password 		= '';
		$data->confirm_password	= '';
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_validation_rules);
		
		if($this->form_validation->run())
		{
			if ($this->users_m->edit_admin($this->input->post()))
			{
				$this->session->set_flashdata('success', sprintf(lang('site.edit_success'), $_POST['username']));
				redirect('sites/users');
			}
			$this->session->set_flashdata('error', lang('site.db_error'));
			redirect('sites/users');
		}

		// Load the view
		$this->template->title(lang('site.sites'), sprintf(lang('site.edit_admin'), $data->username))
						->set('description', lang('site.create_admin_desc'))
						->build('user_form', $data);
	}
	
	/**
	 * Enable an existing user
	 */
	public function enable($id = 0)
	{

		$this->users_m->update($id, array('active' => 1));

		redirect('sites/users');
	}

	/**
	 * Disable an existing user
	 */
	public function disable($id = 0)
	{

		$this->users_m->update($id, array('active' => 0));

		redirect('sites/users');
	}
	
	/**
	 * Delete a user from core_users
	 */
	public function delete($id = 0)
	{
		
		$this->users_m->delete($id);
		
		redirect('sites/users');
	}
}
