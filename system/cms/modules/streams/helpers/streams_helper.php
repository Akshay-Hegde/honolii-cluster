<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Fields Helper
 *
 * @package		PyroStreams
 * @author		Addict Add-ons Dev Team
 * @copyright	Copyright (c) 2011, Addict Add-ons
 * @license		http://addictaddons.com/pyrostreams/license
 * @link		http://addictaddons.com/pyrostreams
 */
function field_types_array( $add_lead = FALSE )
{
	$return = array();

	$CI =& get_instance();
	
	// Add the lead for the drop down if we need it
	if( $add_lead ):
	
		$return = array('-' => '-- Pick a Field Type --');
	
	endif;
	
	//Cycle through, load, and get data	
	foreach( $CI->type->types as $type ):
	
		$return[$type->field_type_slug] = $type->field_type_name;
	
	endforeach;
	
	return $return;
}

// -------------------------------------------------------------------------- 

/**
 * Streams Constants
 *
 * Declare some constants *
 */
function streams_constants()
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

		$CI =& get_instance();
		
		$CI->load->config('streams/streams');

		define('STR_PRE', $CI->config->item('stream_prefix'));	
	
	endif;
}

// -------------------------------------------------------------------------- 

/**
 * Load admin resources
 */
function admin_resources()
{
	$CI =& get_instance();

    $CI->load->model(array('streams/fields_m', 'streams/streams_m', 'streams/row_m'));
   
	$CI->template->append_metadata('<style>table.form_table td {border-bottom: none;} table.form_table td.label_col {text-align: right;} small {display: block; color: #535353;} .actions {text-align: right;} .handle {cursor: move;}</style>');

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

// -------------------------------------------------------------------------- 

/**
 * User name from ID
 *
 * Convenience function for the back end
 *
 * @param	id - user id
 */
function username_from_id($id)
{
	$id = trim($id);

	if(!is_numeric($id) or $id=='') return;

	$CI = get_instance();
	
	$obj = $CI->db->where('id', $id)->limit(1)->get('users');

	if($obj->num_rows() == 0) return;
	
	$row = $obj->row();
	
	return '<a href="'.site_url('admin/users/edit/'.$row->id).'">'.$row->username.'</a>';
}

/* End of file fields_helper.php */
/* Location: /streams/helpers/fields_helper.php */