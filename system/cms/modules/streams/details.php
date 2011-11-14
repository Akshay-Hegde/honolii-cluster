<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Module
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Module_Streams extends Module {

	public $version = '2.1';
	
	function __construct()
	{
		$this->streams_constants();
	}

	// --------------------------------------------------------------------------

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Streams',
				'es' => 'Streams'
			),
			'description' => array(
				'en' => 'Manage, structure, and display data.',
				'es' => 'Administra, estructura, y presenta datos.'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'author' => 'Parse19',
			'menu' => 'content',
			'roles' => array('admin_streams', 'admin_fields'),
			'sections' => array(
			    'streams' => array(
				    'name' => 	'streams.streams',
				    'uri' => 	'admin/streams'
				),
				'fields' => array(
				    'name' => 'streams.fields',
				    'uri' => 'admin/streams/fields',
				    'shortcuts' => array(
						array(
							'name' => 'streams.field_types',
							'uri' => 'admin/streams/fields/types',
						),
						array(
							'name' => 'streams.new_field',
							'uri' => 'admin/streams/fields/add',
							'class' => 'add'
						)
				    ),
			    ),
		    )
		);

		$assignment_uris = array('assignments', 'new_assignment', 'edit_assignment', 'edit', 'view_options');
		
		if(function_exists('group_has_role')):
		
			// Streams Add 
			if(
				group_has_role('streams', 'admin_streams') and 
				!in_array($this->uri->segment(3), $assignment_uris) and
				$this->uri->segment(3) != 'entries'
			):
			
				$info['sections']['streams']['shortcuts'][] = array(
						'name' => 'streams.add_stream',
						'uri' => 'admin/streams/add',
						'class' => 'add');
					
			endif;
			
			// Assignment Add
			if(
				group_has_role('streams', 'admin_streams') and
				in_array($this->uri->segment(3), $assignment_uris) and
				$this->uri->segment(3) != 'entries' or 
				$this->uri->segment(3) == 'manage'):
			
				$info['sections']['streams']['shortcuts'][] = array(
						'name' => 'streams.new_field_assign',
						'uri' => 'admin/streams/new_assignment/'.$this->uri->segment(4),
						'class' => 'add');
			
			endif;
						
			// Entries
			if(
				!in_array($this->uri->segment(3), $assignment_uris) and
				$this->uri->segment(3) == 'entries'
			):
	
				if(group_has_role('streams', 'admin_streams') ):
	
					$info['sections']['streams']['shortcuts'][] = array(
						'name' => 'streams.manage',
						'uri' => 'admin/streams/manage/'.$this->uri->segment(5));
						
				endif;
			
				$info['sections']['streams']['shortcuts'][] = array(
						'name' => 'streams.add_entry',
						'uri' => 'admin/streams/entries/add/'.$this->uri->segment(5),
						'class' => 'add');
	
			endif;
		
		endif;
		
		return $info;
	}
	
	// --------------------------------------------------------------------------
	
	public function install()
	{
		$this->db->query("
		CREATE TABLE `".$this->db->dbprefix(STREAMS_TABLE)."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `stream_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		  `stream_slug` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		  `about` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `view_options` blob NOT NULL,
		  `title_column` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `sorting` enum('title','custom') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'title',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		$this->db->query("
		CREATE TABLE `".$this->db->dbprefix(FIELDS_TABLE)."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `field_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		  `field_slug` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		  `field_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `field_data` blob,
		  `view_options` blob,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		
		$this->db->query("
		CREATE TABLE `".$this->db->dbprefix(ASSIGN_TABLE)."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `sort_order` int(11) NOT NULL,
		  `stream_id` int(11) NOT NULL,
		  `field_id` int(11) NOT NULL,
		  `is_required` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
		  `is_unique` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
		  `instructions` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `field_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		
		$this->db->query("
		CREATE TABLE `".$this->db->dbprefix(SEARCH_TABLE)."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `search_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `search_term` text COLLATE utf8_unicode_ci NOT NULL,
		  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  `total_results` int(11) NOT NULL,
		  `query_string` longtext COLLATE utf8_unicode_ci NOT NULL,
		  `stream_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		
		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function uninstall()
	{		
		$this->load->dbforge();
		
		$obj = $this->db->get(STREAMS_TABLE);
		
		$streams = $obj->result();
		
		foreach( $streams as $stream ):
		
			$this->dbforge->drop_table(STR_PRE.$stream->stream_slug);
		
		endforeach;
		
		// Drop the other tables
		$this->dbforge->drop_table(SITE_REF.'_'.STREAMS_TABLE);
		$this->dbforge->drop_table(SITE_REF.'_'.FIELDS_TABLE);
		$this->dbforge->drop_table(SITE_REF.'_'.ASSIGN_TABLE);
		$this->dbforge->drop_table(SITE_REF.'_'.SEARCH_TABLE);

		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function upgrade($old_version)
	{
		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function streams_constants()
	{
		if(!defined('STREAMS_TABLE')):
		
			define('STREAMS_TABLE', 'data_streams');
		
		endif;
		
		if(!defined('FIELDS_TABLE')):
		
			define('FIELDS_TABLE', 'data_fields');
		
		endif;
		
		if(!defined('ASSIGN_TABLE')):
		
			define('ASSIGN_TABLE', 'data_field_assignments');
		
		endif;
		
		if(!defined('SEARCH_TABLE')):
		
			define('SEARCH_TABLE', 'data_stream_searches');
		
		endif;
		
		if(!defined('BASIC_ACCESS_TABLE')):
		
			define('BASIC_ACCESS_TABLE', 'data_streams_basic_access');
		
		endif;
		
		if(!defined('STR_PRE')):
		
			define('STR_PRE', 'str_');	
		
		endif;
	}

	// --------------------------------------------------------------------------
	
	public function help()
	{
		$out = '<p>Documentation for PyroStreams can be found here:</p>
		
		<p><a href="http://parse19.com/pyrostreams/docs" target="_blank">http://parse19.com/pyrostreams/docs</a></p>
		
		<p>Support for PyroStreams can be found here::</p>
		
		<p><a href="http://parse19.com/support" target="_blank">http://parse19.com/support</a></p>';
		
		return $out;
	}

}

/* End of file details.php */