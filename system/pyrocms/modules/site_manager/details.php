<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Site_manager extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Site Manager'
			),
			'description' => array(
				'en' => 'This is the interface that super admins use to control multi-site.'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'menu' => FALSE
		);
	}

	public function install()
	{
		// the database stuff is there already
		return TRUE;
	}

	public function uninstall()
	{
		return FALSE;
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */