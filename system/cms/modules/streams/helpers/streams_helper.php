<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Helper
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */

// -------------------------------------------------------------------------- 

/**
 * Field Types array
 *
 * Create a drop down of field types
 */
function field_types_array()
{
	$return = array();

	$CI =& get_instance();
	
	// For the chosen data placeholder value
	$return[null] = null;
		
	foreach( $CI->type->types as $type ):
	
		$return[$type->field_type_slug] = $type->field_type_name;
	
	endforeach;
	
	asort($return);
	
	return $return;
}

// -------------------------------------------------------------------------- 

/**
 * Streams Constants
 *
 * This is just for legacy and will evenutally
 * be depricated in favor of straight configs.
 */
function streams_constants()
{
	$CI =& get_instance();

	$CI->load->config('streams/streams');

	if(!defined('STREAMS_TABLE')):

		define('STREAMS_TABLE', $CI->config->item('streams.streams_table'));
	
	endif;

	if(!defined('FIELDS_TABLE')):

		define('FIELDS_TABLE', $CI->config->item('streams.fields_table'));
	
	endif;

	if(!defined('ASSIGN_TABLE')):

		define('ASSIGN_TABLE', $CI->config->item('streams.assignments_table'));
	
	endif;

	if(!defined('SEARCH_TABLE')):

		define('SEARCH_TABLE', $CI->config->item('streams.searches_table'));
	
	endif;

	if(!defined('STR_PRE')):

		define('STR_PRE', $CI->config->item('stream_prefix'));	
	
	endif;
}

// -------------------------------------------------------------------------- 

/**
 * Load admin resources
 *
 * This is handy because the admin sections are split among
 * several controllers.
 */
function admin_resources()
{
	$CI =& get_instance();

    $CI->load->model(array('streams/fields_m', 'streams/streams_m', 'streams/row_m'));
   
	// -------------------------------------
	// Validation Resources
	// -------------------------------------
	// Using our special validation
	// -------------------------------------

	$CI->load->library('form_validation');
	$CI->load->library('streams/Streams_validation');
	
	// -------------------------------------
	// Gather Types
	// -------------------------------------
	// Get our types together for use in
	// many different places.
	// Referenced via $this->type->types		
	// -------------------------------------
	
	$CI->load->library('streams/Type');
	$CI->type->gather_types();		
}

/* End of file fields_helper.php */