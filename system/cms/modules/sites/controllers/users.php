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
				'rules'	=> 'trim|'.
							'min_length['.config_item('min_password_length').']|'.
							'max_length['.config_item('max_password_length').']|'.
							'required'
			)
		);

		$val_edit = array(
			array(
				'field' => 'password',
				'label'	=> 'lang:user_password',
				'rules'	=> 'trim'
			)
		);
		
		if ($this->method == 'add')
		{
			$this->user_validation_rules = array_merge($val_rules, $val_create);
		}
		else
		{
			$this->user_validation_rules = array_merge($val_rules, $val_edit);
		}
		
		// login validation
		$this->login_rules = array(
			array(
				'field' => 'email',
				'label'	=> lang('email_label'),
				'rules' => 'required|callback__check_login'
			),
			array(
				'field' => 'password',
				'label'	=> lang('password_label'),
				'rules' => 'required'
			)
		);
	}

	/**
	 * List super-admins
	 */
	public function	index()
	{
		$data = new stdClass();

		$data->users = $this->user_m->get_all();
		
		// Load the view
		$this->template->title(lang('site:sites'), lang('site:user_manager'))
						->set('description', lang('site:super_admin_list'))
						->build('users', $data);
	}
	
	/**
	 * Create a new super-admin
	 */
	public function add()
	{
		$data = new stdClass();

		// Set the validation rules
		$this->form_validation->set_rules($this->user_validation_rules);
		
		if($this->form_validation->run())
		{
			// check if this email is already registered
			if ($this->user_m->get_by('email', $this->input->post('email')))
			{
				$data->{'messages'}['error'] = sprintf(lang('site:user_exists'), $this->input->post('email'));
			}
			// did it work?
			elseif ($this->user_m->add_admin($this->input->post()))
			{
				$this->session->set_flashdata('success', sprintf(lang('site:admin_create_success'), $_POST['username']));
				redirect('sites/users');
			}
			// no it didn't :'(
			else
			{
				$this->session->set_flashdata('error', lang('site:db_error'));
			}
		}

		foreach ($this->user_validation_rules AS $rule)
		{
			$data->{$rule['field']} = set_value($rule['field']);
		}

		// Load the view
		$this->template->title(lang('site:sites'), lang('site:create_admin'))
						->set('description', lang('site:create_admin_desc'))
						->build('user_form', $data);
	}
	
	/**
	 * Edit a super-admin
	 */
	public function edit($id = '')
	{
		$data = $this->user_m->get($id);
		$data->password 		= '';
		$data->confirm_password	= '';
		
		// Set the validation rules
		$this->form_validation->set_rules($this->user_validation_rules);
		
		if($this->form_validation->run())
		{
			if ($this->user_m->edit_admin($this->input->post()))
			{
				$this->session->set_flashdata('success', sprintf(lang('site:edit_success'), $_POST['username']));
				redirect('sites/users');
			}
			$this->session->set_flashdata('error', lang('site:db_error'));
			redirect('sites/users');
		}

		// Load the view
		$this->template->title(lang('site:sites'), sprintf(lang('site:edit_admin'), $data->username))
						->set('description', lang('site:create_admin_desc'))
						->build('user_form', $data);
	}
	
	/**
	 * Enable an existing super admin
	 */
	public function enable($id = 0)
	{
		$this->user_m->update($id, array('active' => 1));

		redirect('sites/users');
	}

	/**
	 * Disable an existing super admin
	 */
	public function disable($id = 0)
	{
		if ($this->session->userdata('super_id') == $id)
		{
			$this->session->set_flashdata('notice', lang('site:disable_self'));
		}
		else
		{
			$this->user_m->update($id, array('active' => 0));
		}

		redirect('sites/users');
	}
	
	/**
	 * Delete a user from core_users
	 */
	public function delete($id = 0)
	{	
		$this->user_m->delete($id);
		
		redirect('sites/users');
	}
	
	/**
	 * Log in
	 *
	 * @access public
	 * @return void
	 */
	public function login()
	{
		$this->lang->load('users/user');
		
		// Set the validation rules
		$this->form_validation->set_rules($this->login_rules);
		
	    // If the validation worked, or the user is already logged in
	    if ($this->form_validation->run() or $this->user_m->logged_in())
	    {
	    	redirect('sites');
		}

	    $this->template
			->set_layout(false)
			->build('admin/login');
	}
	
	/**
	 * Logout
	 *
	 * Pretty self explanatory
	 */
	public function logout()
	{
		$this->user_m->logout();
			
		redirect('sites');
	}
	
	/**
	 * Callback From: login()
	 *
	 * @access public
	 * @param string $email The Email address to validate
	 * @return bool
	 */
	public function _check_login($email)
	{
		$remember = false;
		if ($this->input->post('remember') == 1)
		{
			$remember = true;
		}

		if ($this->user_m->login($email, $this->input->post('password'), $remember))
		{
			return true;
		}

		$this->form_validation->set_message('_check_login', lang('user:login_incorrect'));
		return false;
	}
}
