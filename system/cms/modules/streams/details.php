<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Module
 *
 * @package		PyroStreams
 * @author		Adam Fairholm
 * @copyright	Copyright (c) 2011 - 2013, Adam Fairholm
 */
class Module_Streams extends Module {

	/**
	 * PyroStreams Version Number
	 *
	 * @access	public
	 * @var		string
	 */
	public $version = '2.3.2';

	// --------------------------------------------------------------------------

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Streams',
				'es' => 'Streams',
				'de' => 'Streams',
				'el' => 'Ροές',
				'lt' => 'Srautai',
				'fr' => 'Streams'
			),
			'description' => array(
				'en' => 'Manage, structure, and display data.',
				'es' => 'Administra, estructura, y presenta datos.',
				'de' => 'Verwalte, strukturiere und veröffentliche Daten.',
				'el' => 'Διαχείριση, δόμηση και προβολή πληροφοριών και δεδομένων.',
				'lt' => null,
				'fr' => 'Gérer, structurer et afficher des données'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'is_core' => FALSE,
			'author' => 'Parse19',
			'menu' => 'content',
			'roles' => array('admin_streams', 'admin_fields')
		);
		
		if( function_exists('group_has_role'))
		{
			if (group_has_role('streams', 'admin_streams'))
			{
				$info['sections']['streams'] = array(
					    'name' => 	'streams:streams',
					    'uri' => 	'admin/streams'
					);
			}
			
			if (group_has_role('streams', 'admin_fields'))
			{
				$info['sections']['fields'] = array(
					    'name' => 'streams:fields',
					    'uri' => 'admin/streams/fields',
					    'shortcuts' => array(
							array(
								'name' => 'streams:new_field',
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
				($this->uri->segment(3) != 'entries' and $this->uri->segment(3) != 'manage')
			)
			{
				$shortcuts[] = array(
						'name' => 'streams:add_stream',
						'uri' => 'admin/streams/add/',
						'class' => 'add');
			}	

			// Entry Add 
			if(
				! in_array($this->uri->segment(3), $assignment_uris) and
				($this->uri->segment(3) != 'entries' and $this->uri->segment(3))
			)
			{
				$shortcuts[] = array(
						'name' => 'streams:add_entry',
						'uri' => 'admin/streams/entries/add/'.$this->uri->segment(4),
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
						'name' => 'streams:new_field_assign',
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
						'name' => 'streams:manage',
						'uri' => 'admin/streams/manage/'.$this->uri->segment(5));
						
				endif;
			
				$shortcuts[] = array(
						'name' => 'streams:add_entry',
						'uri' => 'admin/streams/entries/add/'.$this->uri->segment(5),
						'class' => 'add');
	
			endif;
			
			// We only need to nest the shortcuts in sections
			// if we actually need sections.
			if (group_has_role('streams', 'admin_streams') OR group_has_role('streams', 'admin_fields'))
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

	public function admin_menu(&$menu)
	{
		$this->load->helper('streams/streams');

		// Get our streams in the streams core namespace
		$streams = $this->db
						->where('stream_namespace', 'streams')
						->where('menu_path !=', "''")
						->get('data_streams')->result();

		foreach ($streams as $stream)
		{
			if (check_stream_permission($stream, false))
			{
				$pieces = explode('/', $stream->menu_path, 2);

				$pieces[0] = trim($pieces[0]);

				if (substr($pieces[0], 0, 4) == 'nav_')
				{
					$pieces[0] = 'lang:cp:'.$pieces[0];
				}

				if (count($pieces) == 1)
				{
					$menu[$pieces[0]] = 'admin/streams/entries/index/'.$stream->id;
				}
				elseif (count($pieces) == 2)
				{
					$menu[$pieces[0]][trim($pieces[1])] = 'admin/streams/entries/index/'.$stream->id;
				}
			}
		}
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Install
	 *
	 * All core streams tables are taken care of by Streams
	 * Core now. We only need to add the searches table.
	 *
	 * @access	public
	 * @return	bool
	 */	
	public function install()
	{
		$this->load->config('streams/streams');
				
		// Our streams searches schema
		$schema = array(
			'fields' => array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'stream_slug' => array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'stream_namespace' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null' => TRUE
				),
				'search_id' => array(
					'type' => 'VARCHAR',
					'constraint' => 255,
				),
				'search_term' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'ip_address' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null' => TRUE
				),
				'total_results' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'query_string' => array(
					'type' => 'LONGTEXT',
					'null' => TRUE
				)),
			'primary_key' => 'id'
		);
		
		// Case where table does not exist.
		// Add fields and keys.
		if( ! $this->db->table_exists($this->config->item('streams:searches_table')))
		{
			$this->dbforge->add_field($schema['fields']);

			// Add primary key
			if( isset($schema['primary_key']))
			{
				$this->dbforge->add_key($schema['primary_key'], TRUE);
			}

			$this->dbforge->create_table($this->config->item('streams:searches_table'));
		}
		else
		{
			foreach ($schema['fields'] as $field_name => $field_data)
			{
				// If a field does not exist, then create it.
				if ( ! $this->db->field_exists($field_name, $this->config->item('streams:searches_table')))
				{
					$this->dbforge->add_column($this->config->item('streams:searches_table'), array($field_name => $field_data));	
				}
				else
				{
					// Okay, it exists, we are just going to modify it.
					// If the schema is the same it won't hurt it.
					$this->dbforge->modify_column($this->config->item('streams:searches_table'), array($field_name => $field_data));
				}
			}
		}
				
		return true;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Uninstall Streams
	 *
	 * Using API utilities function to remove
	 * all data associated with the 'streams' namespace
	 *
	 * We do not break down the core stream tables
	 * anymore since they are now part of streams_core.
	 * 
	 * @access	public
	 * @return 	bool
	 */
	public function uninstall()
	{
		$this->load->driver('Streams');
		
		$this->streams->utilities->remove_namespace('streams');
		
		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function upgrade($old_version)
	{
		$this->load->config('streams/streams');

		// Make sure we have the stream_namespace field in the search table
        if ( ! $this->db->field_exists('stream_namespace', $this->config->item('streams:searches_table')))
        {
            $this->dbforge->add_column($this->config->item('streams:searches_table'), array(
                'stream_namespace' => array(
    				'type' => 'VARCHAR',
    				'constraint' => 100,
    				'null' => true
    			)
            ));
        }

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