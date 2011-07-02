<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Code here is run before the site manager controllers
class Sites_Controller extends CI_Controller {

	public function Sites_Controller()
	{
		parent::__construct();
		
		// First off set the db prefix
		$this->db->set_dbprefix('core_');
		
		// make sure they've ran the installer before trying to view our shiny panel
		if ( ! $this->db->table_exists('sites')) redirect('installer');
		
		define('ADMIN_THEME', 'sites');
		
		//define folders that we need to create for each new site
		$this->locations = array(APPPATH.'cache'		=> array(
														 'simplepie'
														),
								  'addons'		=> array('modules',
														 'widgets',
														 'themes'
														),
								  'uploads'	=> array()
								  );
		
		// Load the Language files ready for output
		$this->lang->load('admin');
		$this->lang->load('buttons');
		$this->lang->load('main');
		$this->lang->load('sites/sites');
		$this->lang->load('users/user');

		// Load all the required classes
		$this->load->model('sites_m');
		$this->load->model('users_m');
		$this->load->model('settings_m');
		$this->load->library('form_validation');
		$this->load->dbforge();
		
		// Work out module, controller and method and make them accessable throught the CI instance
		$this->module = $this->router->fetch_module();
		$this->controller = $this->router->fetch_class();
		$this->method = $this->router->fetch_method();
		$this->module_details['slug'] = 'sites';
		
		// Load helpers
		$this->load->helper('admin_theme');
		$this->load->helper('file');
		$this->load->helper('number');
		$this->load->helper('sites/date');
		
		// Fetch all settings
		$this->settings = $this->settings_m->get_settings();

		$this->asset->set_theme(ADMIN_THEME);
		
		// check to make sure they're logged in
		if ( $this->method !== 'login' AND ! $this->users_m->logged_in())
		{
			redirect('sites/login');
		}
		
		// Asset library needs to know where the admin theme directory is
		$this->config->set_item('asset_dir', APPPATH.'themes/sites/');
		$this->config->set_item('asset_url', BASE_URL.APPPATH.'themes/sites/');
		
		// Template configuration
		$this->template
				->append_metadata(css('common.css'))
				->append_metadata(js('jquery/jquery.cooki.js'))
				->enable_parser(FALSE)
				->set('super_username', $this->session->userdata('super_username'))
				->set_theme(ADMIN_THEME)
				->set_layout('default', 'admin');

	}
}