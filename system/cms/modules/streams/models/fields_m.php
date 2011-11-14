<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Fields Model
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Fields_m extends CI_Model {

	public $table;

    // --------------------------------------------------------------------------

	/**
	 * Fields Validation
	 */
	public $fields_validation = array(
		array(
			'field'	=> 'field_name',
			'label' => 'Field Name',
			'rules'	=> 'trim|required|max_length[60]'
		),
		array(
			'field'	=> 'field_slug',
			'label' => 'Field Slug',
			'rules'	=> 'trim|required|max_length[60]|callback_mysql_safe'
		),
		array(
			'field'	=> 'field_type',
			'label' => 'Field Type',
			'rules'	=> 'trim|required|max_length[50]|callback_type_valid'
		)
	);
	
	function __construct()
	{
		$this->table = FIELDS_TABLE;
	}
    
    // --------------------------------------------------------------------------
    
    /**
     * Get some fields
     *
     * @access	public
     * @param	int limit
     * @param	int offset
     * @return	obj
     */
    public function get_fields( $limit = FALSE, $offset = 0 )
	{
		if($offset) $this->db->offset($offset);
		
		if($limit) $this->db->limit($limit);

		$query = $this->db->order_by('field_name', 'asc')->get($this->table);
     
    	return $query->result();
	}
    
    // --------------------------------------------------------------------------
    
    /**
     * Get all fields with extra field info
     *
     * @access	public
     * @param	int limit
     * @param	int offset
     * @return	obj
     */
    public function get_all_fields()
	{
		$obj = $this->db->order_by('field_name', 'asc')->get($this->table);
		
		$fields = $obj->result_array();
		
		$return_fields = array();

		foreach($fields as $key => $field):
		
			$return_fields[$field['field_slug']] = $field;
 			$return_fields[$field['field_slug']]['field_data'] = unserialize($field['field_data']);
		
		endforeach; 
    	
    	return $return_fields;
	}

    // --------------------------------------------------------------------------
    
    /**
     * Count fields
     *
     * @access	public
     * @return	int
     */
	public function count_fields()
	{
		return $this->db->count_all($this->table);
	}

    // --------------------------------------------------------------------------

	/**
	 * Insert a field
	 *
	 * @access	public
	 * @return	bool
	 */
	public function insert_field()
	{
		foreach( $this->fields_validation as $item ):
		
			$insert_data[$item['field']] = $this->input->post($item['field']);
		
		endforeach;
		
		// Load the type to see if there are other fields
		$type_call = $this->input->post('field_type');
		
		$field_type = $this->type->types->$type_call;
		
		if( isset($field_type->custom_parameters) ):
		
			$extra_data = array();
		
			foreach( $field_type->custom_parameters as $param ):
			
				$extra_data[$param] = $this->input->post($param);
			
			endforeach;
		
			$insert_data['field_data'] = serialize($extra_data);
		
		endif;
		
		return $this->db->insert($this->table, $insert_data);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Take field data and parse it into an array
	 * the the DB forge class can use
	 *
	 * @param	public
	 * @param	obj
	 * @param	array
	 * @param	string
	 * @return	array
	 */
	public function field_data_to_col_data( $type, $field_data, $method = 'add' )
	{
		$col_data = array();

		// -------------------------------------		
		// Name
		// -------------------------------------
		
		if( $method == 'edit' ):		

			$col_data['name'] 				= $field_data['field_slug'];
		
		endif;
		
		// -------------------------------------		
		// Col Type
		// -------------------------------------		
	
		$col_data['type'] 				= strtoupper($type->db_col_type);
		
		// -------------------------------------		
		// Constraint
		// -------------------------------------
		
		// First we check and see if a constraint has been added
		if(isset($type->col_constraint) and $type->col_constraint!=''):
		
			$col_data['constraint']		= $type->col_constraint;
			
		// Otherwise, we'll check for a max_length field
		elseif( isset($field_data['max_length']) and is_numeric($field_data['max_length']) ):
			
			$col_data['constraint']		= $field_data['max_length'];
		
		endif;

		// -------------------------------------		
		// Text field varchar change
		// -------------------------------------
		
		if( $type->field_type_slug == 'text' ):		

			if( isset($col_data['constraint']) && $col_data['constraint'] > 255 ):
			
				$col_data['type'] 				= 'TEXT';
				
				// Don't need a constraint no more
				unset($col_data['constraint']);
				
			else:
			
				$col_data['type'] 				= 'VARCHAR';
			
			endif;
		
		endif;

		// -------------------------------------		
		// Default
		// -------------------------------------		
		
		if( isset($field_data['default_value']) && $field_data['default_value'] != '' ):
		
			$col_data['default']		= $field_data['default_value'];
		
		endif;

		// -------------------------------------		
		// Remove Default for some col types:
		//
		// * TEXT
		// * LONGTEXT
		// -------------------------------------
		
		$no_default = array('TEXT', 'LONGTEXT');
		
		if( in_array($col_data['type'], $no_default) ):
		
			unset($col_data['default']);
		
		endif;

		// -------------------------------------		
		// Default to allow null
		// -------------------------------------		

		$col_data['null'] = true;

		// -------------------------------------		
		// Check for varchar with no constraint
		// -------------------------------------		

		if( $col_data['type'] == 'VARCHAR' && ( !isset($col_data['constraint']) || !is_numeric($col_data['constraint']) || $col_data['constraint'] == '' ) ):
		
			$col_data['constraint'] = 255;
		
		endif;

		// -------------------------------------	
		
		return $col_data;	
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Update field
	 *
	 * @access	public
	 * @param	obj
	 * @param	int
	 */
	public function update_field( $field )
	{	
		$type = $this->type->types->{$this->input->post('field_type')};
		
		// -------------------------------------		
		// We want to change columns if the
		// following change:
		// 
		// * Field Slug
		// * Max Length
		// * Default Value
		// -------------------------------------		

		$assignments = $this->get_assignments( $field->id );
		
		if( 
			$field->field_slug != $this->input->post('field_slug') or 
			( isset( $field->field_data['max_length'] ) and  $field->field_data['max_length'] != $this->input->post('max_length') ) or  
			( isset( $field->field_data['default_value'] ) and  $field->field_data['default_value'] != $this->input->post('default_value') )
		):
				
			// If so, we need to update some table names
			// Get the field assignments and change the table names
						
			// Check first to see if there are any assignments
			if( $assignments ):
			
				// Alter the table names
				$this->load->dbforge();
				
				foreach( $assignments as $assignment ):
				
					if(method_exists($type, 'alt_rename_column')):						
						// We run a different function for alt_process
						$type->alt_rename_column($field, $this->streams_m->get_stream($assignment->stream_slug));
					
					else:
					
						// Run the regular column renaming
						$fields[$field->field_slug] = $this->field_data_to_col_data( $type, $_POST, 'edit' );
					
						if( ! $this->dbforge->modify_column(STR_PRE.$assignment->stream_slug, $fields) ):
						
							return FALSE;
						
						endif;
						
					endif;
					
					// Update the view options
					$view_options = unserialize($assignment->view_options);
					
					foreach( $view_options as $key => $option ):
					
						if( $option == $field->field_slug ):
						
							// Replace with the new field slug so nothing goes apeshit
							$view_options[$key] = $this->input->post('field_slug');
						
						endif;					
					
					endforeach;
					
					$vo_update_data['view_options'] = serialize($view_options);
	
					$this->db->where('id', $assignment->stream_id)->update(STREAMS_TABLE, $vo_update_data);
	
					$vo_update_data 	= array();
					$view_options 		= array();
				
				endforeach;
				
			endif;
		
		endif;
		
		// Run edit field update hook
		if(method_exists($type, 'update_field')):						
			
			$type->update_field($field, $assignments);
		
		endif;
			
		// Update field information		
		$update_data['field_name']		= $this->input->post('field_name');
		$update_data['field_slug']		= $this->input->post('field_slug');
		$update_data['field_type']		= $this->input->post('field_type');
		
		// Gather extra data		
		if( !isset($type->custom_parameters) || $type->custom_parameters == '' ):
		
			$custom_params = array();
			
			$update_data['field_data'] = null;
		
		else:
		
			foreach( $type->custom_parameters as $param ):
			
				$custom_params[$param] = $this->input->post($param);
			
			endforeach;

			$update_data['field_data'] = serialize($custom_params);
		
		endif;
		
		$this->db->where('id', $field->id);
					
		if( $this->db->update('data_fields', $update_data) ):
		
			$tc_update['title_column']	= $this->input->post('field_slug');
		
			// Success. Now let's update the title column.
			$this->db->where('title_column', $field->field_slug);
			return $this->db->update(STREAMS_TABLE, $tc_update);
			
		else:
		
			// Boo.
			return FALSE;
		
		endif;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get assignments for a field
	 *
	 * @access	public
	 * @param	int
	 * @return	mixed
	 */
	public function get_assignments( $field_id )
	{
		$this->db->select(STREAMS_TABLE.'.*, '.STREAMS_TABLE.'.id as stream_id');
		$this->db->from(STREAMS_TABLE.', '.ASSIGN_TABLE);
		$this->db->where(PYROSTREAMS_DB_PRE.STREAMS_TABLE.'.id', PYROSTREAMS_DB_PRE.ASSIGN_TABLE.'.stream_id', FALSE);
		$this->db->where(PYROSTREAMS_DB_PRE.ASSIGN_TABLE.'.field_id', $field_id, FALSE);
		
		$obj = $this->db->get();
			
		if( $obj->num_rows() == 0 ) return FALSE;
		
		return $obj->result();
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete a field
	 *
	 * @access	public
	 * @param	int
	 * @return	bool
	 */
	public function delete_field( $field_id )
	{
		// Make sure field exists		
		if( ! $field = $this->get_field( $field_id) ):
		
			return FALSE;
		
		endif;
	
		// Find assignments, and delete rows from table
		$assignments = $this->get_assignments( $field_id );
		
		if( $assignments ):
		
			$this->load->dbforge();
		
			$outcome = TRUE;
		
			// Cycle and delete columns			
			foreach( $assignments as $item ):
			
				if( ! $this->dbforge->drop_column(STR_PRE.$item->stream_slug, $field->field_slug) ):
				
					$outcome = FALSE;
				
				endif;
				
				// Update that stream's view options
				$view_options = unserialize($item->view_options);
				
				foreach( $view_options as $key => $option ):
				
					if( $option == $field->field_slug ):
					
						unset($view_options[$key]);
					
					endif;					
				
				endforeach;
				
				$update_data['view_options'] = serialize($view_options);

				$this->db->where('id', $item->stream_id);
				$this->db->update(STREAMS_TABLE, $update_data);

				$update_data 	= array();
				$view_options 	= array();
			
			endforeach;
			
			if( ! $outcome ):
			
				return $outcome;
			
			endif;
		
		endif;
		
		// Delete assignment entries		
		$this->db->where('field_id', $field->id);
		
		if( ! $this->db->delete(ASSIGN_TABLE) ):
		
			return FALSE;
		
		endif;
		
		// Blank out title_cols
		$this->db->where('title_column', $field->field_slug);
		$this->db->update(STREAMS_TABLE, array('title_column'=>NULL));
		
		// Delete from fields list		
		$this->db->where('id', $field->id);
		
		if( ! $this->db->delete(FIELDS_TABLE) ):
		
			return FALSE;
		
		endif;
		
		return TRUE;
	}

	// --------------------------------------------------------------------------

	/**
	 * Get a single field
	 *
	 * @access	public
	 * @param	int
	 * @return	obj
	 */
	public function get_field( $field_id )
	{
		$this->db->limit(1)->where('id', $field_id);
		
		$obj = $this->db->get($this->table);
		
		if( $obj->num_rows() == 0 ):
		
			return FALSE;
		
		endif;
		
		$field = $obj->row();
		
		$field->field_data = unserialize($field->field_data);
		
		return $field;
	}

	// --------------------------------------------------------------------------

	/**
	 * Get a single field by the field slug
	 *
	 * @access	public
	 * @param	int
	 * @return	obj
	 */
	public function get_field_by_slug( $field_slug )
	{
		$this->db->limit(1)->where('field_slug', $field_slug);
		
		$obj = $this->db->get($this->table);
		
		if( $obj->num_rows() == 0 ):
		
			return FALSE;
		
		endif;
		
		$field = $obj->row();
		
		$field->field_data = unserialize($field->field_data);
		
		return $field;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Insert field to a stream
	 *
	 * @access	public
	 * @param	array
	 * @param	obj
	 * @param	obj
	 * @param	array - optional skipping fields
	 * @return	mixed
	 */
	public function insert_fields_to_stream($post, $fields, $stream, $skips = array())
	{
		// -------------------------------------
		// Run through fields
		// -------------------------------------

		$insert_data = array();
		
		$alt_process = array();
	
		foreach( $fields as $field ):
		
			if(!in_array($field->field_slug, $skips)):
		
				// Need to get rid of this
				$type_tmp = $field->field_type;
				
				$type = $this->type->types->$type_tmp;
				
				if( isset($post[$field->field_slug]) ):
				
					// We don't process the alt process stuff.
					// This is for field types that store data outside of the
					// actual table
					if(isset($type->alt_process) and $type->alt_process === TRUE):
									
						$alt_process[] = $field->field_slug;
					
					else:
					
						if( method_exists($type, 'pre_save') ):
						
							$post[$field->field_slug] = $type->pre_save( $post[$field->field_slug], $field, $stream );
						
						endif;
						
						// Trim if a string
						if(is_string($post[$field->field_slug]))
							$post[$field->field_slug] = trim($post[$field->field_slug]);
						
						$insert_data[$field->field_slug] = $post[$field->field_slug];
					
					endif;
				
				else:
				
					// Set an empty value if there is no post data
					// for a field.
					$insert_data[$field->field_slug] = '';
				
				endif;
			
			endif;
		
		endforeach;

		// -------------------------------------
		// Set standard fields
		// -------------------------------------

		$insert_data['created'] 	= date('Y-m-d H:i:s');
		$insert_data['created_by'] 	= $this->current_user->id;

		// -------------------------------------
		// Set incremental ordering
		// -------------------------------------
		
		$db_obj = $this->db->select("MAX(ordering_count) as max_ordering")->get(STR_PRE.$stream->stream_slug);
		
		if( $db_obj->num_rows() == 0 || !$db_obj ):
		
			$ordering = 0;
		
		else:
		
			$order_row = $db_obj->row();
			
			if( !is_numeric($order_row->max_ordering) ):
			
				$ordering = 0;
			
			else:
			
				$ordering = $order_row->max_ordering;
			
			endif;
		
		endif;

		$insert_data['ordering_count'] 	= $ordering+1;

		// -------------------------------------
		// Insert data
		// -------------------------------------
		
		if( ! $this->db->insert(STR_PRE.$stream->stream_slug, $insert_data) ):
		
			return FALSE;
		
		else:
		
			$id = $this->db->insert_id();
			
			// Process any alt process stuff
			foreach( $alt_process as $field_slug ):
			
				$type->pre_save( $post[$field_slug], $fields->{$field_slug}, $stream, $id );
			
			endforeach;
			
			return $id;
		
		endif;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Edit Assignment
	 *
	 * @access	public
	 * @param	int
	 * @param	obj
	 * @param	obj
	 * return	bool
	 */
	public function edit_assignment( $assignment_id, $stream, $field )
	{
		// -------------------------------------
		// Check for title column
		// -------------------------------------
		// See if this should be made the title column
		// -------------------------------------

		if( $this->input->post('title_column') == 'yes' ):
		
			$title_update_data['title_column'] = $field->field_slug;
		
			$this->db->where('id', $stream->id );
			$this->db->update('data_streams', $title_update_data);
		
		endif;

		// Is required	
		if( $this->input->post('is_required') == 'yes' ):
		
			$update_data['is_required'] = 'yes';
			
		else:
		
			$update_data['is_required'] = 'no';
		
		endif;
		
		// Is unique
		if( $this->input->post('is_unique') == 'yes' ):
		
			$update_data['is_unique'] = 'yes';
			
		else:
		
			$update_data['is_unique'] = 'no';
		
		endif;
			
		// Instructions		
		$update_data['instructions'] = $this->input->post('instructions');
		
		$this->db->where('id', $assignment_id);
		
		return $this->db->update(ASSIGN_TABLE, $update_data);
	}

}

/* End of file fields_m.php */