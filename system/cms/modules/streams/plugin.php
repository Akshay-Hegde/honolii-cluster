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

	/**
	 * Available entry parameters
	 * and their defaults.
	 *
	 * @access	public
	 * @var		array
	 */
	public $entries_params = array(
			'stream'			=> null,
			'limit'				=> null,
			'offset'			=> 0,
			'single'			=> 'no',
			'id'				=> null,
			'date_by'			=> 'created',
			'year'				=> null,
			'month'				=> null,
			'day'				=> null,
			'show_upcoming'		=> 'yes',
			'show_past'			=> 'yes',
			'restrict_user'		=> 'no',
			'where'				=> null,
			'exclude'			=> null,
			'exclude_by'		=> 'id',
			'disable'			=> null,
			'order_by'			=> null,
			'sort'				=> 'asc',
			'exclude_called'	=> 'no',
			'paginate'			=> 'no',
			'pag_segment'		=> 2
	);

	// --------------------------------------------------------------------------

	/**
	 * Pagination confgi
	 *
	 * These are the CI defaults that can be
	 * overridden by PyroStreams
	 *
	 * @access	private
	 * @var		array
	 */
	public $pagination_config = array(
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

	// --------------------------------------------------------------------------
	
	/**
	 * Field Types
	 *
	 * @access	public
	 * @var		obj
	 */
	public $types;

	// --------------------------------------------------------------------------
	
	/**
	 * Rows - in a class var
	 * so it can be shared across some
	 * different functions
	 *
	 * @access	public
	 * @var		obj
	 */
	public $rows;

	// --------------------------------------------------------------------------
	
	/**
	 * PyroStreams Plugin Construct
	 *
	 * Just a bunch of loads and prep
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{	
		$this->load->language('streams/pyrostreams');

		$this->load->config('streams/streams');

		$this->load->helper('streams/streams');

        streams_constants();
        
		$this->load->library('streams/Type');
	
		$this->load->model(array('streams/row_m', 'streams/streams_m', 'streams/fields_m'));
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
		$this->debug_status		 	= $this->streams_attribute('debug', 'off');

		// -------------------------------------
		// Get Plugin Attributes
		// -------------------------------------
		
		$params = array();
		
		foreach($this->entries_params as $param_key => $param_default):
		
			$params[$param_key] = $this->streams_attribute($param_key, $param_default);
		
		endforeach;

		// -------------------------------------
		// Pagination Attributes & Limit
		// -------------------------------------
		
		$pagination = array();
		
		foreach($this->pagination_config as $pag_key => $pag_value):
		
			$pagination[$pag_key] = $this->attribute($pag_key, $pag_value);
		
		endforeach;

		if($params['paginate'] == 'yes' and !$params['limit']) $params['limit'] = 25;

		// -------------------------------------
		// Stream Data Check
		// -------------------------------------
		
		if(!isset($params['stream'])) $this->_error_out(lang('streams.no_stream_provided'));
				
		$stream = $this->streams_m->get_stream($params['stream'], true);
				
		if(!$stream) $this->_error_out(lang('streams.invalid_stream'));
				
		// -------------------------------------
		// Get Stream Fields
		// -------------------------------------
				
		$this->fields = $this->streams_m->get_stream_fields($stream->id);

		// -------------------------------------
		// Get Rows
		// -------------------------------------
		
		$rows = $this->row_m->get_rows($params, $this->fields, $stream);
		
		$return['entries'] = $rows['rows'];
				
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		if($params['paginate'] == 'yes'):
		
			$return['total'] 	= $rows['pag_count'];
			
			// Add in our pagination config
			// override varaibles.
			foreach($this->pagination_config as $key => $var):
			
				$this->pagination_config[$key] = $this->attribute($key, $this->pagination_config[$key]);
				
				// Make sure we obey the FALSE params
				if($this->pagination_config[$key] == 'FALSE') $this->pagination_config[$key] = FALSE;
			
			endforeach;
			
			$return['pagination'] = $this->row_m->build_pagination($params['pag_segment'], $params['limit'], $return['total'], $this->pagination_config);
					
		else:
			
			$return['pagination'] 	= null;
			$return['total'] 		= count($return['entries']);
		
		endif;
				
		// -------------------------------------
		// No Results
		// -------------------------------------
		
		if($return['total'] == 0) return $this->streams_attribute('no_results', "No results");
		
		// -------------------------------------
		// Content Manipulation
		// -------------------------------------
		
		print_r($return);

		return $this->streams_content_parse($this->content(), $return, $params['stream']);
	}

	// --------------------------------------------------------------------------

	/**
	 * Legacy
	 */
	public function related() { return $this->multiple(); }

	// --------------------------------------------------------------------------

	/**
	 * Multiple Related Entries
	 *
	 * This works with the multiple relationship field
	 *
	 * @access	public
	 * @return	array
	 */
	function multiple()
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
			'stream'		=> $join_stream->stream_slug,
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
	private function _error_out($msg)
	{
		if( $this->debug_status == 'on' ):
		
			show_error($msg);
		
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
	 	$date_formats = array('DATE_ATOM', 'DATE_COOKIE', 'DATE_ISO8601', 'DATE_RFC822', 'DATE_RFC850', 'DATE_RFC1036', 'DATE_RFC1123', 'DATE_RFC2822', 'DATE_RSS', 'DATE_W3C');
	 	
		$date = $this->attribute('date');
		$format = $this->attribute('format');
		
		// No sense in trying to get down
		// with somedata that isn't there
		if(!$date or !$format) return null;
		
		$this->load->helper('date');
	
		// Make sure we have a UNIX date
		if(!is_numeric($date)) $date = mysql_to_unix($date);
		
		// Is this a preset?
		if(in_array($format, $date_formats)) return standard_date($format, $date);

		// Default is PHP date		
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
		
		if( !$params['stream'] ): return $this->_error_out(lang('streams.invalid_stream')); endif;
		
		$stream = $this->streams_m->get_stream($params['stream'], TRUE);
		
		if( $stream === FALSE ) return $this->_error_out(lang('streams.invalid_stream'));
		
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
		
		if(!$this->rows) return $this->streams_attribute('no_results', lang('streams.no_results'));

		return $this->streams_content_parse($this->content(), $this->rows['rows'][0], $params['stream'], true);
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
		
		if(!$data->stream) return lang('streams.invalid_stream');
		
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
		$vars['form_submit']	= '<input type="submit" value="'.lang('save_label').'" />';
		$vars['form_reset']		= '<input type="reset" value="'.lang('streams.reset').'" />';

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
		
		if(!$stream) show_error(lang('streams.invalid_stream'));
	
		// -------------------------------------
		// Check Delete
		// -------------------------------------
	
		if($this->input->post('delete_confirm')):
		
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
			
			if(!isset($this->rows['rows'][0])) return $this->streams_attribute('no_entry', lang('streams.no_entry'));
			
			$vars['entry'][0] = $this->rows['rows'][0];
	
			// -------------------------------------
			// Parse other vars
			// -------------------------------------

			$vars['form_open'] 		= form_open($this->uri->uri_string());
			
			$vars['form_close']		= '</form>';
			
			$vars['delete_confirm']	= '<input type="submit" name="delete_confirm" value="'.lang('streams.delete').'" />';
			
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
				
					if(is_string($val)):
				
						$display_content 	= str_replace('['.$key.']', $val, $display_content);
						$link_content 		= str_replace('['.$key.']', $val, $link_content);
					
					endif;
				
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
		
			show_error($search_type.' '.lang('streams.invalid_search_type'));
		
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

		$vars['form_submit'] 		= form_submit('search_submit', lang('streams.search'));

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
			show_error(lang('streams.search_not_found'));
		
		endif;

		$this->fields = $this->streams_m->get_stream_fields(
				$this->streams_m->get_stream_id_from_slug($cache->stream_slug));
				
	
		// Easy out for no results
		if($cache->total_results == 0):
		
			return array(
				'no_results' 		=> $this->streams_attribute('no_results', lang('streams.no_results')),
				'results_exist'		=> false,
				'results'			=> array(),
				'pagination'		=> null,
				'search_term' 		=> $this->streams_attribute('search_term', $cache->search_term),
				'total_results'		=> (string)'0'
			);		
	
		endif;
		
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		$return = array();
	
		$return['total'] 	= $cache->total_results;

		if($paginate == 'yes'):
					
			// Add in our pagination config
			// override varaibles.
			foreach($this->pagination_config as $key => $var):
			
				$this->pagination_config[$key] = $this->attribute($key, $this->pagination_config[$key]);
				
				// Make sure we obey the FALSE params
				if($this->pagination_config[$key] == 'FALSE') $this->pagination_config[$key] = FALSE;
			
			endforeach;

			$return['pagination'] = $this->row_m->build_pagination($pag_segment, $per_page, $return['total'], $this->pagination_config);
			
			$query_string = $cache->query_string." LIMIT $offset, $per_page";
					
		else:
			
			$return['pagination'] 	= null;
			
			$query_string = $cache->query_string;
		
		endif;

		// -------------------------------------
		// Get & Format Results
		// -------------------------------------

		$return['results'] = $this->row_m->format_rows(
									$this->db->query($query_string)->result_array(),
									$this->streams_m->get_stream($cache->stream_slug, true));

		// -------------------------------------
		// Extra Data
		// -------------------------------------

		$return['no_results'] 		= '';
		$return['total_results'] 	= $cache->total_results;
		$return['results_exist'] 	= true;				
		$return['search_term'] 		= $cache->search_term;
		
		return $this->streams_content_parse($this->content(), $return, $cache->stream_slug);
	}

	// --------------------------------------------------------------------------

	/**
	 * Streams content parse
	 *
	 * Special content parser for PyroStreams plugin
	 *
	 * @access	private
	 * @param	string - the tag content
	 * @param	array - the return data
	 * @return 	string - the parsed data
	 */
	private function streams_content_parse($content, $data, $stream_slug, $pre_parse = false)
	{
		// -------------------------------------
		// Multiple Provision
		// -------------------------------------
		// Automatically add in multiple streams data.
		// This makes it easier to call the multiple function
		// from within the streams tags
		// -------------------------------------
		
		$rep = array('{{ streams:related', '{{streams:related');
		$content = str_replace($rep, '{{ streams:related stream="'.$stream_slug.'" entry="{{ id }}" ', $content);

		$rep = array('{{ streams:multiple', '{{streams:multiple');
		$content = str_replace($rep, '{{ streams:multiple stream="'.$stream_slug.'" entry="{{ id }}" ', $content);

		// -------------------------------------
		// Parse Rows
		// -------------------------------------

		$parser = new Lex_Parser();
		$parser->scope_glue(':');
	
		// For some reason, on certain functions,
		// we need to pre-parse the conditionals (as of
		// Lex of PyroCMS 2.0.0 RC1
		if($pre_parse):
		
			$content = $parser->parse_conditionals($content, $data, array($this->parser, 'parser_callback'));
		
		endif;
		
		return $parser->parse_variables($content, $data);
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