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

	public $version = '2.1.2';
	
	// --------------------------------------------------------------------------

	public function info()
	{
		$info = array(
			'name' => array(
				'en' 	=> 'Streams',
				'es' 	=> 'Streams',
				'de' 	=> 'Streams',
				'el' 	=> 'Ροές',
				'lt' 	=> 'Srautai',
				'fr'	=> 'Streams'
			),
			'description' => array(
				'en' 	=> 'Manage, structure, and display data.',
				'es' 	=> 'Administra, estructura, y presenta datos.',
				'de' 	=> 'Verwalte, strukturiere und veröffentliche Daten.',
				'el' 	=> 'Διαχείριση, δόμηση και προβολή πληροφοριών και δεδομένων.',
				'lt' 	=> null,
				'fr'	=> 'Gérer, structurer et afficher des données'
			),
			'frontend' 	=> false,
			'backend' 	=> true,
			'is_core' 	=> false,
			'author' 	=> 'Parse19',
			'menu' 		=> 'content',
			'roles' 	=> array('admin_streams', 'admin_fields')
		);

		if (function_exists('group_has_role'))
		{
			if (group_has_role('streams', 'admin_streams'))
			{
				$info['sections']['streams'] = array(
					    'name' => 	'streams.streams',
					    'uri' => 	'admin/streams'
					);
			}
			
			if (group_has_role('streams', 'admin_fields'))
			{
				$info['sections']['fields'] = array(
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
					);
			}

			$assignment_uris = array('assignments', 'new_assignment', 'edit_assignment', 'edit', 'view_options');
			
			$shortcuts = array();
			
			// Streams Add 
			if(
				group_has_role('streams', 'admin_streams') and 
				! in_array($this->uri->segment(3), $assignment_uris) and
				$this->uri->segment(3) != 'entries'
			)
			{
				$shortcuts[] = array(
						'name' => 'streams.add_stream',
						'uri' => 'admin/streams/add',
						'class' => 'add');
			}	
			
			// Assignment Add
			if(
				group_has_role('streams', 'admin_streams') and
				in_array($this->uri->segment(3), $assignment_uris) and
				$this->uri->segment(3) != 'entries' or 
				$this->uri->segment(3) == 'manage')
			{
			
				$shortcuts[] = array(
						'name' => 'streams.new_field_assign',
						'uri' => 'admin/streams/new_assignment/'.$this->uri->segment(4),
						'class' => 'add');
			}
					
			// Entries
			if(
				!in_array($this->uri->segment(3), $assignment_uris) and
				$this->uri->segment(3) == 'entries'
			):

				if(group_has_role('streams', 'admin_streams') ):

				$shortcuts[] = array(
						'name' => 'streams.manage',
						'uri' => 'admin/streams/manage/'.$this->uri->segment(5));
						
				endif;
			
				$shortcuts[] = array(
						'name' => 'streams.add_entry',
						'uri' => 'admin/streams/entries/add/'.$this->uri->segment(5),
						'class' => 'add');

			endif;
			
			// We only need to nest the shortcuts in sections
			// if we actually need sections.
			if (group_has_role('streams', 'admin_streams') or group_has_role('streams', 'admin_fields'))
			{
				$info['sections']['streams']['shortcuts'] = $shortcuts;
			}
			else
			{
				$info['shortcuts'] = $shortcuts;
			}
		}

		return $info;
	}
	
	// --------------------------------------------------------------------------
	
	public function install()
	{
		if (defined('PYROPATH'))
		{
			require_once(PYROPATH.'modules/streams/config/streams.php');
		}
		else
		{
			require_once(APPPATH.'modules/streams/config/streams.php');
		}

		$this->db->query("
		CREATE TABLE `".$this->db->dbprefix($config['streams.streams_table'])."` (
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
		CREATE TABLE `".$this->db->dbprefix($config['streams.fields_table'])."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `field_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		  `field_slug` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		  `field_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `field_data` blob,
		  `view_options` blob,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		
		$this->db->query("
		CREATE TABLE `".$this->db->dbprefix($config['streams.assignments_table'])."` (
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
		CREATE TABLE `".$this->db->dbprefix($config['streams.searches_table'])."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `search_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `search_term` text COLLATE utf8_unicode_ci NOT NULL,
		  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  `total_results` int(11) NOT NULL,
		  `query_string` longtext COLLATE utf8_unicode_ci NOT NULL,
		  `stream_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		
		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function uninstall()
	{
		// core modules can't be uninstalled
		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function upgrade($old_version)
	{
		return TRUE;
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