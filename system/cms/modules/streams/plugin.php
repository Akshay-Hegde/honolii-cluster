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
        
		$this->load->library('streams/Raw_parser');
		
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

		$params['stream_segment'] 	= $this->streams_attribute('stream_segment');
		
		$params['limit'] 			= $this->streams_attribute('limit');
		
		$params['offset'] 			= $this->streams_attribute('offset', 0);

		$params['single'] 			= $this->streams_attribute('single', 'no');
		
		$params['url_id'] 			= $this->streams_attribute('url_id', 3);

		$params['id'] 				= $this->streams_attribute('id', $this->uri->segment($params['url_id']));
	
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

		// When do we parse tags? early, late, both.
		$this->parse_tags 			= $this->streams_attribute('parse_tags', 'late');

		$no_results 				= $this->streams_attribute('no_results', 'No results');

		// -------------------------------------
		// Get stream
		// -------------------------------------
		
		if( is_numeric($params['stream_segment']) ):
		
			$params['stream'] = $this->uri->segment($params['stream_segment']);
		
		endif;
		
		// Easy out for blank stream
		if( !$params['stream'] ): return $this->_error_out('Invalid Stream'); endif;
		
		$obj = $this->db->limit(1)->where('stream_slug', $params['stream'])->get(STREAMS_TABLE);
		
		if($obj->num_rows() == 0) return $this->_error_out('Invalid Stream');
		
		$stream = $obj->row();

		// -------------------------------------
		// Pagination vars
		// -------------------------------------

		$params['paginate'] 		= $this->streams_attribute('paginate', 'no');

		$params['pag_segment'] 		= $this->streams_attribute('pag_segment', 2);

		// @legacy
		$params['instance_id'] 		= $this->streams_attribute('instance_id', 'default');
		
		// Set a default per_page
		if($params['paginate'] == 'yes' && $params['limit'] != '')
			$params['limit'] = $this->streams_attribute('limit', 25);
				
		// -------------------------------------
		// Get stream fields
		// -------------------------------------
		
		$this->fields = $this->streams_m->get_stream_fields($stream->id);

		// Get the rows
		$this->rows = $this->row_m->get_rows($params, $this->fields, $stream);
		
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		$vars['pagination'] 	= '';
		
		if( $params['paginate'] == 'yes' ):

			$vars['total'] 			= $this->rows['pag_count'];
		
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
			$pagination_config['total_rows'] 		= $vars['total'];
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
			
			$vars['pagination'] = $this->pagination->create_links();
			
			// @legacy
			define( "PAGINATION_LINKS_".$params['instance_id'], $vars['pagination']);
		
		else:
		
			$vars['total'] 			= count($this->rows['rows']);
		
		// End if paginate == 'yes'
		endif;
				
		// -------------------------------------
		// Row Processing Start
		// -------------------------------------

		if(count($this->rows['rows']) == 0) return $no_results;
		
		// We are going to be mackin' on the content
		$content = $this->content();
		
		// Simpletags will be handlin' this
		$this->load->library('streams/Simpletags');
		
		// -------------------------------------
		// Parse 'entries'
		// -------------------------------------
		
		$this->simpletags->set_trigger('entries');
		
		$parsed = $this->simpletags->parse($content, array(), array($this, 'entries_parse'));
	
		$return = $parsed['content'];		

		// -------------------------------------
		// Parse other vars
		// -------------------------------------
		
		foreach($vars as $k => $v):
		
			$return = str_replace('{'.$k.'}', $v, $return);
		
		endforeach;
		
		// Done w/ rows
		$this->rows = null;
		
		// -------------------------------------
		// Return Data
		// -------------------------------------
				
		return $return;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Parse Entries
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	public function entries_parse($tag_data)
	{
		$html = '';
		
		// Prase the rows
		foreach($this->rows['rows'] as $key => $row):
								
			$tmp_html['content'] = $tag_data['content'];
			
			// Early pyro tag parsing
			if($this->parse_tags == 'early' or $this->parse_tags == 'both' and strpos($prsd, '{'.config_item('tags_trigger').':') !== false):
			
				$this->simpletags->set_trigger(config_item('tags_trigger').':');
				$early_output = $this->tags->parse($tmp_html['content'], $this->load->_ci_cached_vars, array($this->parser, 'parser_callback'));
				$tmp_html['content'] = $early_output['content'];
				
			endif;
			
			// Parse created/updated
			$regular_dates = array('created', 'updated');
			
			foreach($regular_dates as $reg_data):
			
				$this->simpletags->set_trigger($reg_data);
				$tmp_html = $this->simpletags->parse($tmp_html['content'], array(), array($this, 'date_parse'), array('input'=>$row[$reg_data]));
			
			endforeach;

			// Run the alt processes
			foreach($this->fields as $field_slug => $field ):
			
				// Is this an alt process?
				if(method_exists($this->type->types->{$field->field_type}, 'alt_process_plugin')):
				 
					$this->simpletags->set_trigger($field_slug);
					
					// Give some extra data					
					$extra = array(
						'field'			=> $field,
						'row'			=> $row
					);
					
					if(isset($row[$field->field_slug])) $extra['input_value'] = $row[$field->field_slug];
					
					$tmp_html = $this->simpletags->parse($tmp_html['content'], array(), array($this->type->types->{$field->field_type}, 'alt_process_plugin'), $extra);
	
				endif;

			endforeach;
			
			// Parse the normal items
			$prsd = $this->raw_parser->parse_string($tmp_html['content'], $row, TRUE);

			// Manually run PHP parsing for the row
			$prsd = $this->simpletags->parse_php($this->simpletags->parse_conditionals($prsd), $row);
			
			// Late pyro tag parsing.
			if($this->parse_tags == 'late' or $this->parse_tags == 'both' and strpos($prsd, '{'.config_item('tags_trigger').':') !== false):
			
				$this->simpletags->set_trigger(config_item('tags_trigger').':');
				
				$late_output = $this->tags->parse($prsd, $this->load->_ci_cached_vars, array($this->parser, 'parser_callback'));
				$prsd = $late_output['content'];
				
			endif;
			
			$html .= $prsd;
									
		endforeach;
		
		return $html;
	}

	// --------------------------------------------------------------------------

	/**
	 * Parse a date for created/updated
	 *
	 * @access	public
	 * @param	array
	 * @return 	string
	 */	
	public function date_parse($tag_data)
	{	
		$input = $tag_data['input'];

		// Get the format string
		if(isset($tag_data['attributes']['format'])):
			
			// Is this a preset format?
			if(in_array($tag_data['attributes']['format'], $this->type->types->datetime->date_formats)):
			
				return standard_date($tag_data['attributes']['format'], $input);
			
			else:
			
				return date($tag_data['attributes']['format'], $input);
			
			endif;
		
		endif;
		
		// If there is no formatting, just return the
		// original value
		return $input;
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
	 * Join multiple
	 */
	function join_multiple($data)
	{
		$this->db->from(STR_PRE.$data['join_stream']->stream_slug);
		$this->db->join(	
			$data['join_table'],
			$data['join_table'].'.'.$data['join_stream']->stream_slug.'_id = '.STR_PRE.$data['join_stream']->stream_slug.".id",
			'LEFT' );
		$this->db->where($data['join_table'].'.row_id', $data['row_id']);
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
	 * Pagination
	 *
	 * Return pagination links. This is a legacy function.
	 *
	 * @legacy
	 *
	 * @access	public
	 * @param	string
	 */
	public function pagination()
	{
		$var = "PAGINATION_LINKS_".$this->streams_attribute('instance_id', 'default');
		
		if( defined($var) ):
		
			return constant($var);
		
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
	 * Single
	 *
	 * Show a single item
	 */
	public function single()
	{	
		$this->load->library('streams/Simpletags');

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

		$no_results 				= $this->streams_attribute('no_results', 'No results');

		// -------------------------------------
		// Get stream
		// -------------------------------------
		
		// Easy out for blank stream
		if( !$params['stream'] ): return $this->_error_out('Invalid Stream'); endif;
		
		$stream = $this->streams_m->get_stream($params['stream'], TRUE);
		
		if( $stream === FALSE ) return $this->_error_out('Invalid Stream');
		
		// -------------------------------------
		// Disable
		// -------------------------------------
		// Allows users to turn off relationships
		// and created_by
		// -------------------------------------
		
		if( $params['disable'] ):

			$params['disable'] = explode("|", $params['disable']);
			
		else:
		
			$params['disable'] = array();
		
		endif;
		
		// -------------------------------------
		// Get stream fields
		// -------------------------------------
		
		$this->fields = $this->streams_m->get_stream_fields($stream->id);
		
		// -------------------------------------
		// Return Rows
		// -------------------------------------
		
		$this->rows = $this->row_m->get_rows($params, $this->fields, $stream);
		
		$content = $this->entries_parse(array('content' => $this->content()));

		// Done w/ rows
		$this->rows = null;
		
		return $content;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Output an input form for a stream
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
		
		// @legacy
		define('ERROR_START', $error_start);
		
		// @legacy
		define('ERROR_END', $error_end);
		
		// -------------------------------------
		// Process Stream Segment
		// -------------------------------------
		
		// @legacy
		if( is_numeric($stream_segment) ) $stream_slug = $this->uri->segment($stream_segment);

		// -------------------------------------
		// Process Return URL
		// @legacy - you can do this via
		// passing values via params now
		// -------------------------------------

		$segs = explode("/", $data->return);
		
		$processed_return = '';
		
		foreach( $segs as $key => $seg ):
		
			$processed_return .= str_replace("*", $this->uri->segment($key+1), $seg).'/';
	
		endforeach;
	
		$data->return = $processed_return;
			
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$data->stream			= $this->streams_m->get_stream($stream_slug, TRUE);
		
		if( !$data->stream ):
		
			return 'Invalid Stream';
			
		endif;
		
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
		
		$content = $this->parser->parse_string($this->content(), '', TRUE);
		
		return $this->raw_parser->parse_string($content, $vars, TRUE);
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
	 * Form Open
	 *
	 * @legacy
	 *
	 * Simple form open that goes to the current page
	 *
	 * @access	public
	 * @return	string
	 */
	public function form_open()
	{
		$this->load->helper( 'form' );
		
		$params['class']		= $this->streams_attribute('class', 'crud_form');
		
		return form_open_multipart( $this->uri->uri_string(), $params );
	}

	// --------------------------------------------------------------------------

	/**
	 * Form Close
	 *
	 * @legacy
	 *
	 * @access	public
	 * @return	string
	 */
	public function form_close()
	{
		return '</form>';
	}

	// --------------------------------------------------------------------------

	/**
	 * reCAPTCHA
	 *
	 * @legacy
	 *
	 * @access	public
	 * @return	string
	 */
	public function recaptcha()
	{
		$this->recaptcha->_rConfig['theme'] = $this->streams_attribute('theme', 'red');

		return $this->recaptcha->get_html();
	}

	// --------------------------------------------------------------------------

	/**
	 * reCAPTCHA Error
	 *
	 * @access	public
	 * @return	string
	 */
	public function recaptcha_error()
	{		
		// Output the error if we have one
		if (isset($this->streams_validation->_field_data['recaptcha_response_field']['error'])
			  and $this->streams_validation->_field_data['recaptcha_response_field']['error'] != ''):

			return $this->streams_validation->error('recaptcha_response_field', ERROR_START, ERROR_END);

		endif;

		return;
	}

	// --------------------------------------------------------------------------

	/**
	 * Form Submit
	 *
	 * @legacy
	 *
	 * @access	public
	 * @return	string
	 */
	public function form_submit()
	{
		$title = $this->streams_attribute('title', 'Submit');
		$class = $this->streams_attribute('class', 'submit_button');

		return '<input type="submit" value="'.$title.'" class="'.$class.'" />';
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

			$this->load->library('streams/Simpletags');

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
			
			// -------------------------------------
			// Parse 'entries'
			// -------------------------------------
			
			$this->simpletags->set_trigger('entry');
			
			$parsed = $this->simpletags->parse($this->content(), array(), array($this, 'entries_parse'));
		
			$return = $parsed['content'];		
	
			// -------------------------------------
			// Parse other vars
			// -------------------------------------

			$vars['form_open'] 		= form_open($this->uri->uri_string());
			
			$vars['form_close']		= '</form>';
			
			$vars['delete_confirm']	= '<input type="submit" name="delete_confirm" value="Delete" />';
			
			foreach($vars as $k => $v):
			
				$return = str_replace('{'.$k.'}', $v, $return);
			
			endforeach;
			
			// Done w/ rows
			$this->rows = null;
			
			return $return;
		
		endif;	
	}

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

		$content = $this->parser->parse_string($this->content(), '', TRUE);
		
		return $this->raw_parser->parse_string($content, $vars, TRUE);
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
				'total_results'		=> 0
			);		

			return $this->raw_parser->parse_string($this->content(), $vars, TRUE);
	
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

		// -------------------------------------
		// Format and return
		// -------------------------------------

		$vars['no_results'] = '';

		$vars['total_results'] = $cache->total_results;

		$vars['results_exist'] = TRUE;
				
		$vars['results'] = array();

		$vars['search_term'] = $cache->search_term;
				
		// -------------------------------------
		// Parse 'results'
		// -------------------------------------

		$this->load->library('streams/Simpletags');
		
		$this->simpletags->set_trigger('results');
		
		$parsed = $this->simpletags->parse($this->content(), array(), array($this, 'entries_parse'));
	
		$return = $parsed['content'];		

		// -------------------------------------
		// Parse other vars
		// -------------------------------------
		
		foreach($vars as $k => $v):
			
			if(is_string($v)) $return = str_replace('{'.$k.'}', $v, $return);
		
		endforeach;
		
		// Done w/ rows
		$this->rows = null;
		
		// -------------------------------------
		// Return Data
		// -------------------------------------
				
		return $return;
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