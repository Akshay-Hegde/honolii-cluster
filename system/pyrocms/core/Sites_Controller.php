<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Code here is run before the site manager controllers
class Sites_Controller extends CI_Controller {

	public function Sites_Controller()
	{
		define('ADMIN_THEME', 'sites');
		
		parent::__construct();
		// Load the Language files ready for output
		$this->lang->load('admin');
		$this->lang->load('buttons');
		$this->lang->load('sites/sites');
		
		// Load all the required classes
		$this->load->model('core_sites_m');
		$this->load->model('core_users_m');
		$this->load->library('form_validation');
		
		// Work out module, controller and method and make them accessable throught the CI instance
		$this->module = $this->router->fetch_module();
		$this->controller = $this->router->fetch_class();
		$this->method = $this->router->fetch_method();
		$this->module_details['slug'] = 'sites';
		
		// Load helpers
		$this->load->helper('admin_theme');
		
		// And lastly configs
		$this->load->config('sites/config');

		$this->asset->set_theme(ADMIN_THEME);
		
		// Asset library needs to know where the admin theme directory is
		$this->config->set_item('asset_dir', APPPATH.'themes/sites/');
		$this->config->set_item('asset_url', BASE_URL.APPPATH.'themes/sites/');
		// Set the front-end theme directory
		$this->config->set_item('theme_asset_dir', APPPATH.'themes/');
		$this->config->set_item('theme_asset_url', BASE_URL.APPPATH.'themes/');
		
		// Template configuration
		$this->template
				->enable_parser(FALSE)
				->set_theme(ADMIN_THEME)
				->set_layout('default', 'admin');

	}
}