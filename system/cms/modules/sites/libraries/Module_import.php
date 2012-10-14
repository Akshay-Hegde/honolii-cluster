<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Module_import {

	private $ci;
	private $addons;

	public function __construct($addons)
	{
		$this->ci =& get_instance();
		$this->addons = $addons;
		
		// set this so we can install modules. We'll change it later
		define('DEFAULT_EMAIL', 'admin@domain.com');
		define('DEFAULT_LANG', config_item('default_language'));
	}

	/**
	 * Install
	 *
	 * Installs a module
	 *
	 * @param	string	$slug	The module slug
	 * @return	bool
	 */
	public function install($slug, $is_core = false)
	{
		$details_class = $this->_spawn_class($slug, $is_core);

		// Get some basic info
		$module = $details_class->info();
		
		// Only install 3rd party modules if defined
		if( $is_core === false and ( ! isset($module['default_install']) or $module['default_install'] === false ) )
		{
			return false;
		}

		// Now lets set some details ourselves
		$module['version'] = $details_class->version;
		$module['is_core'] = $is_core;
		$module['enabled'] = true;
		$module['installed'] = true;
		$module['slug'] = $slug;

		// Run the install method to get it into the database
		if ( ! $details_class->install())
		{
			return false;
		}

		// Looks like it installed ok, add a record
		return $this->add($module);
	}

	public function add($module)
	{
		return $this->ci->db->insert('modules', array(
			'name' => serialize($module['name']),
			'slug' => $module['slug'],
			'version' => $module['version'],
			'description' => serialize($module['description']),
			'skip_xss' => !empty($module['skip_xss']),
			'is_frontend' => !empty($module['frontend']),
			'is_backend' => !empty($module['backend']),
			'menu' => !empty($module['menu']) ? $module['menu'] : false,
			'enabled' => $module['enabled'],
			'installed' => $module['installed'],
			'is_core' => $module['is_core']
		));
	}


	public function import_all()
	{
		//drop the old modules table
		$this->ci->load->dbforge();
		$this->ci->dbforge->drop_table('modules');

		$modules = "
			CREATE TABLE IF NOT EXISTS ".$this->ci->db->dbprefix('modules')." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` TEXT NOT NULL,
			  `slug` varchar(50) NOT NULL,
			  `version` varchar(20) NOT NULL,
			  `type` varchar(20) DEFAULT NULL,
			  `description` TEXT DEFAULT NULL,
			  `skip_xss` tinyint(1) NOT NULL,
			  `is_frontend` tinyint(1) NOT NULL,
			  `is_backend` tinyint(1) NOT NULL,
			  `menu` varchar(20) NOT NULL,
			  `enabled` tinyint(1) NOT NULL,
			  `installed` tinyint(1) NOT NULL,
			  `is_core` tinyint(1) NOT NULL,
			  `updated_on` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `slug` (`slug`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";

		//create the modules table so that we can import all modules including the modules module
		$this->ci->db->query($modules);
		
		$session = "
			CREATE TABLE IF NOT EXISTS ".$this->ci->db->dbprefix(str_replace('default_', '', config_item('sess_table_name')))." (
			 `session_id` varchar(40) DEFAULT '0' NOT NULL,
			 `ip_address` varchar(16) DEFAULT '0' NOT NULL,
			 `user_agent` varchar(120) NOT NULL,
			 `last_activity` int(10) unsigned DEFAULT 0 NOT NULL,
			 `user_data` text null,
			PRIMARY KEY (`session_id`)
			);
		";
		
		// create a session table so they can use it if they want
		$this->ci->db->query($session);

		// Loop through directories that hold modules
		$is_core = true;

		foreach (array(APPPATH, SHARED_ADDONPATH) as $directory)
		{
			// Loop through modules
			foreach(glob($directory.'modules/*', GLOB_ONLYDIR) as $module_name)
			{
				$slug = basename($module_name);

				if ( ! $details_class = $this->_spawn_class($slug, $is_core))
				{
					continue;
				}

				$this->install($slug, $is_core);
			}

			// Going back around, 2nd time is addons
			$is_core = false;
		}

		return true;
	}

	/**
	 * Spawn Class
	 *
	 * Checks to see if a details.php exists and returns a class
	 *
	 * @param	string	$module_slug	The folder name of the module
	 * @access	private
	 * @return	array
	 */
	private function _spawn_class($slug, $is_core = false)
	{
		$path = $is_core ? APPPATH : SHARED_ADDONPATH;

		// Before we can install anything we need to know some details about the module
		$details_file = $path . 'modules/' . $slug . '/details'.EXT;

		// Check the details file exists
		if ( ! is_file($details_file))
		{
			return false;
		}

		// Sweet, include the file
		include_once $details_file;

		// Now call the details class
		$class = 'Module_'.ucfirst(strtolower($slug));

		// Now we need to talk to it
		return class_exists($class) ? new $class : false;
	}
}