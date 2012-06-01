<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller for the api module
 * 
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS\Core\Modules\API\Controllers
 */
class Admin extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->language('api');
	}
	
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->load->model('api_log_m');
		$this->load->language('users/user');
		
		if ($this->db->table_exists('api_logs'))
		{
			$this->template->logs = $this->api_log_m->limit(10)->get_all();
		}
		
		$this->template
			->title($this->module_details['name'])
			->build('index');
	}
	
	public function ajax_set_api_status()
	{
		if ( ! $this->input->is_ajax_request())
		{
			exit('Trickery is afoot.');
		}
		
		$status = (bool) (int) $this->input->post('status');
		
		// Update the setting
		Settings::set('api_enabled', $status);
		
		echo json_encode(array('status' => $status));
	}
	
	public function ajax_set_api_user_keys()
	{
		if ( ! $this->input->is_ajax_request())
		{
			exit('Trickery is afoot.');
		}
		
		$status = (bool) (int) $this->input->post('status');
		
		// Update the setting
		Settings::set('api_user_keys', $status);
		
		echo json_encode(array('status' => $status));
	}
}

/* End of file admin.php */