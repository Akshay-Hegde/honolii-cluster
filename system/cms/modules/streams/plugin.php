<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Plugin
 *
 * @package		PyroStreams
 * @author		Addict Add-ons Dev Team
 * @copyright	Copyright (c) 2011, Addict Add-ons
 * @license		http://addictaddons.com/pyrostreams/license
 * @link		http://addictaddons.com/pyrostreams
 */
class Plugin_Streams extends Plugin
{
	private $pagination_vars = array(
								'num_links'			=> 3,
								'full_tag_open'		=> '<p>',
								'full_tag_close'	=> '</p>',
								'first_link'		=> 'First',
								'first_tag_open'	=> '<div>',
								'first_tag_close'	=> '</div>',
								'last_link'			=> 'Last',
								'last_tag_open'		=> '<div>',
								'last_tag_close'	=> '</div>',
								'next_link'			=> '&gt;',
								'next_tag_open'		=> '<div>',
								'next_tag_close'	=> '</div>',
								'prev_link'			=> '&lt;',
								'prev_tag_open'		=> '<div>',
								'prev_tag_close'	=> '</div>',
								'cur_tag_open'		=> '<span>',
								'cur_tag_close'		=> '</span>',
								'num_tag_open'		=> '<div>',
								'num_tag_close'		=> '</div>'
	);
	
	public $types;
	
	public $rows;
	
	public $parse_tags;

	// --------------------------------------------------------------------------
	
	/**
	 * PyroStreams Plugin Construct
	 *
	 * Just a bunch of loads and prep 
	 */
	function __construct()
	{	
		$this->load->config('streams/streams');

		$this->load->helper('streams/streams');

        streams_constants();
        
		$this->load->library('streams/Type');
	
		$this->load->model(array('streams/row_m', 'streams/streams_m', 'streams/fields_m'));
		
		$this->types = $this->type->gather_types();
	}

	// --------------------------------------------------------------------------

	/**
	 * PyroStreams attribute function
	 *
	 * Allows you to pass stuff like [segment_1], etc.
	 *
	 * @access	public
	 * @param	string
	 * @param	[string]
	 * @return	string
	 */	
	public function streams_attribute($param, $default = NULL)
	{
		$value = $this->attribute($param, $default);
		
		// See if we have any vars in there
		if(strpos($value, '[') !== FALSE):
		
			$segs = array(
				'segment_1' => $this->uri->segment(1),
				'segment_2' => $this->uri->segment(2),
				'segment_3' => $this->uri->segment(3),
				'segment_4' => $this->uri->segment(4),
				'segment_5' => $this->uri->segment(5),
				'segment_6' => $this->uri->segment(6),
				'segment_7' => $this->uri->segment(7),
			);
						
			// We can only get the user data if it is available
			if($this->current_user):
			
				$segs['user_id']	= $this->current_user->id;
				$segs['username']	= $this->current_user->username;
			
			endif;

			foreach($segs as $seg_marker => $segment_value):
			
				$value = str_replace("[$seg_marker]", $segment_value, $value);
			
			endforeach;
		
		endif;
		
		return $value;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Data Cycle
	 *
	 * List data from a stream
	 *
	 * @access	public
	 */
	public function cycle()
	{		
		// -------------------------------------
		// Get vars
		// -------------------------------------
		
		$this->debug_status		 	= $this->streams_attribute('debug', 'off');
	
		$params['stream'] 			= $this->streams_attribute('stream');
		
		$params['limit'] 			= $this->streams_attribute('limit');
		
		$params['offset'] 			= $this->streams_attribute('offset', 0);

		$params['single'] 			= $this->streams_attribute('single', 'no');
		
		$params['id'] 				= $this->streams_attribute('id');
	
		$params['date_by'] 			= $this->streams_attribute('date_by', 'created');

		$params['year'] 			= $this->streams_attribute('year');
		
		$params['month'] 			= $this->streams_attribute('month');

		$params['day'] 				= $this->streams_attribute('day');
	
		$params['show_upcoming'] 	= $this->streams_attribute('show_upcoming', 'yes');

		$params['show_past'] 		= $this->streams_attribute('show_past', 'yes');
		
		$params['restrict_user']	= $this->streams_attribute('restrict_user', 'no');

		$params['where'] 			= $this->streams_attribute('where');

		$params['exclude'] 			= $this->streams_attribute('exclude');

		$params['exclude_by']		= $this->streams_attribute('exclude_by', 'id');

		$params['disable']			= $this->streams_attribute('disable');

		$params['order_by'] 		= $this->streams_attribute('order_by');

		$params['sort'] 			= $this->streams_attribute('sort', 'asc');

		$params['exclude_called'] 	= $this->streams_attribute('exclude_called', 'no');

		// -------------------------------------
		// Stream Data Check
		// -------------------------------------
	
		// @todo - languagize
		if( !$params['stream'] ): return $this->_error_out('Invalid Stream'); endif;

		$obj = $this->db->limit(1)->where('stream_slug', $params['stream'])->get(STREAMS_TABLE);
		
		// @todo - languagize
		if($obj->num_rows() == 0) return $this->_error_out('Invalid Stream');
		
		$stream = $obj->row();

		// -------------------------------------
		// Pagination vars
		// -------------------------------------

		$params['paginate'] 		= $this->streams_attribute('paginate', 'no');

		$params['pag_segment'] 		= $this->streams_attribute('pag_segment', 2);

		// Set a default per_page
		if($params['paginate'] == 'yes' && $params['limit'] != ''):
		
			$params['limit'] = $this->streams_attribute('limit', 25);

		endif;

		// -------------------------------------
		// Our Return Vars
		// -------------------------------------

		$return = array();
				
		// -------------------------------------
		// Get stream fields
		// -------------------------------------
				
		$this->fields = $this->streams_m->get_stream_fields($stream->id);

		// Get the rows
		$rows = $this->row_m->get_rows($params, $this->fields, $stream);
		$return[0]['entries'] = $rows['rows'];

		// Out for no entries
		if(count($return[0]['entries']) == 0) return $this->streams_attribute('no_results', 'No results');
				
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		$return[0]['pagination'] 	= null;
		
		if( $params['paginate'] == 'yes' ):

			$return[0]['total'] 	= $this->rows['pag_count'];
		
			$this->load->library('pagination');

			// Find Pagination base_url
			$segments = $this->uri->segment_array();
			
			if( isset($segments[count($segments)]) and is_numeric($segments[count($segments)]) ):
			
				unset($segments[count($segments)]);
			
			endif;
			
			$pag_uri = '';
			
			foreach($segments as $segment):
			
				$pag_uri .= $segment . '/';
			
			endforeach;
			
			$pagination_config['base_url'] 			= site_url( $pag_uri );
			
			// Set basic pagination data
			$pagination_config['total_rows'] 		= $return[0]['total'];
			$pagination_config['per_page'] 			= $params['limit'];
			$pagination_config['uri_segment'] 		= $params['pag_segment'];
			
			// Set pagination Configs
			foreach($this->pagination_vars as $var => $default):

				$pagination_config[$var] 		= $this->streams_attribute($var, $default);
				
				// Make sure we obey the FALSE params
				if($pagination_config[$var] == 'FALSE'):
					
					$pagination_config[$var] = FALSE;
				
				endif;
			
			endforeach;
						
			$this->pagination->initialize($pagination_config);
			
			$return[0]['pagination'] = $this->pagination->create_links();
					
		else:
		
			$return[0]['total'] 	= count($return[0]['entries']);
		
		endif;

		// -------------------------------------
		// Content Manipulation
		// -------------------------------------

		$content = $this->content();
		
		// Automatically addin
		$rep = array('{{ streams:related', '{{streams:related');
		$content = str_replace($rep, '{{ streams:related stream="'.$params['stream'].'"', $content);

		// -------------------------------------
		// Parse Rows
		// -------------------------------------

		$parser = new Lex_Parser();
		$parser->scope_glue(':');
		
		return $parser->parse_variables($content, $return[0]);
	}

	// --------------------------------------------------------------------------

	/**
	 * Related Entries
	 *
	 * This works with the multiple relationship field
	 *
	 * @access	public
	 * @return	array
	 */
	function related()
	{
		$rel_field = $this->attribute('field');
		$entry_id = $this->attribute('entry');

		$field = $this->fields_m->get_field_by_slug($rel_field);

		// Get the stream
		$join_stream = $this->streams_m->get_stream($field->field_data['choose_stream']);
			
		// Get the fields		
		$this->fields = $this->streams_m->get_stream_fields($join_stream->id);
		
		// Add the join_multiple hook to the get_rows function
		$this->row_m->get_rows_hook = array($this, 'join_multiple');
		$this->row_m->get_rows_hook_data = array(
		
			'join_table' => STR_PRE.$this->attribute('stream').'_'.$join_stream->stream_slug,
			'join_stream' => $join_stream,
			'row_id' =>  $this->attribute('entry')
		
		);
		
		$params = array(
			'stream'		=> $this->streams_attribute('stream'),
			'limit'			=> $this->streams_attribute('limit'),
			'offset'		=> $this->streams_attribute('offset', 0),
			'order_by'		=> $this->streams_attribute('order_by'),
			'exclude'		=> $this->streams_attribute('exclude'),
			'show_upcoming'	=> $this->streams_attribute('show_upcoming', 'yes'),
			'show_past'		=> $this->streams_attribute('show_past', 'yes'),
			'year'			=> $this->streams_attribute('year'),
			'month'			=> $this->streams_attribute('month'),
			'day'			=> $this->streams_attribute('day'),
			'restrict_user'	=> $this->streams_attribute('restrict_user', 'no'),
			'single'		=> $this->streams_attribute('single', 'no')
		);
		
		// Get the rows
		$this->rows = $this->row_m->get_rows($params, $this->fields, $join_stream);

		return $this->rows['rows'];
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Join multiple
	 *
	 * Multiple join callback
	 *
	 * @access	public
	 * @param	array - array of settings
	 * @return	void
	 */
	public function join_multiple($data)
	{
		$this->db->join(	
			$data['join_table'],
			$data['join_table'].'.'.$data['join_stream']->stream_slug.'_id = '.STR_PRE.$data['join_stream']->stream_slug.".id",
			'LEFT' );
		$this->db->where($data['join_table'].'.row_id', $data['row_id']);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Reverse Multiple Fields Function
	 *
	 * @access	public
	 * @return 	mixed
	 */	
	public function reverse_multiple()
	{
		$params['stream'] 			= $this->streams_attribute('stream');

		$related_field				= $this->streams_attribute('related_field');

		$entry_id 					= $this->streams_attribute('entry_id');
		
		$params['limit'] 			= $this->streams_attribute('limit');
		
		$params['offset'] 			= $this->streams_attribute('offset', 0);

		$params['date_by'] 			= $this->streams_attribute('date_by', 'created');

		$params['year'] 			= $this->streams_attribute('year');
		
		$params['month'] 			= $this->streams_attribute('month');

		$params['day'] 				= $this->streams_attribute('day');
	
		$params['show_upcoming'] 	= $this->streams_attribute('show_upcoming', 'yes');

		$params['show_past'] 		= $this->streams_attribute('show_past', 'yes');
		
		$params['restrict_user']	= $this->streams_attribute('restrict_user', 'no');

		$params['where'] 			= $this->streams_attribute('where');

		$params['exclude'] 			= $this->streams_attribute('exclude');

		$params['exclude_by']		= $this->streams_attribute('exclude_by', 'id');

		$params['disable']			= $this->streams_attribute('disable');

		$params['order_by'] 		= $this->streams_attribute('order_by');

		$params['sort'] 			= $this->streams_attribute('sort', 'asc');
		
		// We need a related field
		if(!$related_field) return;
		
		// We are pulling from the field stream, so we need to get that data
		$field = $this->fields_m->get_field_by_slug($related_field);
		
		// Make sure the field is a multiple
		if($field->field_type != 'multiple') return;
		
		// Get the join stream
		$join_stream = $this->streams_m->get_stream($field->field_data['choose_stream']);
		
		$this->fields = $this->streams_m->get_stream_fields($join_stream->id);
		
		$stream = $this->streams_m->get_stream($params['stream'], TRUE);

		// Add the join_multiple hook to the get_rows function
		$this->row_m->get_rows_hook = array($this, 'join_multiple');
		$this->row_m->get_rows_hook_data = array(
		
			'join_table' 	=> STR_PRE.$params['stream'].'_'.$join_stream->stream_slug,
			'join_stream' 	=> $join_stream,
			'row_id' 		=> $entry_id
		
		);

		// Get the rows
		$this->rows = $this->row_m->get_rows($params, $this->fields, $stream);

		$this->load->library('streams/Simpletags');

		$this->simpletags->set_trigger('entries');
		
		$parsed = $this->simpletags->parse($this->content(), array(), array($this, 'entries_parse'));
	
		return $parsed['content'];		
	}


	// --------------------------------------------------------------------------
	
	/**
	 * Output debug message or just an empty array
	 *
	 * @access	private
	 * @param	string
	 * @return 	mixed
	 */	
	private function _error_out( $msg )
	{
		if( $this->debug_status == 'on' ):
		
			return $msg;
		
		else:
		
			return FALSE;
		
		endif;
	}

	// --------------------------------------------------------------------------

	/**
	 * Get the total number of rows
	 *
	 * @access	public
	 * @return	int
	 */
	public function total()
	{
		$this->load->config('streams/streams');

		return $this->db->count_all(STR_PRE.$this->streams_attribute('stream'));
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Format date variables
	 *
	 * This could be done in an external plugin,
	 * but it is needed so much that were are
	 * going to add it in natively here.
	 *
	 * @access	public
	 * @return	string - formatted date
	 */
	public function date()
	{
		$date = $this->attribute('date');
		$format = $this->attribute('format');
		
		// No sense in trying to get down
		// with somedata that isn't there
		if(!$date or !$format) return null;
		
		// Is this a MySQL date or a UNIX date?
		if(!is_numeric($date)):
		
			$this->load->helper('date');
			$date = mysql_to_unix($date);
		
		endif;
		
		return date($format, $date);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Single
	 *
	 * Show a single item without the total,
	 * pagination, etc.
	 *
	 * @access	public
	 * @return	array
	 */
	public function single()
	{	
		// -------------------------------------
		// Get vars
		// -------------------------------------

		// We are going to set these to inert values
		// to start off with.
		$params = array(
			'limit'			=> 1,
			'offset'		=> 0,
			'order_by'		=> FALSE,
			'exclude'		=> FALSE,
			'show_upcoming'	=> NULL,
			'show_past'		=> NULL,
			'year'			=> NULL,
			'month'			=> NULL,
			'day'			=> NULL,
			'restrict_user'	=> 'no',
			'single'		=> 'yes'
		);
		
		$this->debug_status		 	= $this->streams_attribute('debug', 'off');
	
		$params['stream'] 			= $this->streams_attribute('stream');

		$params['id'] 				= $this->streams_attribute('entry_id');

		$params['where'] 			= $this->streams_attribute('where');

		$params['disable']			= $this->streams_attribute('disable');

		// -------------------------------------
		// Get stream
		// -------------------------------------
		
		// @todo - languagize
		if( !$params['stream'] ): return $this->_error_out('Invalid Stream'); endif;
		
		$stream = $this->streams_m->get_stream($params['stream'], TRUE);
		
		// @todo - languagize
		if( $stream === FALSE ) return $this->_error_out('Invalid Stream');
		
		// -------------------------------------
		// Disable
		// -------------------------------------
		// Allows users to turn off relationships
		// and created_by
		// -------------------------------------
		
		$params['disable'] ? $params['disable'] = explode("|", $params['disable']) : $params['disable'] = array();
		
		// -------------------------------------
		// Get stream fields
		// -------------------------------------
		
		$this->fields = $this->streams_m->get_stream_fields($stream->id);
		
		// -------------------------------------
		// Return Rows
		// -------------------------------------
		
		$this->rows = $this->row_m->get_rows($params, $this->fields, $stream);
		
		// @todo - languagize this
		if(!$this->rows) return $this->streams_attribute('no_results', 'No results');
		
		return $this->rows['rows'][0];
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Output an input form for a stream
	 *
	 * @access	public
	 * @return	array
	 */
	public function form()
	{
		// -------------------------------------
		// General Loads
		// -------------------------------------

		$data = new stdClass;
		
		$this->load->library(array('form_validation', 'streams/Streams_validation', 'streams/Fields'));
 
		// -------------------------------------
		// Get vars
		// -------------------------------------

		$mode 					= $this->streams_attribute('mode', 'new');

		$edit_id 				= $this->streams_attribute('edit_id', FALSE);

		$edit_segment 			= $this->streams_attribute('edit_segment', FALSE);
	
		$stream_slug 			= $this->streams_attribute('stream');

		$stream_segment 		= $this->streams_attribute('stream_segment');
		
		$required 				= $this->streams_attribute('required', '<span class="required">* required</span>');

		$data->return 			= $this->streams_attribute('return', $this->uri->uri_string());
		
		$error_start 			= $this->streams_attribute('error_start', '<span class="error">');

		$error_end 				= $this->streams_attribute('error_end', '</span>');

		$where 					= $this->streams_attribute('where');

		$include 				= $this->streams_attribute('include');
		
		$exclude 				= $this->streams_attribute('exclude');

		$recaptcha 				= $this->streams_attribute('use_recaptcha', 'no');
		
		$this->streams_attribute('use_recaptcha', 'no') == 'yes' ? $recaptcha = TRUE : $recaptcha = FALSE;
							
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$data->stream			= $this->streams_m->get_stream($stream_slug, TRUE);
		
		// @todo - languagize
		if( !$data->stream ) return 'Invalid Stream';
		
		$data->stream_id		= $data->stream->id;

		// -------------------------------------
		// Get Edit ID from URL if in Edit Mode
		// -------------------------------------
		
		$row = FALSE;
		
		if( $mode == 'edit' ):
		
			// Do they want us to grab the ID from the URL?
			if( is_numeric($edit_segment) ):
			
				$edit_id = $this->uri->segment( $edit_segment );
			
			endif;
			
			// Do they want a where?
			// This overrides the edit_id
			if($where):
				
				$bits = explode('==', $where);
				
				if(count($bits) == 2):
	
					$query = $this->db->limit(1)->where($bits[0], $bits[1])->get(STR_PRE.$data->stream->stream_slug);
					
					if($query->num_rows() == 1):
					
						$row = $query->row();
						
						$edit_id = $row->id;
					
					endif;
	
				endif;
					
			endif;
			
			// Get the row
			$row = $this->row_m->get_row( $edit_id, $data->stream, FALSE );
		
		endif;

		// -------------------------------------
		// Include/Exclude
		// -------------------------------------

		$skips = array();

		if($include):

			$includes = explode('|', $include);

			// We need to skip everything else
			$raw_fields = $this->streams_m->get_stream_fields($data->stream_id);

			foreach($raw_fields as $field):

				if(!in_array($field->field_slug, $includes)):

					$skips[] = $field->field_slug;

				endif;

			endforeach;

		elseif($exclude):

			// Exlcudes are just our skips
			$skips = explode('|', $exclude);

		endif;

		// -------------------------------------
		// Process and Output Form Data
		// -------------------------------------
	
		$output = $this->fields->build_form($data, $mode, $row, TRUE, $recaptcha, $skips);
		
		$fields = array();
		
		$count = 0;
		
		foreach( $output->stream_fields as $slug => $field ):
		
			if(!in_array($field->field_slug, $skips)):
		
				$fields[$count]['input_title'] 		= $field->field_name;
				$fields[$count]['input_slug'] 		= $field->field_slug;
				$fields[$count]['instructions'] 	= $field->instructions;
	
				if( $mode == 'edit' ):
				
					$fields[$count]['input'] 			= $this->fields->build_form_input($field, $output->values[$field->field_slug], $row->id);
				
				else:
	
					$fields[$count]['input'] 			= $this->fields->build_form_input($field, $output->values[$field->field_slug]);			
				
				endif;
	
	
				$fields[$count]['error']			= $this->streams_validation->error($field->field_slug, $error_start, $error_end);
				
				if( $fields[$count]['error'] ):
				
					$fields[$count]['error']		= $error_start . $fields[$count]['error'] . $error_end;
				
				endif;
				
				if( $field->is_required == 'yes' ):
				
					$fields[$count]['required'] 	= $required;
					
				else:
				
					$fields[$count]['required'] 	= '';
				
				endif;
	
				if( ($count+1)%2 == 0 ):
				
					$fields[$count]['odd_even'] 		= 'even';
					
				else:
				
					$fields[$count]['odd_even'] 		= 'odd';
				
				endif;
				
				$count++;
			
			endif;		
		
		endforeach;

		// -------------------------------------
		// reCAPTCHA
		// -------------------------------------
		
		if($recaptcha):
		
			$this->recaptcha->_rConfig['theme'] = $this->streams_attribute('recaptcha_theme', 'red');

			$vars['recaptcha'] = $this->recaptcha->get_html();

			// Output the error if we have one
			if (isset($this->streams_validation->_field_data['recaptcha_response_field']['error'])
				  and $this->streams_validation->_field_data['recaptcha_response_field']['error'] != ''):
	
				$vars['recaptcha_error'] = $this->streams_validation->error('recaptcha_response_field');
				
			else:
			
				$vars['recaptcha_error'] = '';
	
			endif;
		
		endif;

		// -------------------------------------
		// Return the Fields
		// -------------------------------------
		
		$vars['fields'] = $fields;
		
		// -------------------------------------
		// Form elements
		// -------------------------------------
		
		$params['class']		= $this->streams_attribute('form_class', 'crud_form');
		
		$hidden = array();
		if($mode == 'edit'):
		
			$hidden = array('row_edit_id' => $row->id);
		
		endif;
		
		$vars['form_open']		= form_open_multipart($this->uri->uri_string(), $params, $hidden);				
		$vars['form_close']		= '</form>';
		$vars['form_submit']	= '<input type="submit" value="Submit" />';
		$vars['form_reset']		= '<input type="reset" value="Reset" />';

		// -------------------------------------
		
		return array($vars);				
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Form assets
	 *
	 * @access	public
	 * @return	string
	 */	
	public function form_assets()
	{
		if(!empty($this->type->assets)):
			
			// Weird fix that seems to work for fixing WYSIWYG
			// since it is throwing missing variable errors
			$html = '<script type="text/javascript">var SITE_URL = "'.$this->config->item('base_url').'";</script>';
		
			foreach($this->type->assets as $asset):
			
				$html .= $asset."\n";
			
			endforeach;
			
			return $html;
		
		endif;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete a row field
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function delete_entry()
	{
		// -------------------------------------
		// General Loads
		// -------------------------------------

		$this->load->library(array('form_validation', 'streams/Streams_validation', 'streams/Fields'));

		// -------------------------------------
		// Get vars
		// -------------------------------------

		$stream_slug 			= $this->streams_attribute('stream');

		$entry_id 				= $this->streams_attribute('entry_id', FALSE);

		$return 				= $this->streams_attribute('return', '');
		
		$vars					= array();

		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$stream			= $this->streams_m->get_stream($stream_slug, TRUE);
		
		if(!$stream) show_error("\"$stream_slug\" is not a valid stream");
	
		// -------------------------------------
		// Check Delete
		// -------------------------------------
	
		if($this->input->post('delete_confirm') == 'Delete'):
		
			$this->db->where('id', $entry_id)->delete(STR_PRE.$stream->stream_slug);
			
			$this->load->helper('url');
			
			redirect(str_replace('-id-', $entry_id, $return));
			
		else:

			// -------------------------------------
			// Get stream fields
			// -------------------------------------
			
			$this->fields = $this->streams_m->get_stream_fields($stream->id);

			// -------------------------------------
			// Get entry data
			// -------------------------------------
			// We may want to display it 
			// -------------------------------------
			
			$params = array(
				'stream'		=> $stream->stream_slug,
				'id' 			=> $entry_id,
				'limit'			=> 1,
				'offset'		=> 0,
				'order_by'		=> FALSE,
				'exclude'		=> FALSE,
				'show_upcoming'	=> NULL,
				'show_past'		=> NULL,
				'where'			=> NULL,
				'disable'		=> array(),
				'year'			=> NULL,
				'month'			=> NULL,
				'day'			=> NULL,
				'restrict_user'	=> 'no',
				'single'		=> 'yes'
			);

			$this->rows = $this->row_m->get_rows($params, $this->fields, $stream);
			
			// @todo - languagize this
			if(!isset($this->rows['rows'][0])) return $this->streams_attribute('no_entry', 'Unable to find entry');
			
			$vars['entry'][0] = $this->rows['rows'][0];
	
			// -------------------------------------
			// Parse other vars
			// -------------------------------------

			$vars['form_open'] 		= form_open($this->uri->uri_string());
			
			$vars['form_close']		= '</form>';
			
			$vars['delete_confirm']	= '<input type="submit" name="delete_confirm" value="Delete" />';
			
			$this->rows = null;
			
			return array($vars);
		
		endif;	
	}

	// --------------------------------------------------------------------------

	/**
	 * Default Calendar Template
	 *
	 * @access	public
	 * @var		string
	 */
	public $calendar_template = '
	
	   {table_open}<table border="0" cellpadding="0" cellspacing="0">{/table_open}
	
	   {heading_row_start}<tr>{/heading_row_start}
	
	   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
	   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
	   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
	
	   {heading_row_end}</tr>{/heading_row_end}
	
	   {week_row_start}<tr>{/week_row_start}
	   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
	   {week_row_end}</tr>{/week_row_end}
	
	   {cal_row_start}<tr>{/cal_row_start}
	   {cal_cell_start}<td>{/cal_cell_start}
	
	   {cal_cell_content}{day}{content}{/cal_cell_content}
	   {cal_cell_content_today}<div class="highlight">{day}{content}</div>{/cal_cell_content_today}
	
	   {cal_cell_no_content}{day}{/cal_cell_no_content}
	   {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}
	
	   {cal_cell_blank}&nbsp;{/cal_cell_blank}
	
	   {cal_cell_end}</td>{/cal_cell_end}
	   {cal_row_end}</tr>{/cal_row_end}
	
	   {table_close}</table>{/table_close}
	';

	// --------------------------------------------------------------------------

	/**
	 * Calendar
	 *
	 * @access	public
	 * @return	string
	 */
	public function calendar()
	{
		// -------------------------------------
		// Get vars
		// -------------------------------------
	
		$passed_streams 		= $this->streams_attribute('stream');

		$date_fields_passed		= $this->streams_attribute('date_field', 'created');

		$year 					= $this->streams_attribute('year', date('Y'));

		$year_segment 			= $this->streams_attribute('year_segment');

		$month 					= $this->streams_attribute('month', date('n'));

		$month_segment 			= $this->streams_attribute('month_segment');

		$passed_display 		= $this->streams_attribute('display', '<strong>[id]</strong>');

		$passed_link 			= $this->streams_attribute('link', '');
		
		$title_col				= $this->streams_attribute('title_col', 'id');

		$template				= $this->streams_attribute('template', FALSE);

		// -------------------------------------
		// Figure out year & month
		// -------------------------------------

		if( is_numeric($year_segment) and is_numeric($this->uri->segment($year_segment)) ):
		
			$year = $this->uri->segment($year_segment);
		
		endif;

		if( is_numeric($month_segment) and is_numeric($this->uri->segment($month_segment)) ):
		
			$month = $this->uri->segment($month_segment);
		
		endif;

		// Default to current
		if(!is_numeric($year)) $year = date('Y');
		if(!is_numeric($month)) $month = date('n');

		// -------------------------------------
		// Run through streams & create
		// calendar data
		// -------------------------------------
		
		$calendar = array();
		
		$displays		= explode("|", $passed_display);

		$links			= explode("|", $passed_link);
		
		$streams 		= explode("|", $passed_streams);

		$date_fields 	= explode("|", $date_fields_passed);
		
		$count = 0;
				
		foreach( $streams as $stream_slug ):

			$date_field = $date_fields[$count];

			$stream = $this->streams_m->get_stream($stream_slug, true);
	
			$this->fields = $this->streams_m->get_stream_fields($stream->id);
			
			$params = array(
				'date_by' => $date_field,
				'get_day' => true,
				'year' => $year,
				'month' => $month
			);

			$this->rows = $this->row_m->get_rows($params, $this->fields, $stream);
				
			// -------------------------------------
			// Format Calendar Data
			// -------------------------------------
			
			foreach( $this->rows as $above ):
			
				foreach($above as $entry):
				
				// Replace fields				
				$display_content 	= $displays[$count];
				$link_content 		= $links[$count];
				
				foreach( $entry as $key => $val ):
				
					$display_content 	= str_replace('['.$key.']', $val, $display_content);
					$link_content 		= str_replace('['.$key.']', $val, $link_content);
				
				endforeach;
				
				// Link
				if( $link_content != '' ):
				
					$display_content = '<a href="'.site_url($link_content).'" class="'.$stream_slug.'_link">'.$display_content.'</a>';
				
				endif;
				
				// Adding to the array
				if( isset($calendar[$entry['pyrostreams_cal_day']]) ):
				
					$calendar[$entry['pyrostreams_cal_day']] .= $display_content.'<br />';
				
				else:
	
					$calendar[$entry['pyrostreams_cal_day']]  = $display_content.'<br />';
				
				endif;
				
				endforeach;
			
			endforeach;
					
			$count++;
	
		endforeach;
				
		// -------------------------------------
		// Get Template
		// -------------------------------------

		if( $template ):
		
			$this->db->limit(1)->select('body')->where('title', $template);
			$db_obj = $this->db->get('page_layouts');
			
			if( $db_obj->num_rows() > 0 ):
			
				$layout = $db_obj->row();
				
				$this->calendar_template = $layout->body;
			
			endif;
						
		endif;
	
		// -------------------------------------
		// Generate Calendar
		// -------------------------------------
		
		$calendar_prefs['template']			= $this->calendar_template;
		$calendar_prefs['start_day']		= strtolower($this->streams_attribute('start_day', 'sunday'));
		$calendar_prefs['month_type']		= $this->streams_attribute('month_type', 'long');
		$calendar_prefs['day_type']			= $this->streams_attribute('day_type', 'abr');
		$calendar_prefs['show_next_prev']	= $this->streams_attribute('show_next_prev', 'yes');
		$calendar_prefs['next_prev_url']	= $this->streams_attribute('next_prev_url', '');

		if( $calendar_prefs['show_next_prev'] == 'yes' ):
		
			$calendar_prefs['show_next_prev'] = TRUE;
		
		else:
		
			$calendar_prefs['show_next_prev'] = FALSE;
		
		endif;

		$this->load->library('calendar', $calendar_prefs);

		return $this->calendar->generate($year, $month, $calendar);
	}

	// --------------------------------------------------------------------------

	/**
	 * Seach Form
	 *
	 * @access	public
	 * @return	string
	 */
	function search_form()
	{
		$this->load->helper('form');
	
		$stream_slug 	= $this->streams_attribute('stream');

		$fields 		= $this->streams_attribute('fields');
		
		$search_types 	= array('keywords', 'full_phrase');
		
		$search_type 	= strtolower($this->streams_attribute('search_type', 'full_phrase'));
		
		$results_page	= $this->streams_attribute('results_page');

		$variables		= array();

		// -------------------------------------
		// Check our search type
		// -------------------------------------
		
		if(!in_array($search_type, $search_types)):
		
			show_error("\"$search_type\" is not a valid search type");
		
		endif;

		// -------------------------------------
		// Check for our search term
		// -------------------------------------
		
		if(isset($_POST['search_term'])):
		
			$this->load->model('streams/search_m');
			
			// Write cache
			$cache_id = $this->search_m->perform_search($this->input->post('search_term'), $search_type, $stream_slug, $fields);
		
			// Redirect
			$this->load->helper('url');
			redirect($results_page.'/'.$cache_id);
		
		endif;
		
		// -------------------------------------
		// Build Form
		// -------------------------------------

		$vars['form_open']			= form_open($this->uri->uri_string());

		$search_input = array(
		              'name'        => 'search_term',
		              'id'          => 'search_term');
		
		$vars['search_input'] 		= form_input($search_input);

		$vars['form_submit'] 		= form_submit('search_submit', 'Search');

		$vars['form_close'] 		= '</form>';

		return array($vars);
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Search Results
	 *
	 * @access	public
	 * @return	string
	 */
	function search_results()
	{
		$paginate		= $this->streams_attribute('paginate', 'no');
		
		$cache_segment	= $this->streams_attribute('cache_segment', 3);
		
		// Pagination segment is always right after the cache hash segment
		$pag_segment	= $cache_segment+1;

		$per_page		= $this->streams_attribute('per_page', 25);
		
		$variables		= array();

		// -------------------------------------
		// Check for Cached Search Query
		// -------------------------------------

		$this->load->model('streams/search_m');

		if(! $cache = $this->search_m->get_cache($this->uri->segment($cache_segment))):
		
			// Invalid search
			show_error("Search not found.");
		
		endif;

		$this->fields = $this->streams_m->get_stream_fields(
				$this->streams_m->get_stream_id_from_slug($cache->stream_slug));
	
		// Easy out for no results
		if($cache->total_results == 0):
		
			$vars = array(
				'no_results' 		=> $this->streams_attribute('no_results', "No results."),
				'results_exist'		=> FALSE,
				'results'			=> array(),
				'pagination'		=> '',
				'search_term' 		=> $this->streams_attribute('search_term', $cache->search_term),
				'total_results'		=> (string)'0'
			);		
			
			return array($vars);
	
		endif;
		
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		if($paginate == 'yes'):
		
			$this->load->library('pagination');

			// Find Pagination base_url
			$segments = $this->uri->segment_array();
			
			if( is_numeric($segments[count($segments)]) ):
			
				unset($segments[count($segments)]);
			
			endif;
			
			$pag_uri = '';
			
			foreach($segments as $segment):
			
				$pag_uri .= $segment . '/';
			
			endforeach;
			
			$this->EE->load->helper('url');
			
			$pagination_config['base_url'] 			= site_url($pag_uri);
			$pagination_config['total_rows'] 		= $cache->total_results;
			$pagination_config['per_page'] 			= $per_page;
			$pagination_config['uri_segment'] 		= $pag_segment;
			
			// Set pagination configs
			foreach($this->pagination_vars as $var => $default):

				$pagination_config[$var] 		= $this->streams_attribute($var, $default);
			
			endforeach;
			
			// Set the offset
			if($this->uri->segment($per_page) == ''):
			
				$offset = 0;
	
			else:
			
				$offset = $this->uri->segment($pag_segment);		
			
			endif;
			
			$this->pagination->initialize($pagination_config);
			
			$vars['pagination'] = $this->pagination->create_links();
	
			$search_query = $this->db->query($cache->query_string." LIMIT $offset, $per_page");

		else:
		
			// No pagination? Just run the query.		
			$search_query = $this->db->query($cache->query_string);

			$vars['pagination'] = '';
	
		endif;
		
		$this->rows['rows'] = $search_query->result_array();
		
		$vars['results'] = $this->rows['rows'];

		// -------------------------------------
		// Format and return
		// -------------------------------------

		$vars['no_results'] = '';

		$vars['total_results'] = $cache->total_results;

		$vars['results_exist'] = TRUE;
				
		$vars['results'] = array();

		$vars['search_term'] = $cache->search_term;
				
		// Done w/ rows
		$this->rows = null;
		
		// -------------------------------------
		// Return Data
		// -------------------------------------
				
		return array($vars);
	}

	// --------------------------------------------------------------------------

	/**
	 * Show dates for a certain stream
	 *
	 * @access	public
	 * @return 	array
	 */
	public function months()
	{
		$this->load->helper('date');
	
		$stream		= $this->streams_attribute('stream');

		$date_by	= $this->streams_attribute('date_by', 'created');

		if(!$stream) return null;
		
		$obj = $this->db->query("SELECT DISTINCT MONTH({$date_by}) as month_number, YEAR({$date_by}) as year, {$date_by} as full_date FROM ".PYROSTREAMS_DB_PRE.STR_PRE.$stream);
	
		if($obj->num_rows() == 0) return null;
		
		$dates = $obj->result_array();

		// Go through and grab some extra data
		foreach($dates as $key => $data):
		
			$dates[$key]['month'] 		= date('F', mysql_to_unix($data['full_date']));
			$dates[$key]['month_slug'] 	= strtolower($dates[$key]['month']);
		
		endforeach;
		
		return $dates;
	}

}

/* End of file plugin.php */