<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Plugin
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
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
			'include'			=> null,
			'include_by'		=> 'id',
			'disable'			=> null,
			'order_by'			=> null,
			'sort'				=> 'asc',
			'exclude_called'	=> 'no',
			'paginate'			=> 'no',
			'pag_segment'		=> 2,
			'partial'			=> null,
			'site_ref'			=> SITE_REF
	);

	// --------------------------------------------------------------------------

	/**
	 * Pagination config
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
	 * Cache Vars
	 */
	public $cache_type				= 'query';		// tag or query
	public $cache_time_format		= 'minutes'; 	// minutes or seconds
	public $cache					= null;			// num of seconds or minutes
	public $cache_hash				= null;
	public $write_tag_cache			= false;		// Whether or not we need

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
		$this->load->language('streams_core/pyrostreams');

		$this->load->config('streams_core/streams');
 		$this->load->config('streams/streams');
       
		$this->load->library('streams_core/Type');
	
		$this->load->model(array('streams_core/row_m', 'streams_core/streams_m', 'streams_core/fields_m'));
		
		// Set our core namespace.
		$this->core_namespace = $this->config->item('streams:core_namespace');
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
	 * Fun little method to call a stream without
	 * using cycle. Like:
	 *
	 * {{ streams:stream_slug }}
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function __call($name, $data)
	{
		$this->entries_params['stream'] = $name;

		return $this->cycle();
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * List stream entries
	 *
	 * @access	public
	 */
	public function cycle()
	{		
		$this->debug_status		 	= $this->streams_attribute('debug', 'on');

		// -------------------------------------
		// Get Plugin Attributes
		// -------------------------------------
		
		$params = array();
		
		foreach ($this->entries_params as $param_key => $param_default)
		{
			$params[$param_key] = $this->streams_attribute($param_key, $param_default);
		}

		// -------------------------------------
		// Cache
		// -------------------------------------

		$this->setup_cache();

		if ( ! is_null($full_tag_cache = $this->full_tag_cache()))
		{
			return $full_tag_cache;
		}

		// -------------------------------------
		// Pagination Attributes & Limit
		// -------------------------------------
		
		$pagination = array();
		
		foreach ($this->pagination_config as $pag_key => $pag_value)
		{
			$pagination[$pag_key] = $this->attribute($pag_key, $pag_value);
		}

		if ($params['paginate'] == 'yes' and !$params['limit']) $params['limit'] = 25;

		// -------------------------------------
		// Stream Data Check
		// -------------------------------------
		
		if ( ! isset($params['stream'])) $this->_error_out(lang('streams.no_stream_provided'));
				
		$stream = $this->streams_m->get_stream($params['stream'], TRUE, $this->core_namespace);
				
		if ( ! $stream) $this->_error_out(lang('streams.invalid_stream'));
				
		// -------------------------------------
		// Get Stream Fields
		// -------------------------------------
				
		$this->fields = $this->streams_m->get_stream_fields($stream->id);

		// -------------------------------------
		// Get Rows
		// -------------------------------------

		if ($this->cache_type == 'query' and is_numeric($this->cache))
		{
			$rows = $this->pyrocache->model('row_m', 'get_rows', array($params, $this->fields, $stream), $this->cache);
		}
		else
		{
			$rows = $this->row_m->get_rows($params, $this->fields, $stream);
		}
		
		$return['entries'] = $rows['rows'];
				
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		if ($params['paginate'] == 'yes')
		{
			$return['total'] 	= $rows['pag_count'];
			
			// Add in our pagination config
			// override varaibles.
			foreach ($this->pagination_config as $key => $var)
			{
				$this->pagination_config[$key] = $this->attribute($key, $this->pagination_config[$key]);
				
				// Make sure we obey the FALSE params
				if($this->pagination_config[$key] == 'FALSE') $this->pagination_config[$key] = FALSE;
			}
			
			$return['pagination'] = $this->row_m->build_pagination($params['pag_segment'], $params['limit'], $return['total'], $this->pagination_config);
		}	
		else
		{	
			$return['pagination'] 	= NULL;
			$return['total'] 		= count($return['entries']);
		}
				
		// -------------------------------------
		// No Results
		// -------------------------------------
		
		if ($return['total'] == 0) return $this->streams_attribute('no_results', lang('streams.no_results'));

		// -------------------------------------
		// {{ entries }} Bypass
		// -------------------------------------
		// If we don't want to use {{ entries }},
		// we don't have to!
		// -------------------------------------

		$loop = false;

		if (preg_match('/\{\{\s?entries\s?\}\}/', $this->content()) == 0)
		{
			$return = $return['entries'];
			$loop = true;
		}
		
		// -------------------------------------
		// Parse Ouput Content
		// -------------------------------------
		
		$return_content = $this->streams_content_parse($this->content(), $return, $params['stream'], $loop);
	
		// -------------------------------------
		// Cache End Procedures
		// -------------------------------------

		$this->tag_cache_write($return_content);

		$this->clear_cache_vars();

		// -------------------------------------

		return $return_content;
	}

	// --------------------------------------------------------------------------

	/**
	 * Setup the cache vars
	 *
	 * @access 	private
	 * @return 	void
	 */
	private function setup_cache()
	{
		// 'tag' or 'query'
		$this->cache_type				= $this->streams_attribute('cache_type', 'query');	

		// 'minutes' or 'seconds'
		$this->cache_time_format		= $this->streams_attribute('cache_time_format', 'minutes'); 

		// num of seconds or minutes
		$this->cache					= $this->streams_attribute('cache', null);

		// Format the cache time. It can either be in seconds
		// or minutes depending on a param.
		if (is_numeric($this->cache))
		{
			if ($this->cache_time_format == 'minutes')
			{
				// If they specified minutes we just need to
				// convert it to second
				$this->cache = $this->cache*60;
			}
		}

		$this->set_cache_hash();
	}

	// --------------------------------------------------------------------------

	/**
	 * Set the cache hash
	 *
	 * @access 	private
	 * @return 	void
	 */
	private function set_cache_hash()
	{
		$this->cache_hash = md5(implode('-', $this->attributes()).$this->content());
	}

	// --------------------------------------------------------------------------

	/**
	 * Write tag cache if we need to
	 *
	 * @access 	private
	 * @param 	string - the content to write
	 * @return 	void
	 */
	private function tag_cache_write($content)
	{
		if ($this->write_tag_cache === true)
		{
			$this->pyrocache->write($content, 'pyrostreams'.DIRECTORY_SEPARATOR.$this->cache_hash, $this->cache);
		}		
	}

	// --------------------------------------------------------------------------

	/**
	 * Full tag cache
	 *
	 * @access 	private
	 * @return 	mixed - null or string
	 */
	private function full_tag_cache()
	{
		if ( ! $this->cache_hash)
		{
			$this->set_cache_hash();
		}

		// Check to see if we have a tag cache.
		if ($this->cache_type == 'tag' and ! is_null($this->cache))
		{
			if ( ! $tag_cache_content = $this->pyrocache->get('pyrostreams'.DIRECTORY_SEPARATOR.$this->cache_hash))
			{
				// Set this so functions know to write the
				// cache when necesary.
				$this->write_tag_cache = true;
			}
			else
			{
				return $tag_cache_content;
			}
		}

		return null;
	}

	// --------------------------------------------------------------------------

	/**
	 * Reset the cache vars to their defaults
	 *
	 * @access 	private
	 * @return 	void
	 */
	private function clear_cache_vars()
	{
		$this->cache_type			= 'query';
		$this->cache_time_format	= 'minutes';
		$this->cache				= null;
		$this->cache_hash			= null;
		$this->write_tag_cache		= false;
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
		$rel_field 	= $this->attribute('field');
		$entry_id 	= $this->attribute('entry');

		// -------------------------------------
		// Cache
		// -------------------------------------

		$this->setup_cache();

		// -------------------------------------
		
		if ( ! $field = $this->fields_m->get_field_by_slug($rel_field, $this->core_namespace)) return NULL;

		// Get the stream
		$join_stream = $this->streams_m->get_stream($field->field_data['choose_stream']);
		
		// Get the fields		
		$this->fields = $this->streams_m->get_stream_fields($join_stream->id);

		$stream = $this->streams_m->get_stream($this->attribute('stream'), true, $this->attribute('namespace', $this->core_namespace));
		
		// Add the join_multiple hook to the get_rows function
		$this->row_m->get_rows_hook = array($this, 'join_multiple');
		$this->row_m->get_rows_hook_data = array(
			'join_table' => $stream->stream_prefix.$stream->stream_slug.'_'.$join_stream->stream_slug,
			'join_stream' => $join_stream,
			'row_id' =>  $this->attribute('entry')		
		);
		
		$params = array(
			'arbitrary'			=> $entry_id, // For the cache
			'stream'			=> $join_stream->stream_slug,
			'limit'				=> $this->streams_attribute('limit'),
			'offset'			=> $this->streams_attribute('offset', 0),
			'id'				=> $this->streams_attribute('id', NULL),
			'date_by'			=> $this->streams_attribute('date_by', 'created'),
			'exclude'			=> $this->streams_attribute('exclude'),
			'show_upcoming'		=> $this->streams_attribute('show_upcoming', 'yes'),
			'show_past'			=> $this->streams_attribute('show_past', 'yes'),
			'year'				=> $this->streams_attribute('year'),
			'month'				=> $this->streams_attribute('month'),
			'day'				=> $this->streams_attribute('day'),
			'restrict_user'		=> $this->streams_attribute('restrict_user', 'no'),
			'where'				=> $this->streams_attribute('where', NULL),
			'exclude'			=> $this->streams_attribute('exclude', NULL),
			'exclude_by'		=> $this->streams_attribute('exclude_by', 'id'),
			'disable'			=> $this->streams_attribute('disable', NULL),
			'order_by'			=> $this->streams_attribute('order_by'),
			'sort'				=> $this->streams_attribute('sort', 'asc'),
			'exclude_called'	=> $this->streams_attribute('exclude_called', 'no'),
			'paginate'			=> $this->streams_attribute('paginate', 'no'),
			'pag_segment'		=> $this->streams_attribute('pag_segment', 2),
			'partial'			=> $this->streams_attribute('partial', NULL)			
		);

		if ($this->cache_type == 'query' and is_numeric($this->cache))
		{
			$rows = $this->pyrocache->model('row_m', 'get_rows', array($params, $this->fields, $join_stream), $this->cache);
		}
		else
		{
			$rows = $this->row_m->get_rows($params, $this->fields, $join_stream);
		}

		$this->clear_cache_vars();

		return $rows['rows'];
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
		$this->row_m->sql['join'][] = "LEFT JOIN `{$this->db->dbprefix($data['join_table'])}` ON `{$this->db->dbprefix($data['join_table'])}`.`{$data['join_stream']->stream_slug}_id` = `{$this->db->dbprefix($data['join_stream']->stream_prefix.$data['join_stream']->stream_slug)}`.`id`";
		$this->row_m->sql['where'][] = "`{$this->db->dbprefix($data['join_table'])}`.`row_id` = '{$data['row_id']}'";
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
		if ( ! $this->streams_attribute('stream'))
		{
			return null;
		}

		$this->setup_cache();

		$this->load->config('streams/streams');

		if ( ! is_null($this->cache))
		{
			if ( ! $cache_content = $this->pyrocache->get('pyrostreams'.DIRECTORY_SEPARATOR.$this->cache_hash))
			{
				return $this->pyrocache->write(
					$this->db->count_all(STR_PRE.$this->streams_attribute('stream')),
					'pyrostreams'.DIRECTORY_SEPARATOR.$this->cache_hash, $this->cache);

			}
			else
			{
				return $cache_content;
			}
		}
		else
		{
			return $this->db->count_all(STR_PRE.$this->streams_attribute('stream'));
		}
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
	 	
		$date 		= $this->attribute('date');
		$format 	= $this->attribute('format');
		
		// No sense in trying to get down
		// with somedata that isn't there
		if ( ! $date or ! $format) return NULL;
		
		$this->load->helper('date');
	
		// Make sure we have a UNIX date
		if ( ! is_numeric($date)) $date = mysql_to_unix($date);
		
		// Is this a preset?
		if (in_array($format, $date_formats)) return standard_date($format, $date);

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
		// Cache
		// -------------------------------------

		$this->setup_cache();

		if ( ! is_null($full_tag_cache = $this->full_tag_cache()))
		{
			return $full_tag_cache;
		}

		// -------------------------------------
		// Get vars
		// -------------------------------------

		// We are going to set these to inert values
		// to start off with.
		$params = array(
			'limit'			=> 1,
			'offset'		=> 0,
			'order_by'		=> false,
			'sort'			=> false,
			'exclude'		=> false,
			'show_upcoming'	=> null,
			'show_past'		=> null,
			'year'			=> null,
			'month'			=> null,
			'day'			=> null,
			'restrict_user'	=> 'no',
			'single'		=> 'yes'
		);
		
		$this->debug_status		 	= $this->streams_attribute('debug', 'on');
		$params['stream'] 			= $this->streams_attribute('stream');
		$params['id'] 				= $this->streams_attribute('entry_id');
		$params['where'] 			= $this->streams_attribute('where');
		$params['disable']			= $this->streams_attribute('disable');
		$params['sort']				= $this->streams_attribute('sort');
		$params['namespace']		= $this->streams_attribute('namespace', $this->core_namespace);

		// -------------------------------------
		// Get stream
		// -------------------------------------
		
		if ( ! $params['stream'] ) return $this->_error_out(lang('streams.invalid_stream'));
		
		$stream = $this->streams_m->get_stream($params['stream'], TRUE, $params['namespace']);
		
		if ($stream === false) return $this->_error_out(lang('streams.invalid_stream'));
		
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

		if ($this->cache_type == 'query' and ! is_null($this->cache))
		{
			$rows = $this->pyrocache->model('row_m', 'get_rows', array($params, $this->fields, $stream), $this->cache);
		}
		else
		{
			$rows = $this->row_m->get_rows($params, $this->fields, $stream);
		}
	
		// -------------------------------------
		// Get content
		// -------------------------------------
		
		if ( ! $rows)
		{
			$return_content = $this->streams_attribute('no_results', lang('streams.no_results'));
		}
		else
		{
			$return_content = $this->streams_content_parse($this->content(), $rows['rows'][0], $params['stream']);
		}

		// -------------------------------------
		// Cache End Procedures
		// -------------------------------------

		$this->tag_cache_write($return_content);

		$this->clear_cache_vars();

		// -------------------------------------

		return $return_content;
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
		
		$this->load->library(array('form_validation', 'streams_core/Fields'));
 
		// -------------------------------------
		// Get vars
		// -------------------------------------
		
		$extra = array();

		$mode 					= $this->streams_attribute('mode', 'new');
		
		// Make sure that we have a valid mode.
		if ($mode != 'new' and $mode != 'edit') $mode = 'new';

		$edit_id 				= $this->streams_attribute('edit_id', FALSE);
		$edit_segment 			= $this->streams_attribute('edit_segment', FALSE);
		$stream_slug 			= $this->streams_attribute('stream');
		$stream_segment 		= $this->streams_attribute('stream_segment');
		$where 					= $this->streams_attribute('where');
		$include 				= $this->streams_attribute('include');
		$exclude 				= $this->streams_attribute('exclude');
		$recaptcha 				= $this->streams_attribute('use_recaptcha', 'no');
		$creator_only       	= $this->streams_attribute('creator_only', false);
		$namespace 				= $this->streams_attribute('namespace', $this->core_namespace);

		$extra['required'] 		= $this->streams_attribute('required', '<span class="required">* required</span>');
		$extra['return'] 		= $this->streams_attribute('return', $this->uri->uri_string());
		$extra['error_start'] 	= $this->streams_attribute('error_start', '<span class="error">');
		$extra['error_end']		= $this->streams_attribute('error_end', '</span>');
		
		$this->streams_attribute('use_recaptcha', 'no') == 'yes' ? $recaptcha = TRUE : $recaptcha = FALSE;

		// -------------------------------------
		// Messages
		// -------------------------------------
		// Lang line references:
		// - new_entry_success
		// - new_entry_error
		// - edit_entry_success
		// - edit_entry_error
		// -------------------------------------
		
		$extra['success_message'] 	= $this->streams_attribute('success_message', $this->lang->line("streams.{$mode}_entry_success"));
		$extra['failure_message'] 	= $this->streams_attribute('failure_message', $this->lang->line("streams.{$mode}_entry_error"));
							
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$data->stream			= $this->streams_m->get_stream($stream_slug, TRUE, $namespace);
		
		if ( ! $data->stream) return lang('streams.invalid_stream');
		
		$data->stream_id		= $data->stream->id;

		// -------------------------------------
		// Collect Email Notification Data
		// -------------------------------------
		// Default is two notifications. We collect
		// this data no matter what and the 
		// form library takes care of the rest.
		// -------------------------------------
	
		$notifications 			= array();

		$numbers = array('a', 'b');
	
		foreach ($numbers as $notify_num)
		{
			$notifications[$notify_num]['notify'] 		= $this->streams_attribute('notify_'.$notify_num);
			$notifications[$notify_num]['template'] 	= $this->streams_attribute('notify_template_'.$notify_num);
			$notifications[$notify_num]['from'] 		= $this->streams_attribute('notify_from_'.$notify_num);
		}		
		
		$extra['email_notifications'] = $notifications;
		
		// -------------------------------------
		// Get Edit ID from URL if in Edit Mode
		// -------------------------------------
		
		$row = false;
		
		if ($mode == 'edit')
		{
			// Do they want us to grab the ID from the URL?
			if (is_numeric($edit_segment))
			{
				$edit_id = $this->uri->segment($edit_segment);
			}
			
			// Do they want a where?
			// This overrides the edit_id
			if ($where)
			{
				$bits = explode('==', $where);
				
				if (count($bits) == 2)
				{
					$query = $this->db->limit(1)->where($bits[0], $bits[1])->get($data->stream->stream_prefix.$data->stream->stream_slug);
					
					if($query->num_rows() == 1)
					{
						// WTF is this doing? It gets
						// overwritten anyways.
						$row = $query->row();	
						$edit_id = $row->id;
					}
				}
			}
			else
			{
				// Get the row
				$row = $this->row_m->get_row($edit_id, $data->stream, FALSE);
			}			
		}

		// -------------------------------------
		// Check Author Only
		// -------------------------------------
		// If this mode is on edit, and it is set
		// to creator_only, we can check to see
		// if the editor is the creator.
		// -------------------------------------

		if ($creator_only == 'yes' and $mode == 'edit')
		{
			// Must be logged in
			if ( ! isset($this->current_user->id))
			{
				return null;
			}

			if($this->current_user->id != $row->created_by)
			{
				return null;
			}
		}

		// -------------------------------------
		// Include/Exclude
		// -------------------------------------

		$skips = $this->determine_skips($include, $exclude, $data->stream_id);

		// -------------------------------------
		// Process and Output Form Data
		// -------------------------------------
	
		$vars['fields'] = $this->fields->build_form($data->stream, $mode, $row, TRUE, $recaptcha, $skips, $extra);

		// -------------------------------------
		// Individual Field Access 
		// -------------------------------------
		// For greater form control, this allows
		// users to access each form item
		// indivudally.
		// -------------------------------------

		foreach($vars['fields'] as $field)
		{
			$vars[$field['input_slug']]['label'] 			= $field['input_title'];
			$vars[$field['input_slug']]['slug'] 			= $field['input_slug'];
			$vars[$field['input_slug']]['value'] 			= $field['value'];

			if($field['input_parts'] !== false)
			{
				$vars[$field['input_slug']]['input']		= $field['input_parts'];
				$vars[$field['input_slug']]['input_built']	= $field['input'];
			}
			else
			{
				$vars[$field['input_slug']]['input']		= $field['input'];
				$vars[$field['input_slug']]['input_built']	= $field['input'];
			}

			$vars[$field['input_slug']]['error_raw'] 		= $field['error_raw'];
			$vars[$field['input_slug']]['error'] 			= $field['error'];
			$vars[$field['input_slug']]['is_required'] 		= ($field['required']) ? true : false;
			$vars[$field['input_slug']]['required'] 		= $field['required'];
			$vars[$field['input_slug']]['odd_even'] 		= $field['odd_even'];
		}
		
		// -------------------------------------
		// reCAPTCHA
		// -------------------------------------
		
		if ($recaptcha)
		{
			$this->recaptcha->_rConfig['theme'] = $this->streams_attribute('recaptcha_theme', 'red');

			$vars['recaptcha'] = $this->recaptcha->get_html();

			// Output the error if we have one
			if (isset($this->form_validation->_field_data['recaptcha_response_field']['error'])
				  and $this->form_validation->_field_data['recaptcha_response_field']['error'] != '')
			{
				$vars['recaptcha_error'] = $this->form_validation->error('recaptcha_response_field');
			}	
			else
			{
				$vars['recaptcha_error'] = '';
			}
		}
		
		// -------------------------------------
		// Form elements
		// -------------------------------------
		
		$params['class']		= $this->streams_attribute('form_class', 'crud_form');
		
		$hidden = array();
		
		if ($mode == 'edit') $hidden = array('row_edit_id' => $row->id);
		
		$vars['form_open']		= form_open_multipart($this->uri->uri_string(), $params, $hidden);				
		$vars['form_close']		= '</form>';
		$vars['form_submit']	= '<input type="submit" value="'.lang('save_label').'" />';
		$vars['form_reset']		= '<input type="reset" value="'.lang('streams.reset').'" />';

		$vars['validation_errors'] = validation_errors($extra['error_start'], $extra['error_end']);

		// -------------------------------------
		
		return array($vars);				
	}

	// --------------------------------------------------------------------------

	/**
	 * Determine the fields to skip
	 * based on include/exclude
	 */
	private function determine_skips($include, $exclude, $stream_id, $stream_fields = null)
	{
		$skips = array();

		if ($include)
		{
			$includes = explode('|', $include);

			if (is_null($stream_fields))
			{
				$stream_fields = $this->streams_m->get_stream_fields($stream_id);
			}

			// We need to skip everything else
			foreach ($stream_fields as $field)
			{
				if ( ! in_array($field->field_slug, $includes))
				{
					$skips[] = $field->field_slug;
				}
			}
		}
		if ($exclude)
		{
			// Exlcudes are just our skips
			$excludes = explode('|', $exclude);
		
			$skips = array_merge($excludes, $skips);
		}

		return $skips;
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
		if ( ! empty($this->type->assets))
		{
			// Weird fix that seems to work for fixing WYSIWYG
			// since it is throwing missing variable errors
			$html = '<script type="text/javascript">var SITE_URL = "'.$this->config->item('base_url').'";</script>';
		
			foreach($this->type->assets as $asset)
			{
				$html .= $asset."\n";
			}
			
			return $html;
		}
	}

	// --------------------------------------------------------------------------

	/**
	 * Form CSRF input
	 *
	 * You might need this if you are not using the {{ form_open }} variable.
	 *
	 * @access 	public
	 * @return 	mixed - null or string
	 */
	public function form_csrf()
	{
		if ($this->config->item('csrf_protection'))
		{
			return form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
		}		
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Form Fields
	 *
	 * Allows you to simple show form fields without 
	 */
	public function form_fields()
	{
		$this->load->library(array('form_validation', 'streams_core/Fields'));

		$mode 				= $this->streams_attribute('mode', 'new');
		$edit_id 			= $this->streams_attribute('edit_id', false);
		$stream_slug 		= $this->streams_attribute('stream');
		$namespace 			= $this->streams_attribute('namespace', $this->core_namespace);
		$include 			= $this->streams_attribute('include');
		$exclude 			= $this->streams_attribute('exclude');
		$required 			= $this->streams_attribute('required', '<span class="required">* required</span>');
		
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$data->stream			= $this->streams_m->get_stream($stream_slug, TRUE, $namespace);
		
		if ( ! $data->stream) return lang('streams.invalid_stream');
		
		$data->stream_id		= $data->stream->id;
		$vars = array();

		// -------------------------------------
		// Get the row in edit mode
		// -------------------------------------
		
		$row = false;
		
		if ($mode == 'edit')
		{
			$row = $this->row_m->get_row($edit_id, $data->stream, false);
		}

		// -------------------------------------
		// Set up skips & values
		// -------------------------------------

		$stream_fields = $this->streams_m->get_stream_fields($data->stream_id);

		$skips = $this->determine_skips($include, $exclude, $data->stream_id, $stream_fields);

		$values = $this->fields->set_values($stream_fields, $row, $mode, $skips);

		// -------------------------------------
		// Get & Return Fields
		// -------------------------------------

		$vars['fields'] = $this->fields->build_fields($stream_fields, $values, $row, $mode, $skips, $required);		

		// -------------------------------------
		// Individual Field Access 
		// -------------------------------------
		// For greater form control, this allows
		// users to access each form item
		// indivudally.
		// -------------------------------------

		foreach($vars['fields'] as $field)
		{
			$vars[$field['input_slug']]['label'] 			= $field['input_title'];
			$vars[$field['input_slug']]['slug'] 			= $field['input_slug'];
			$vars[$field['input_slug']]['value'] 			= $field['value'];

			if($field['input_parts'] !== false)
			{
				$vars[$field['input_slug']]['input']		= $field['input_parts'];
				$vars[$field['input_slug']]['input_built']	= $field['input'];
			}
			else
			{
				$vars[$field['input_slug']]['input']		= $field['input'];
				$vars[$field['input_slug']]['input_built']	= $field['input'];
			}

			$vars[$field['input_slug']]['error_raw'] 		= $field['error_raw'];
			$vars[$field['input_slug']]['error'] 			= $field['error'];
			$vars[$field['input_slug']]['is_required'] 		= ($field['required']) ? true : false;
			$vars[$field['input_slug']]['required'] 		= $field['required'];
			$vars[$field['input_slug']]['odd_even'] 		= $field['odd_even'];
		}

		return array($vars);				
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

		$this->load->library(array('form_validation', 'streams_core/Fields'));

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
		
		$stream			= $this->streams_m->get_stream($stream_slug, TRUE, $this->core_namespace);
		
		if ( ! $stream) show_error(lang('streams.invalid_stream'));
	
		// -------------------------------------
		// Check Delete
		// -------------------------------------
	
		if ($this->input->post('delete_confirm'))
		{
			$this->db->where('id', $entry_id)->delete($stream->stream_prefix.$stream->stream_slug);
			
			$this->load->helper('url');
			
			redirect(str_replace('-id-', $entry_id, $return));
		}	
		else
		{
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

			$rows = $this->row_m->get_rows($params, $this->fields, $stream);
			
			if ( ! isset($rows['rows'][0])) return $this->streams_attribute('no_entry', lang('streams.no_entry'));
			
			$vars['entry'][0] = $rows['rows'][0];
	
			// -------------------------------------
			// Parse other vars
			// -------------------------------------

			$vars['form_open'] 		= form_open($this->uri->uri_string());
			$vars['form_close']		= '</form>';
			$vars['delete_confirm']	= '<input type="submit" name="delete_confirm" value="'.lang('streams.delete').'" />';
			
			$rows = null;
			
			return array($vars);
		}
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
		// Cache
		// -------------------------------------

		$this->setup_cache();

		if ( ! is_null($full_tag_cache = $this->full_tag_cache()))
		{
			return $full_tag_cache;
		}

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

		if (is_numeric($year_segment) AND is_numeric($this->uri->segment($year_segment)))
		{
			$year = $this->uri->segment($year_segment);
		}

		if (is_numeric($month_segment) and is_numeric($this->uri->segment($month_segment)))
		{
			$month = $this->uri->segment($month_segment);
		}

		// Default to current
		if ( ! is_numeric($year)) $year = date('Y');
		if ( ! is_numeric($month)) $month = date('n');

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
				
		foreach ($streams as $stream_slug)
		{
			$date_field = $date_fields[$count];

			$stream = $this->streams_m->get_stream($stream_slug, TRUE, $this->core_namespace);
	
			$this->fields = $this->streams_m->get_stream_fields($stream->id);
			
			$params = array(
				'date_by'	 	=> $date_field,
				'get_day' 		=> TRUE,
				'year' 			=> $year,
				'month' 		=> $month
			);

			// -------------------------------------
			// Get rows
			// -------------------------------------

			if ($this->cache_type == 'query' and ! is_null($this->cache))
			{
				$rows = $this->pyrocache->model('row_m', 'get_rows', array($params, $this->fields, $stream), $this->cache);
			}
			else
			{
				$rows = $this->row_m->get_rows($params, $this->fields, $stream);
			}

			$this->clear_cache_vars();
				
			// -------------------------------------
			// Format Calendar Data
			// -------------------------------------
			
			foreach ($rows as $above)
			{
				foreach ($above as $entry)
				{
					if (isset($displays[$count]))
					{
						// Replace fields				
						$display_content 	= $displays[$count];
						$link_content 		= $links[$count];
			
						$parser = new Lex_Parser();
						$parser->scope_glue(':');
						
						$display_content = str_replace(array('[', ']'), array('{{ ', ' }}'), $display_content);
						$link_content = str_replace(array('[', ']'), array('{{ ', ' }}'), $link_content);
													
						$display_content = $parser->parse($display_content, $entry, array($this->parser, 'parser_callback'));
						$link_content = $parser->parse($link_content, $entry, array($this->parser, 'parser_callback'));
									
						// Link
						if ($link_content != '' )
						{
							$display_content = '<a href="'.site_url($link_content).'" class="'.$stream_slug.'_link">'.$display_content.'</a>';
						}							
						
						// Adding to the array
						if (isset($calendar[$entry['pyrostreams_cal_day']]))
						{
							$calendar[$entry['pyrostreams_cal_day']] .= $display_content.'<br />';
						}
						else
						{
							$calendar[$entry['pyrostreams_cal_day']]  = $display_content.'<br />';
						}
					}
				}
			}
					
			$count++;
		}
				
		// -------------------------------------
		// Get Template
		// -------------------------------------

		if ($template)
		{
			$this->db->limit(1)->select('body')->where('title', $template);
			$db_obj = $this->db->get('page_layouts');
			
			if($db_obj->num_rows() > 0)
			{
				$layout = $db_obj->row();
				$this->calendar_template = $layout->body;
			}
		}
	
		// -------------------------------------
		// Generate Calendar
		// -------------------------------------
		
		$calendar_prefs['template']			= $this->calendar_template;
		$calendar_prefs['start_day']		= strtolower($this->streams_attribute('start_day', 'sunday'));
		$calendar_prefs['month_type']		= $this->streams_attribute('month_type', 'long');
		$calendar_prefs['day_type']			= $this->streams_attribute('day_type', 'abr');
		$calendar_prefs['show_next_prev']	= $this->streams_attribute('show_next_prev', 'yes');
		$calendar_prefs['next_prev_url']	= $this->streams_attribute('next_prev_uri', '');

		if ($calendar_prefs['show_next_prev'] == 'yes')
		{
			$calendar_prefs['show_next_prev'] = true;
		}
		else
		{
			$calendar_prefs['show_next_prev'] = false;
		}

		$this->load->library('calendar', $calendar_prefs);

		$return_content = $this->calendar->generate($year, $month, $calendar);

		// -------------------------------------
		// Cache End Procedures
		// -------------------------------------

		$this->tag_cache_write($return_content);

		$this->clear_cache_vars();

		// -------------------------------------

		return $return_content;
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
		
		if ( ! in_array($search_type, $search_types))
		{
			show_error($search_type.' '.lang('streams.invalid_search_type'));
		}

		// -------------------------------------
		// Check for our search term
		// -------------------------------------
		
		if (isset($_POST['search_term']))
		{
			$this->load->model('streams/search_m');
			
			// Write cache
			$cache_id = $this->search_m->perform_search($this->input->post('search_term'), $search_type, $stream_slug, $fields);
		
			// Redirect
			$this->load->helper('url');
			redirect($results_page.'/'.$cache_id);
		}
		
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
		// Pagination segment is always right after the cache hash segment
		$pag_segment	= $cache_segment+1;

		$paginate		= $this->streams_attribute('paginate', 'no');
		$cache_segment	= $this->streams_attribute('cache_segment', 3);
		$per_page		= $this->streams_attribute('per_page', 25);
		$variables		= array();

		// -------------------------------------
		// Check for Cached Search Query
		// -------------------------------------

		$this->load->model('streams/search_m');

		if ( ! $cache = $this->search_m->get_cache($this->uri->segment($cache_segment)))
		{
			// Invalid search
			show_error(lang('streams.search_not_found'));
		}

		$this->fields = $this->streams_m->get_stream_fields($this->streams_m->get_stream_id_from_slug($cache->stream_slug));

		// Easy out for no results
		if ($cache->total_results == 0)
		{
			return array(
				'no_results' 		=> $this->streams_attribute('no_results', lang('streams.no_results')),
				'results_exist'		=> FALSE,
				'results'			=> array(),
				'pagination'		=> NULL,
				'search_term' 		=> $this->streams_attribute('search_term', $cache->search_term),
				'total_results'		=> (string)'0'
			);		
		}
		
		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		$return = array();
	
		$return['total'] 	= $cache->total_results;

		if ($paginate == 'yes')
		{
			// Add in our pagination config
			// override varaibles.
			foreach($this->pagination_config as $key => $var)
			{
				$this->pagination_config[$key] = $this->attribute($key, $this->pagination_config[$key]);
				
				// Make sure we obey the FALSE params
				if($this->pagination_config[$key] == 'FALSE') $this->pagination_config[$key] = FALSE;
			}

			$return['pagination'] = $this->row_m->build_pagination($pag_segment, $per_page, $return['total'], $this->pagination_config);
			
			$offset = $this->uri->segment($pag_segment, 0);
			
			$query_string = $cache->query_string." LIMIT $offset, $per_page";
		}
		else
		{
			$return['pagination'] 	= NULL;	
			$query_string = $cache->query_string;
		}

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
	 * @param	string - stream slug
	 * @param 	[bool - whether or not to loop through the results or not]
	 * @return 	string - the parsed data
	 */
	private function streams_content_parse($content, $data, $stream_slug, $loop = false)
	{
		// -------------------------------------
		// Multiple Provision
		// -------------------------------------
		// Automatically add in multiple streams data.
		// This makes it easier to call the multiple function
		// from within the streams tags
		// -------------------------------------

		$rep = array('{{ streams:related', '{{streams:related');
		$content = str_replace($rep, '{{ streams:related stream="'.$stream_slug.'" entry=id ', $content);

		$rep = array('{{ streams:multiple', '{{streams:multiple');
		$content = str_replace($rep, '{{ streams:multiple stream="'.$stream_slug.'" entry=id ', $content);
		
		// -------------------------------------
		// Parse
		// -------------------------------------

		$parser = new Lex_Parser();
		$parser->scope_glue(':');

		if ( ! $loop)
		{
			return $parser->parse($content, $data, array($this->parser, 'parser_callback'));
		}

		$out = '';

		foreach ($data as $item)
		{
			$out .= $parser->parse($content, $item, array($this->parser, 'parser_callback'));
		}

		return $out;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Output debug message or just
	 * return FALSE.
	 *
	 * @access	private
	 * @param	string
	 * @return 	mixed
	 */	
	private function _error_out($msg)
	{
		return ($this->debug_status == 'on') ? show_error($msg) : FALSE;
	}

}