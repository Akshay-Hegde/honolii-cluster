<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Migrations Library
 *
 * Allows you to update a table based on a master
 *
 * Schema structure
 *
 * deprecated_tables 		=> array of table names no longer user
 * renamed_tables 			=> array of tables (old => new) that have been renamed
 * tables					=> array of tables. See structure below:
 *
 * fields					=> array of DB Forge-readable field data
 * primary_key				=> PK field (string)
 * keys						=> array of key fields
 * renames					=> array (old => new) of renamed fields
 * deprecated_fields		=> array of no longer used fields
 *
 * @author		Adam Fairholm (@adamfairholm)
 * @copyright	Copyright (c) 2011, Adam Fairholm
 */
class Migrate
{
	public $schema;

    function __construct()
    {
		$this->CI =& get_instance();
		
		$this->CI->load->dbforge();
	}
	
	public function run_schema($schema)
	{
		// -------------------------------------
		// Drop Deprecated Tables
		// -------------------------------------
		
		if(isset($schema['deprecated_tables']) and !empty($schema['deprecated_tables'])):
		
			foreach($schema['deprecated_tables'] as $d_table):
			
				if($this->CI->db->table_exists($d_table)):
				
					$this->CI->dbforge->drop_table($d_table);
				
				endif;
			
			endforeach;
			
		endif;
		
		// -------------------------------------
		// Rename Renamed Tables
		// -------------------------------------
		
		if(isset($schema['renamed_tables']) and !empty($schema['renamed_tables'])):
		
			foreach($schema['renamed_tables'] as $old_name => $new_name):
			
				if($this->CI->db->table_exists($old_name) and !$this->CI->db->table_exists($old_name)):
				
					$this->CI->dbforge->rename_table($old_name, $new_name);
				
				endif;
			
			endforeach;
			
		endif;

		// -------------------------------------
		// Migrate Tables
		// -------------------------------------

		if(isset($schema['tables']) and !empty($schema['tables'])):
		
			foreach($schema['tables'] as $table_name => $schema):
			
				$this->run_table_update($table_name, $schema);
			
			endforeach;
			
		endif;
	}

	// --------------------------------------------------------------------------

	public function run_table_update($table_name, $schema)
	{
		// -------------------------------------
		// Create New Table if it doesn't exist
		// -------------------------------------

		if(!$this->CI->db->table_exists($table_name)):
		
			$this->CI->dbforge->add_field($schema['fields']);
			 
			// Add keys
			if(isset($schema['keys']) and !empty($schema['keys'])):
			
				$this->CI->dbforge->add_key($schema['keys']);
			
			endif;

			// Add primary key
			if(isset($schema['primary_key'])):
			
				$this->CI->dbforge->add_key($schema['primary_key'], TRUE);
			
			endif;
			
			$this->CI->dbforge->create_table($table_name);
			 
		else:
		
			// Delete deprecated fields 
			if(isset($schema['deprecated_fields']) and !empty($schema['deprecated_fields'])):

				foreach($schema['deprecated_fields'] as $delete_field):
				
					if($this->CI->db->field_exists($delete_field, $table_name)):
				
						$this->CI->dbforge->drop_column($table_name, $delete_field);

					endif;
				
				endforeach;		
			
			endif;
		
			// Run through the fields of our table
			foreach($schema['fields'] as $field_name => $field_data):
			
				// If a field does not exist, then create it.
				if(!$this->CI->db->field_exists($field_name, $table_name)):

					$this->CI->dbforge->add_column($table_name, array($field_name => $field_data));
					
				else:
				
					// Okay, it exists, we are just going to modify it.
					// If the schema is the same it won't hurt it.
					
					// By the way, is this a column that is in our renamed column
					// array? We search the vals where we keep the renamed to names
					if(isset($schema['renames']) and in_array($field_name, $schema['renames'])):
					
						// It is, so let's rename the column
						$field_data['name'] = $field_name;
					
					endif;
					
					$this->CI->dbforge->modify_column($table_name, array($field_name => $field_data));
				
				endif;
				
			endforeach;
	
		endif;
	}
	
	public function destruct($schema)
	{
		if(isset($schema['tables']) and !empty($schema['tables'])):
		
			foreach($schema['tables'] as $table_name => $schema):
			
				if($this->CI->db->table_exists($table_name)):
				
					$this->CI->dbforge->drop_table($table_name);
				
				endif;
			
			endforeach;
			
		endif;
	}

}

/* End of file Migrate.php */