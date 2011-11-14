<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Fields Library
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Fields
{
    function __construct()
    {
    	$this->CI =& get_instance();
    
		$this->CI->load->helper('form');
	}

	// --------------------------------------------------------------------------

	/**
	 * Build form input
	 *
	 * Builds an individual form input from the
	 * type object
	 *
	 * @access	public
	 * @param	obj
	 * @param	bool
	 * @return	string
	 */
	public function build_form_input( $field, $value = FALSE, $row_id = NULL )
	{
		$tmp = $field->field_type;
		
		$type = $this->CI->type->types->$tmp;
		
		$form_data['form_slug']		= $field->field_slug;
		
		if( !isset($field->field_data['default_value']) ):
		
			$field->field_data['default_value'] = '';
		
		endif;
		
		// Set the value
		$value ? $form_data['value'] = $value : $form_data['value'] = $field->field_data['default_value'];
		
		// Do we have a post value? That should 
		// Take precendence over everything else
		// @todo - this currently causes some problems
		// but we can let this loose in an upcoming version
		/*if($this->CI->input->post($field->field_slug)):
		
			$form_data['value'] = $this->CI->input->post($field->field_slug);
		
		endif;*/
		
		$form_data['custom'] = $field->field_data;
		
		// Set the max_length
		if( isset($field->field_data['max_length']) ):
		
			$form_data['max_length'] = $field->field_data['max_length'];
			
		endif;

		// Get form output
		return $type->form_output($form_data, $row_id, $field);
	}

	// --------------------------------------------------------------------------
	
    /**
     * Build the form validation rules and the actual output.
     *
     * Based on the type of application we need it for, it will
     * either return a full form or an array of elements.
     * 
     * @access	public
     * @param	obj
     * @param	string
     * @param	bool
     * @param	bool - is this a plugin call?
     * @param	bool - are we using reCAPTCHA?
     * @param	array - all the skips
     * @return	mixed
     */
 	public function build_form($data, $method, $row = FALSE, $plugin = FALSE, $recaptcha = FALSE, $skips = array())
 	{ 	
 		$this->data = $data;
 	
 		$this->data->method = $method;
 		
 		$this->data->row = $row;
 		
 		// -------------------------------------
		// Get Stream Fields
		// -------------------------------------
		
		$this->data->stream_fields = $this->CI->streams_m->get_stream_fields( $this->data->stream_id );
		
		// Can't do nothing if we don't have any fields		
		if( $this->data->stream_fields === FALSE ):
					
			return FALSE;
			
		endif;
		
		// -------------------------------------
		// Run Type Events
		// -------------------------------------

		$events_called = array();
		
		foreach($this->data->stream_fields as $field):
		
			if(!in_array($field->field_slug, $skips)):
		
				// If we haven't called it (for dupes),
				// then call it already.
				if(!in_array($field->field_type, $events_called)):
				
					if(method_exists($this->CI->type->types->{$field->field_type}, 'event')):
					
						$this->CI->type->types->{$field->field_type}->event($field);
					
					endif;
					
					$events_called[] = $field->field_type;
				
				endif;
			
			endif;
		
		endforeach;
				
		// -------------------------------------
		// Set Validation Rules
		// -------------------------------------

		$this->set_rules($this->data->stream_fields, $method, $skips);
		
		// -------------------------------------
		// Set reCAPTCHA
		// -------------------------------------

		if( $recaptcha ):
		
			$this->CI->config->load('streams/recaptcha');
		
			$this->CI->load->library('streams/Recaptcha');
			
			$this->CI->streams_validation->set_rules('recaptcha_response_field', 'lang:recaptcha_field_name', 'required|check_captcha');
		
		endif;
		
		// -------------------------------------
		// Set Values
		// -------------------------------------

		$this->data->values = array();
		
		foreach( $this->data->stream_fields as $stream_field ):
		
			if(!in_array($stream_field->field_slug, $skips)):
		
				if( $method == "new" ):
			
					$this->data->values[$stream_field->field_slug] = $this->CI->input->post($stream_field->field_slug);
				
				else:
				
					$node = $stream_field->field_slug;
					
					if( isset($row->$node) ):
				
						$this->data->values[$stream_field->field_slug] = $row->$node;
					
					else:
					
						$this->data->values[$stream_field->field_slug] = null;
					
					endif;
					
					$node = null;
				
				endif;
			
			endif;
		
		endforeach;

		// -------------------------------------
		// Validation
		// -------------------------------------
		
		$result_id = '';

		if( $this->CI->streams_validation->run() ):
		
			if($method == 'new'):
	
				if( ! $result_id = $this->CI->fields_m->insert_fields_to_stream($_POST, $this->data->stream_fields, $this->data->stream, $skips ) ):
				
					$this->CI->session->set_flashdata('notice', $this->CI->lang->line('streams.add_entry_error'));	
				
				else:
				
					$this->CI->session->set_flashdata('success', $this->CI->lang->line('streams.entry_add_success'));	
				
				endif;
			
			else:
			
				if( ! $result_id = $this->CI->row_m->update_row($this->data->stream_fields, $this->data->stream, $row->id, $skips ) ):
				
					$this->CI->session->set_flashdata('notice', $this->CI->lang->line('streams.update_entry_error'));	
				
				else:
				
					$this->CI->session->set_flashdata('success', $this->CI->lang->line('streams.entry_update_success'));	
				
				endif;
			
			endif;
			
			// Redirect based on if this is a plugin call or not

			if( $plugin ):
						
				redirect( str_replace('-id-', $result_id, $this->data->return) );
			
			else:

				redirect('admin/streams/entries/index/'.$this->data->stream->id);
			
			endif;
		
		endif;
		
		// -------------------------------------
		// Build Output
		// -------------------------------------
		
		if( $plugin ):
		
			$this->data->output = $this->data->values;
		
			return $this->data;
		
		else:
		
        	$this->CI->template->build('admin/entries/form', $this->data);
		
		endif;
	}

	// --------------------------------------------------------------------------

	/**
	 * Set Rules
	 *
	 * Set the rules from the stream fields
	 */	
	public function set_rules( $stream_fields, $method, $skips )
	{
		// -------------------------------------
		// Loop through and set the rules
		// -------------------------------------
	
		foreach( $stream_fields  as $stream_field ):
		
			if(!in_array($stream_field->field_slug, $skips)):

				// Get the type object
				$type_call = $stream_field->field_type;	
				$type = $this->CI->type->types->$type_call;	
			
				$rules = array(
					'field'	=> $stream_field->field_slug,
					'label' => $stream_field->field_name,
					'rules'	=> ''				
				);
				
				// -------------------------------------
				// Set required if necessary
				// -------------------------------------
							
				if( $stream_field->is_required == 'yes' ):
				
					if( isset($type->input_is_file) && $type->input_is_file === TRUE ):
					
						$rules['rules'] .= '|file_required['.$stream_field->field_slug.']';
					
					else:
					
						$rules['rules'] .= '|required';
					
					endif;
				
				endif;
				
				// -------------------------------------
				// Set unique if necessary
				// -------------------------------------
	
				if( $stream_field->is_unique == 'yes' ):
				
					$rules['rules'] .= '|unique['.$stream_field->field_slug.':'.$method.':'.$stream_field->stream_id.']';
				
				endif;
				
				// -------------------------------------
				// Set extra validation
				// -------------------------------------
				
				
				if( isset($type->extra_validation) ):
				
					$rules['rules'] .= '|'.$type->extra_validation;
				
				endif;
	
				// -------------------------------------
				// Set them rules
				// -------------------------------------
	
				$this->CI->streams_validation->set_rules( $rules['field'], $rules['label'], $rules['rules'] );
				
				// Reset this baby!
				$rules = array();
			
			endif;

		endforeach;
	}

}

/* End of file Fields.php */