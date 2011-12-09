<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Streams Validation Library
 *
 * Contains custom functions that cannot be used in a
 * callback method.
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Streams_validation extends CI_Form_validation
{
	function __construct()
	{
		//parent::CI_Form_validation();
	
		$this->CI =& get_instance();
	}

	// --------------------------------------------------------------------------

	/**
	 * Check captcha callback
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function check_captcha($val)
	{
		if($this->CI->recaptcha->check_answer(
						$this->CI->input->ip_address(),
						$this->CI->input->post('recaptcha_challenge_field'),
						$val)):
		
	    	return TRUE;
		
		else:
		
			$this->CI->streams_validation->set_message(
						'check_captcha',
						$this->CI->lang->line('recaptcha_incorrect_response'));
			
			return FALSE;
	    
	    endif;
	}

	// --------------------------------------------------------------------------

	/**
	 * Is unique
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	obj
	 * @return	bool
	 */
	public function unique( $string, $data )
	{
		// Split the data
		$items = explode(":", $data);
		
		$column 	= $items[0];
		$mode 		= $items[1];
		$stream_id	= $items[2];
		
		// Get the stream
		$stream = $this->CI->streams_m->get_stream($stream_id);
			
		$this->CI->db->where(trim($column), trim($string));
		
		$obj = $this->CI->db->get(STR_PRE.$stream->stream_slug);
		
		if( $mode == 'new' ):
		
			if( $obj->num_rows() == 0 ):
			
				return TRUE;
			
			endif;

		elseif( $mode == 'edit' ):
		
			// We need to see if the new value is different.
			$this->CI->db->select(trim($column))->limit(1)->where( 'id', $this->CI->input->post('row_edit_id') );
			$db_obj = $this->CI->db->get(STR_PRE.$stream->stream_slug);
			
			$existing = $db_obj->row();
			
			if( $existing->$column == $string ):
			
				// No change
				if( $obj->num_rows() >= 1 ): return true; endif;
				
			else:
			
				// There was a change. We treat it as new now.
				if( $obj->num_rows() == 0 ): return true; endif;
			
			endif;
		
		endif;

		$this->CI->streams_validation->set_message('unique', lang('streams.field_unique'));
	
		return false;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * File is Required
	 *
	 * Tricky function that checks various inputs needed for files
	 * to see if one is indeed added.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function file_required( $string, $field )
	{
		// Do we already have something? If we are editing the row,
		// the file may already be there. We know that if the ID has a
		// numerical value, since it is hooked up with the PyroCMS
		// file system.
		if( is_numeric($this->CI->input->post($field)) ):
		
			return TRUE;
		
		endif;
		
		// OK. Now we really need to make sure there is a file here.
		// The method of choice here is checking for a file name		
		if( isset($_FILES[$field.'_file']['name']) && $_FILES[$field.'_file']['name'] != '' ):

			// Don't do shit.
					
		else:
		
			$this->CI->streams_validation->set_message('file_required', lang('streams.field_is_required'));
			return FALSE;
			
		endif;
	
		return;
	}

}

/* End of file Streams_validation.php */