<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Code here is run before the site manager controllers
class Sites_Controller extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// First off set the db prefix
		$this->db->set_dbprefix('core_');
		
		// If using a URL not defined as a site, set this to stop the world ending
		if ( ! defined('SITE_REF'))
		{
			define('SITE_REF', 'core');
		}
		
		// make sure they've ran the installer before trying to view our shiny panel
		if ( ! $this->db->table_exists('sites')) 
		{
			redirect('installer');
		}
		
		define('ADMIN_THEME', 'sites');
		
		// define folders that we need to create for each new site
		$this->locations = array(
			APPPATH.'cache'	=> array(
				'simplepie'
			),
			'addons' => array(
				'modules',
				'widgets',
				'themes',
			),
			'uploads'	=> array(),
		);
		
		// Since we don't need to lock the lang with a setting like /admin and
		// the front-end we just define CURRENT_LANGUAGE exactly the same as AUTO_LANGUAGE
		define('CURRENT_LANGUAGE', AUTO_LANGUAGE);
		
		// Load the Language files ready for output
		$this->lang->load(array('admin', 'buttons', 'global', 'sites/sites', 'users/user'));
		
		// Load all the required classes
		$this->load->model(array('sites_m', 'users_m', 'settings_m'));
		
		$this->load->library(array('form_validation', 'settings/settings'));
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
		$this->load->helper('date');
		$this->load->helper('cookie');
		
		// Load ion_auth config so our user's settings (password length, etc) are in sync
		$this->load->config('users/ion_auth');

		$this->asset->set_theme(ADMIN_THEME);
		
		// check to make sure they're logged in
		if ( $this->method !== 'login' AND ! $this->users_m->logged_in())
		{
			redirect('sites/login');
		}
		
		// Asset library needs to know where the admin theme directory is
		$this->config->set_item('asset_dir', APPPATH.'themes/');
		$this->config->set_item('asset_url', BASE_URL.APPPATH.'themes/');
		$this->config->set_item('theme_asset_dir', APPPATH.'themes/');
		$this->config->set_item('theme_asset_url', BASE_URL.APPPATH.'themes/');
		
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