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
		$this->load->helper('streams/streams');
		
		streams_constants();
		
		$this->load->library('streams/Migrate');
	}

	// --------------------------------------------------------------------------

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Streams',
				'el' => 'Ροές',
				'es' => 'Streams'
			),
			'description' => array(
				'en' => 'Manage, structure, and display data.',
				'el' => 'Διαχείριση, δόμηση και προβολή πληροφοριών και δεδομένων.',
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
		
		return $info;
	}
	
	// --------------------------------------------------------------------------
	
	public function install()
	{
		$this->migrate->run_schema($this->schema());
		
		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function uninstall()
	{
		// Get streams and delete them
		$this->load->config('streams/streams');
		$this->load->helper('streams/streams');
		streams_constants();
		$this->load->library('streams/type');
		$this->type->gather_types();
        $this->load->model(array('streams/fields_m', 'streams/streams_m', 'streams/row_m'));
		
		$this->load->dbforge();
		
		$streams = $this->db->get(STREAMS_TABLE)->result();

				
		foreach( $streams as $stream ):
		
			$this->streams_m->delete_stream( $this->streams_m->get_stream($stream->stream_slug, TRUE) );
		
		endforeach;
		
		// Drop the other tables
		$this->migrate->destruct($this->schema());

		return TRUE;
	}

	// --------------------------------------------------------------------------
	
	public function upgrade($old_version)
	{
		$this->migrate->run_schema($this->schema());

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

	// --------------------------------------------------------------------------

	/**
	 * PyroStreams DB Schema
	 *
	 * @access	public
	 * @return	array
	 */
	public function schema()
	{
		return array(
			'removed_tables' => array('streams_basic_access'),
			'tables' => array(
	            STREAMS_TABLE => array(
	                'fields' => array(
	                	'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
	                	'stream_name' => array('type' => 'VARCHAR', 'constraint' => 60),
	                	'stream_slug' => array('type' => 'VARCHAR', 'constraint' => 60),
	                	'about' => array('type' => 'VARCHAR', 'constraint' => 255),
	                	'view_options' => array('type' => 'BLOB'),
	                	'title_column' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
	                	'sorting' => array('type' => 'ENUM', 'constraint' => "'title', 'custom'", 'default' => 'title')),
	                'primary_key' => 'id'),
	            FIELDS_TABLE => array(
	                'fields' => array(
	                	'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
	                	'field_name' => array('type' => 'VARCHAR', 'constraint' => 60),
	                	'field_slug' => array('type' => 'VARCHAR', 'constraint' => 60),
	                	'field_type' => array('type' => 'VARCHAR', 'constraint' => 50),
	                	'field_data' => array('type' => 'BLOB', 'null' => true),
	                	'view_options' => array('type' => 'BLOB', 'null' => true)),
	                'primary_key' => 'id'),
	            ASSIGN_TABLE => array(
	                'fields' => array(
	                	'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
	                	'sort_order' => array('type' => 'INT', 'constraint' => 11),
	                	'stream_id' => array('type' => 'INT', 'constraint' => 11),
	                	'field_id' => array('type' => 'INT', 'constraint' => 11),
	                	'is_required' => array('type' => 'ENUM', 'constraint' => "'yes', 'no'", 'default' => 'no'),
	                	'is_unique' => array('type' => 'ENUM', 'constraint' => "'yes', 'no'", 'default' => 'no'),
	                	'instructions' => array('type' => 'VARCHAR', 'constraint' => 255),
	                	'field_name' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true)),
	                'primary_key' => 'id'),
				SEARCH_TABLE => array(
	                'fields' => array(
	                	'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
	                	'search_id' => array('type' => 'VARCHAR', 'constraint' => 255),
	                	'search_term' => array('type' => 'TEXT'),
	                	'ip_address' => array('type' => 'VARCHAR', 'constraint' => 100),
	                	'total_results' => array('type' => 'INT', 'constraint' => 11),
	                	'query_string' => array('type' => 'LONGTEXT'),
	                	'stream_slug' => array('type' => 'VARCHAR', 'constraint' => 255)),
	                'primary_key' => 'id')
	            )
	        );
	}

}

/* End of file details.php */