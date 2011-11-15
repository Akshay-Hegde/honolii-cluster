<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Relationship Field Type
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_relationship
{
	public $field_type_name 		= 'Relationship';
	
	public $field_type_slug			= 'relationship';
	
	public $db_col_type				= 'int';

	public $custom_parameters		= array( 'choose_stream' );

	public $version					= '1.1';

	public $author					= array('name'=>'Parse19', 'url'=>'http://parse19.com');

	public $lang					= array(
	
		'en'	=> array(
				'choose_stream'	=> 'Relationship Stream'
		)
	
	);			
	
	// --------------------------------------------------------------------------
	
	function __construct()
	{
		$this->CI =& get_instance();
	}

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output( $data )
	{	
		// Get slug stream
		$stream = $this->CI->streams_m->get_stream($data['custom']['choose_stream']);
		
		// @todo - languagize
		if(!$stream) return '<em>Related stream does not exist.</em>';

		$title_column = $stream->title_column;
		
		// Default to ID for title column
		if(!trim($title_column) or !$this->CI->db->field_exists($title_column, STR_PRE.$stream->stream_slug)):
		
			$title_column = 'id';
		
		endif;
	
		// Get the entries
		$obj = $this->CI->db->get( STR_PRE.$stream->stream_slug );
		
		$choices = array();
		
		foreach($obj->result() as $row):
		
			// Need to replace with title column
			$choices[$row->id] = $row->$title_column;
		
		endforeach;
		
		// Output the form input
		return form_dropdown($data['form_slug'], $choices, $data['value'], 'id="'.$data['form_slug'].'"');
	}

	// --------------------------------------------------------------------------

	/**
	 * Get a list of streams to choose from
	 *
	 * @access	public
	 * @return	string
	 */
	public function param_choose_stream( $stream_id = FALSE )
	{
		$this->CI =& get_instance();
		
		$this->CI->db->select('id, stream_name');
		$db_obj = $this->CI->db->get('data_streams');
		
		$streams = $db_obj->result();
		
		foreach( $streams as $stream ):
		
			$choices[$stream->id] = $stream->stream_name;
		
		endforeach;
		
		return form_dropdown('choose_stream', $choices, $stream_id);
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before outputting on the CP
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input, $data)
	{	
		// We only need this in the admin.
		// Relationships are taken care of by a join
		// on the front end
		if($this->CI->uri->segment(1) != 'admin') return;
	
		$stream = $this->CI->streams_m->get_stream($data['choose_stream']);

		$title_column = $stream->title_column;

		// -------------------------------------
		// Data Checks
		// -------------------------------------
		
		// Make sure the table exists still. If it was deleted we don't want to
		// have everything go to hell.
		if(!$this->CI->db->table_exists($this->CI->config->item('stream_prefix').$stream->stream_slug)):
		
			return;
		
		endif;
		
		// We need to make sure the select is NOT null.
		// So, if we have no title column, let's use the id
		if(trim($title_column) == ''):
			
			$title_column = 'id';
		
		endif;

		// -------------------------------------
		// Get the entry
		// -------------------------------------
		
		$this->CI->db->select($title_column)->where('id', $input);
		$obj = $this->CI->db->get($this->CI->config->item('stream_prefix').$stream->stream_slug);	
		
		$row = $obj->row();

		if( isset($row->$title_column) ):
		
			return $row->$title_column;
		
		endif;
		
		return '';
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Format a relationship row
	 * 
	 * Note - this will only be processed in the event
	 * of a relationship inside of a relationship. Top-level
	 * relationships are handled by a join.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	array
	 */
	function pre_output_plugin($prefix, $row, $custom)
	{
		// We only want to do this for second level stuff.
		// First level gets taken care of by a join.
		$segs = explode('.', $prefix);
		if(count($segs) == 2) return;
		
		// Okay good to go
		$stream = $this->CI->streams_m->get_stream($custom['choose_stream']);
		
		$obj = $this->CI->db->where('id', $row)->get(STR_PRE.$stream->stream_slug);
		
		if($obj->num_rows() == 0) return;
		
		$returned_row = $obj->row();
		
		$return = array(rtrim($prefix.$r, '.') => $returned_row->{$stream->title_column});
		
		foreach($returned_row as $r => $val):
		
			$return[$prefix.$r] = $val;
		
		endforeach;
		
		$stream_fields = $this->CI->streams_m->get_stream_fields($stream->id);

		return $this->CI->row_m->format_row( $return, $stream_fields, $stream, FALSE, TRUE, $prefix );
	}

}

/* End of file field.relationship.php */