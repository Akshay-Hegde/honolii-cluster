<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * API module
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\API
 */
class Module_Api extends Module
{
	public $version = '1.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'API Management',
				'el' => 'Διαχείριση API',
				'fr' => 'Gestionnaire d\'API',
				'hu' => 'API Kezelés'
			),
			'description' => array(
				'en' => 'Set up a RESTful API with API Keys and out in JSON, XML, CSV, etc.',
				'el' => 'Ρυθμίσεις για ένα RESTful API με κλειδιά API και αποτελέσματα σε JSON, XML, CSV, κτλ.',
				'fr' => 'Paramétrage d\'une API RESTgul avec clés API et export en JSON, XML, CSV, etc.',
                'hu' => 'Körültekintően állítsd be az API-t (alkalmazásprogramozási felület) az API Kulcsokkal együtt és küldd JSON-ba, XML-be, CSV-be, vagy bármi egyébbe.',
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'utilities',
			'sections' => array(
				'overview' => array(
					'name' => 'api:overview',
					'uri' => 'admin/api',
				),
				'keys' => array(
					'name' => 'api:keys',
					'uri' => 'admin/api/keys',
					'shortcuts' => array(
						array(
						    'name' => 'global:add',
						    'uri' => 'admin/api/keys/create',
						    'class' => 'add'
						),
					),
				),
			),
		);
	}

	public function install()
	{
		$this->load->driver('Streams');

		if ( ! $this->streams->streams->add_stream('lang:api:api_keys', 'keys', 'api', 'api_'))
		{
			return false;
		}

		// Set the custom view fields for this stream
		$this->db
			->set('view_options', serialize(array('id', 'key', 'user_id', 'level', 'created')))
			->where('stream_name', 'lang:api:api_keys')
			->update('data_streams');

		// Add some fields
		$this->streams->fields->add_fields(array(
			array(
				'name'			=> 'lang:api:api_key',
				'slug'			=> 'key',
				'assign'		=> 'keys',
				'namespace'		=> 'api',
				'type'			=> 'text',
				'extra'			=> array('max_length' => 40),
				'title_column'	=> TRUE,
				'required'		=> TRUE,
				'unique'		=> TRUE,
			),
			array(
				'name'			=> 'lang:global:user',
				'slug'			=> 'user_id',
				'assign'		=> 'keys',
				'namespace'		=> 'api',
				'type'			=> 'user',
				'required'		=> TRUE,
			),
			array(
				'name'			=> 'lang:api:key_level',
				'slug'			=> 'level',
				'assign'		=> 'keys',
				'namespace'		=> 'api',
				'type'			=> 'text',
				'extra'			=> array('max_length' => 2, 'default_value' => '1'),
				'required'		=> TRUE,
			),
			array(
				'name'			=> 'lang:api:key_ignore_limits',
				'slug'			=> 'ignore_limits',
				'assign'		=> 'keys',
				'namespace'		=> 'api',
				'type'			=> 'choice',
				'extra'			=> array(
					'max_length' 	=> 1,
					'default_value' => '0',
					'choice_type'	=> 'radio',
					'choice_data'	=> "0 : lang:global:no\n1 : lang:global:yes",
				),
			),
			array(
				'name'			=> 'lang:api:key_is_active',
				'slug'			=> 'active',
				'namespace'		=> 'api',
				'type'			=> 'choice',
				'extra'			=> array(
					'max_length' 	=> 1,
					'default_value' => '0',
					'choice_type'	=> 'radio',
					'choice_data'	=> "0 : lang:global:no\n1 : lang:global:yes",
				),
			),
		));

		// // Make the key key an index
		// TODO Index this... somehow. Stupid DBForge
		// $this->dbforge
		// 	->add_key('key')
		// 	->alter_table('api_keys');

		// Create Logging table
		$this->dbforge
			->add_field(array(
				'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
				'uri' => array('type' => 'varchar', 'constraint' => 255),
				'method' => array('type' => 'varchar', 'constraint' => 6),
				'params' => array('type' => 'text', 'null' => true),
				'api_key' => array('type' => 'varchar', 'constraint' => 40),
				'ip_address' => array('type' => 'varchar', 'constraint' => 15),
				'time' => array('type' => 'int', 'constraint' => 11),
				'authorized' => array('type' => 'tinyint', 'constraint' => 1),
			))
			// Make the key Primary (thats what true does)
			->add_key('id', true)
			// Make the api_key key an index
			->add_key('api_key')
			// Now build it!
			->create_table('api_logs');


		return true;
	}

	public function uninstall()
	{
		$this->load->driver('Streams');

		$this->streams->streams->delete_stream('keys', 'api');
		$this->streams->utilities->remove_namespace('api');

		$this->dbforge->drop_table('api_logs');

		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}