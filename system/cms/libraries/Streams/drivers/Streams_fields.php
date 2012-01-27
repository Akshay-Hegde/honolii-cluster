<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams API Library
 *
 * @package  	Streams API
 * @category  	Libraries
 * @author  	Parse19
 */

// --------------------------------------------------------------------------
 
/**
 * Fields Driver
 *
 * @package  	Streams API
 * @category  	Drivers
 * @author  	Parse19
 */ 
 
class Streams_fields extends CI_Driver {

	/**
	 * Add field
	 *
	 * @access	public
	 * @param	array - field_data
	 * @return	bool
	 */
	function add_field($field)
	{
		$CI = get_instance();
	
		extract($field);
	
		// -------------------------------------
		// Validate Data
		// -------------------------------------
		
		// Do we have a field name?
		if ( ! isset($name) OR ! trim($name))
		{
			$this->log_error('empty_field_name', 'add_field');
			return FALSE;
		}			

		// Do we have a field slug?
		if( ! isset($slug) OR ! trim($slug))
		{
			$this->log_error('empty_field_slug', 'add_field');
			return FALSE;
		}

		// Do we have a namespace?
		if( ! isset($namespace) OR ! trim($namespace))
		{
			$this->log_error('empty_field_namespace', 'add_field');
			return FALSE;
		}
		
		// Is this stream slug already available?
		if (is_object($CI->fields_m->get_field_by_slug($slug, $namespace)))
		{
			$this->log_error('field_slug_in_use', 'add_field');
			return FALSE;
		}

		// Is this a valid field type?
		if ( ! isset($type) OR ! isset($CI->type->types->$type) )
		{
			$this->log_error('invalid_fieldtype', 'add_field');
			return FALSE;
		}
		
		// Set extra
		if ( ! isset($extra) OR ! is_array($extra)) $extra = array();
	
		// -------------------------------------
		// Create Field
		// -------------------------------------

		if ( ! $CI->fields_m->insert_field($name, $slug, $type, $namespace, $extra)) return FALSE;
		
		$field_id = $CI->db->insert_id();

		// -------------------------------------
		// Assignment (Optional)
		// -------------------------------------

		if (isset($assign) and $assign != '' and (is_object($stream = $CI->streams_m->get_stream($assign, TRUE, $namespace))))
		{
			$data = array();
		
			// Title column
			if (isset($title_column) and $title_column === TRUE)
			{
				$data['title_column'] = 'yes';
			}

			// Instructions
			$data['instructions'] = (isset($instructions) and $instructions != '') ? $instructions : NULL;
			
			// Is Unique
			if (isset($unique) and $unique === TRUE)
			{
				$data['is_unique'] = 'yes';
			}
			
			// Is Required
			if (isset($required) and $required === TRUE)
			{
				$data['is_required'] = 'yes';
			}
		
			// Add actual assignment
			$CI->streams_m->add_field_to_stream($field_id, $stream->id, $data);
		}
		
		return $field_id;
	}

	// --------------------------------------------------------------------------

	/**
	 * Add an array of fields
	 *
	 * @access	public
	 * @param	array - array of fields
	 * @return	bool
	 */
	function add_fields($fields)
	{
		if ( ! is_array($fields)) return FALSE;
		
		foreach ($fields as $field):
		
			$this->add_field($field);
		
		endforeach;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete field
	 *
	 * @access	public
	 * @param	string - field slug
	 * @param	string - field namespace
	 * @return	bool
	 */
	function delete_field($field_slug, $namespace)
	{
		if ( ! trim($field_slug)) return FALSE;
	
		if ( ! $field = $this->CI->fields_m->get_field_by_slug($field_slug)) return FALSE;
	
		return $this->CI->fields_m->delete_field($field->id);
	}

	// --------------------------------------------------------------------------

	/**
	 * Update field
	 *
	 * @access	public
	 * @param	string - slug
	 * @param	array - new data
	 * @return	bool
	 */
	function update_field($field_slug, $field_namespace, $field_data)
	{
		if ( ! trim($field_slug) ) return FALSE;
	
		if ( ! $field = $this->CI->fields_m->get_field_by_slug($field_slug, $field_namespace)) return FALSE;

		return $this->CI->fields_m->update_field($field, $field_data);
	}

	// --------------------------------------------------------------------------

	/**
	 * Get assigned fields for
	 * a stream.
	 *
	 * @access	public
	 * @param	[int - limit]
	 * @param	[int - offset]
	 * @return	object
	 */
	function get_field_assignments($field_slug, $limit = null, $offset = 0)
	{
		if( !trim($field_slug) ) return false;
	
		if( !$field = $this->CI->fields_m->get_field_by_slug($field_slug) ) return false;
	
		return $this->CI->fields_m->get_assignments($field->id);
	}

	// --------------------------------------------------------------------------

	/**
	 * Get fields for a stream.
	 *
	 * This includes the input and other
	 * associated data.
	 *
	 * @access	public
	 * @param	[int - limit]
	 * @param	[int - offset]
	 * @return	object
	 */
	function get_stream_fields($stream, $stream_namespace = NULL, $current_data = array(), $entry_id = null)
	{
		$assignments = $this->CI->field_m->get_assignments_for_stream($this->stream_id($stream, $stream_namespace));
		
		$return = array();
		
		if ( ! $assignments) return $return;
		
		foreach ($assignments as $assign)
		{
			// Get the input
			$value = null;
		
			if(isset($current_data[$assign->field_slug])) $value = $current_data[$assign->field_slug];
	
			$return[$count]['input'] = $this->CI->fields->build_form_input($assign, $value, $entry_id);
		
			unset($value);
			
			// Other data
			$return[$count]['value'] 			= $value;
			$reutnr[$count]['instructions']		= $assign->instructions;
		}
		
		return $return;
	}

	// --------------------------------------------------------------------------

	/**
	 * Get fields for a stream.
	 *
	 * This includes the input and other
	 * associated data.
	 *
	 * @access	public
	 * @param	[int - limit]
	 * @param	[int - offset]
	 * @return	object
	 */
	function get_field($field_slug)
	{
		
	}

	// --------------------------------------------------------------------------

	/**
	 * Get validation array for fields.
	 *
	 * @access	public
	 * @param	string - name
	 * @param	string - slug
	 * @param	string - type
	 * @param	[array - extra field data]
	 * @return	bool
	 */
	function get_fields_validation($stream)
	{
		
	}

	// --------------------------------------------------------------------------

	/**
	 * Assign field to stream
	 *
	 * @access	public
	 * @param	stream
	 * @param	field
	 * @return	bool
	 */
	/*function assign_field($stream, $field)
	{
		
	}*/
	
}