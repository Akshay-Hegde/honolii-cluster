<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Subscriptions extends Module
{
	public $version = '1.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Subscriptions'
			),
			'description' => array(
				'en' => 'Require payment for users to be part of certain groups, giving them access to certain features.',
			),
			'frontend' => true,
			'backend'  => true,
			'menu'	  => 'users',
			'skip_xss' => TRUE,
			
			'sections' => array(
			    'subscriptions' => array(
				    'name' => 'subscriptions:subscriptions',
				    'uri' => 'admin/subscriptions',
				    'shortcuts' => array(
						array(
					 	   'name' => 'blog_create_title',
						   'uri' => 'admin/blog/create',
						   'class' => 'add'
						),
					),
				),
				'features' => array(
				    'name' => 'subscriptions:features',
				    'uri' => 'admin/subscriptions/features',
				    'shortcuts' => array(
						array(
						    'name' => 'cat_create_title',
						    'uri' => 'admin/blog/categories/create',
						    'class' => 'add'
						),
				    ),
			    ),
		    ),
		);
	}

	public function install()
	{
		// TODO Install some streamy coolshit.
		return TRUE;
	}

	public function uninstall()
	{
		return TRUE;
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
		return "No documentation has been added for this module.";
	}
}
/* End of file details.php */