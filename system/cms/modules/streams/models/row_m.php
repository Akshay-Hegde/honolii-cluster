<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Row Model
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Row_m extends MY_Model {

	public $ignore = array('id', 'created', 'updated', 'created_by');
	
	public $data;
	
	public $cycle_defaults = array(
	
		'exclude_by'		=> 'id',
		'include_by'		=> 'id'
	
	);
	
	public $base_prefix;
	
	public $select_string;
	
	public $all_fields;
	
	public $structure;
	
	/**
	 * Array of IDs called, by stream.
	 * Used to exclude IDs already called.
	 * @since 2.1
	 */
	public $called;
	
	/**
	 * Hook for get_rows
	 *
	 * array($obj, $method_name)
	 */
	public $get_rows_hook 	= array();
	
	// Data to send to the function
	public $get_rows_hook_data;

	// --------------------------------------------------------------------------

	/**
	 * Get rows from a stream
	 *
	 * @return 	array 
	 * @param	array
	 * @param	obj
	 * @param	obj
	 * @param	obj
	 * @return	array
	 */
	public function get_rows($params, $fields, $stream)
	{
		$return = array();

		// First, let's get all out fields. That's
		// right. All of them.
		$this->all_fields = $this->fields_m->get_all_fields();
		
		// Now the structure. We will need this as well.
		$this->structure = $this->gather_structure();
	
		// So we don't get things confused
		if(isset($params['stream'])) unset($params['stream']);

		// -------------------------------------
		// Set our defaults
		// While many items check if they are set,
		// we can set defaults with the cycyle_defaults
		// class array
		// -------------------------------------
	
		foreach($this->cycle_defaults as $key => $value):
		
			if(!isset($params[$key])):
			
				$params[$key] = $value;
			
			endif;
		
		endforeach;
	
		// -------------------------------------
		// Extract Our Params
		// -------------------------------------

		extract($params, EXTR_OVERWRITE);

		// -------------------------------------
		// Get Data We'll Need
		// -------------------------------------
		
		$this->data->stream = $stream;

		$stream_fields = $this->streams_m->get_stream_fields($this->data->stream->id);

		//Just for sanity's sake
		$this->full_select_prefix = PYROSTREAMS_DB_PRE.STR_PRE.$stream->stream_slug.'.';
		$this->base_prefix = STR_PRE.$stream->stream_slug.'.';
		
		// Get your asses in the seats
		$this->db->flush_cache();

		// -------------------------------------
		// Start Query Build
		// -------------------------------------
		
		// We may build on this.
		$this->select_string = STR_PRE."$stream->stream_slug.*";
		
		// -------------------------------------
		// Get the day.
		// For calendars and stuff
		// -------------------------------------

		if( isset($get_day) and $get_day == true ):
		
			$this->select_string .= ', DAY('.$this->full_select_prefix.$date_by.') as pyrostreams_cal_day';
		
		endif;
	
		// -------------------------------------
		// Disable
		// -------------------------------------
		// Allows users to turn off relationships
		// and created_by to save some queries
		// -------------------------------------
		
		if( isset($disable) and $disable ):

			$disable = explode("|", $disable);
			
		else:
		
			$disable = array();
		
		endif;

		// -------------------------------------
		// Specialing Sorting
		// -------------------------------------

		// Special provision for sort by random
		if( isset($sort) and $sort == 'random' ):
		
			$this->db->order_by('RAND()');

		else:
	
			// Default Sort
			if( !isset($sort) or $sort == '' ) $sort = 'asc';
	
			// If we don't have an override, let's see what
			// else we can sort by
			if( !isset($order_by) or $order_by == '' ):
			
				// Let's go with the stream setting now
				// since there isn't an override	
				if( $stream->sorting == 'title' && $stream->title_column != '' ):
				
					$order_by = $stream->title_column;	
			
				elseif( $stream->sorting == 'custom' ):
	
					$order_by 	= 'ordering_count';
					$sort		= 'asc';	
				
				endif;
			
			endif;
	
			// -------------------------------------
			// Order by
			// -------------------------------------
			
			if( isset($order_by) and $order_by ) $this->db->order_by( $this->base_prefix.$order_by, $sort );
		
		endif;

		// -------------------------------------
		// Exclude
		// -------------------------------------
		
		// Do we have anything in the excludes that was can add?
		if(	isset($exclude_called) and $exclude_called == 'yes' and 
			isset($this->called[$stream->stream_slug]) and !empty($this->called[$stream->stream_slug])):
		
			$exclude .= '|'.implode('|', $this->called[$stream->stream_slug]);
		
		endif;
		
		if( isset($exclude) and $exclude ):
		
			$exclusions = explode('|', $exclude);
			
			foreach( $exclusions as $exclude_id ):
			
				$this->db->where( $this->base_prefix.$exclude_by.' !=', $exclude_id );
			
			endforeach;
		
		endif;

		// -------------------------------------
		// Include
		// -------------------------------------
		
		if( isset($include) and $include ):
		
			$inclusions = explode('|', $include);
			
			foreach( $inclusions as $include_id ):
			
				$this->db->or_where( $this->base_prefix.$include_by.' =', $include_id );
			
			endforeach;
		
		endif;

		// -------------------------------------
		// Where
		// -------------------------------------

		if( isset($where) and $where ):
		
			// Replace the segs
			
			$seg_markers 	= array('seg_1', 'seg_2', 'seg_3', 'seg_4', 'seg_5', 'seg_6', 'seg_7');
			$seg_values		= array($this->uri->segment(1), $this->uri->segment(2), $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5), $this->uri->segment(6), $this->uri->segment(7));
		
			$where = str_replace($seg_markers, $seg_values, $where);
			
			$where_pieces = explode("|", $where);
			
			foreach($where_pieces as $w):
			
				$vals = explode("==", trim($w));
				
				if( count($vals) == 2 ):
				
					$this->db->where( $this->base_prefix.$vals[0], $vals[1] );
				
				endif;
			
			endforeach;
		
		endif;
		
		// -------------------------------------
		// Show Upcoming
		// -------------------------------------
		
		if( isset($show_upcoming) and $show_upcoming == 'no'):
		
			$upc_prefix = (count($this->db->ar_where) == 0 AND count($this->db->ar_cache_where) == 0) ? '' : 'AND';
			
			$this->db->ar_where[] = $upc_prefix.' '.$this->db->_protect_identifiers($this->base_prefix.$date_by, FALSE, TRUE).' <= CURDATE()';

		endif;

		// -------------------------------------
		// Show Past
		// -------------------------------------
		
		if( isset($show_past) and $show_past == 'no'):

			$past_prefix = (count($this->db->ar_where) == 0 AND count($this->db->ar_cache_where) == 0) ? '' : 'AND';
			
			$this->db->ar_where[] = $past_prefix.' '.$this->db->_protect_identifiers($this->base_prefix.$date_by, FALSE, TRUE).' >= CURDATE()';

		endif;

		// -------------------------------------
		// Month / Day / Year
		// -------------------------------------
		
		if( isset($year) and is_numeric($year) ):
		
			$this->db->where('YEAR('.$this->full_select_prefix.$date_by.')', $year);
		
		endif;

		if( isset($month) and is_numeric($month) ):
		
			$this->db->where('MONTH('.$this->full_select_prefix.$date_by.')', $month);
		
		endif;

		if( isset($day) and is_numeric($day) ):
		
			$this->db->where('DAY('.$this->full_select_prefix.$date_by.')', $day);
		
		endif;

		// -------------------------------------
		// Relationships
		// -------------------------------------
		
		if( ! in_array('relationships', $disable) ):

			foreach( $stream_fields as $field_slug => $field ):
			
				// Do we have a relationship?
				if( $field->field_type == 'relationship' && isset($field->field_data['choose_stream']) ):
				
					$rel_stream_slug = $this->streams_m->streams_cache[$field->field_data['choose_stream']];
				
					// Add the Join
					$this->db->join(	
						STR_PRE.$rel_stream_slug->stream_slug.' as '.$field_slug.'_jn',
						STR_PRE.$stream->stream_slug.'.'.$field_slug.' = '.$field_slug.'_jn.id',
						'LEFT' );
						
					// Add to Select
					foreach( $this->structure[$field->field_data['choose_stream']]['fields'] as $rel_field ):
					
						if(!isset($this->type->types->{$rel_field->field_type}->alt_process) or !$this->type->types->{$rel_field->field_type}->alt_process):
	
							$this->select_string .= ', '.$field_slug.'_jn.'.$rel_field->field_slug.' as \''.$field_slug.'.'.$rel_field->field_slug."'";
						
						endif;
					
					endforeach;
					
				endif;
			
			endforeach;
		
		endif;	

		// -------------------------------------
		// Restrict User
		// -------------------------------------
		
		if( isset($restrict_user) ):
		
			if( $restrict_user != 'no' ):
			
				// Should we restrict to the current user?
				if( $restrict_user == 'current' ):
				
					// Check and see if a user is logged in
					// and then set the param
					if( $this->user->id ):
					
						$restrict_user = $this->user->id;
					
					endif;
				
				elseif( is_numeric($restrict_user) ):
				
					// It's numeric, meaning we don't have to do anything. Durrr...
				
				else:
				
					// Looks like they might have put in a user's handle
					$this->db->limit(1)->select('id')->where('username', $user);
					$db_obj = $this->db->get('users');
					
					if( $db_obj->num_rows == 0 ):
					
						// Whoops, no dice.
						$restrict_user = 'no';
					
					else:
					
						$user = $db_obj->row();
						
						$restrict_user = $user->id;
					
					endif;
				
				endif;
			
			endif;
		
			if( $restrict_user != 'no' && is_numeric($restrict_user) ):
			
				$this->db->where($this->full_select_prefix.'created_by', $restrict_user, FALSE );
			
			endif;
		
		endif;

		// -------------------------------------
		// Get Created By Data
		// -------------------------------------
		
		if( ! in_array('created_by', $disable) ):

			$this->db->join('users', STR_PRE."$stream->stream_slug.created_by = ".'users.id' );
			
			// We are just going to take a few things. Let's not get greedy.
			$this->select_string .= " , users.id as 'created_by.id', users.email as 'created_by.email', users.group_id as 'created_by.group_id', users.username as 'created_by.username'";
			
		endif;

		// -------------------------------------
		// Get by ID & Single
		// -------------------------------------
		
		if( isset($id) and is_numeric($id) ):
		
			$this->db->where( $this->base_prefix.'id', $id );
			
			$limit = 1;
		
		endif;

		if( isset($single) and $single == 'yes' ) $limit = 1;

		// -------------------------------------
		// Hook
		// -------------------------------------
		
		if( $this->get_rows_hook ):
		
			if(method_exists($this->get_rows_hook[0], $this->get_rows_hook[1])):
						
				$this->get_rows_hook[0]->{$this->get_rows_hook[1]}($this->get_rows_hook_data);
			
			endif;
		
		endif;
		
		// -------------------------------------
		// Run Our Select
		// -------------------------------------
		
		$this->db->select( $this->select_string );		

		// -------------------------------------
		// Pagination
		// -------------------------------------
		
		if( isset($paginate) and $paginate == 'yes' ):
		
			// If we are paginating, then we 
			// need to return the count based on all
			// the shit we did above this.
			// Check out this dumb hack.
			$class_vars = get_class_vars('CI_DB_active_record');
			
			$tmp = array();
			
			foreach($class_vars as $var => $val):
			
				$tmp[$var] = $this->db->$var;
			
			endforeach;		
			
			$tmp_obj = $this->db->get( STR_PRE.$stream->stream_slug );
			
			$return['pag_count'] = $tmp_obj->num_rows();
			
			// We basically just borrowed the class for a
			// second and then put everything back.
			// No one will be any the wiser!! Durrr!
			foreach($tmp as $v => $k):
			
				$this->db->$v = $k;
			
			endforeach;

			// Set the offset
			if( $this->uri->segment( $pag_segment ) == '' ):
			
				$offset = 0;
	
			else:
			
				$offset = $this->uri->segment( $pag_segment );
						
			endif;
		
		endif;

		// -------------------------------------
		// Offset
		// -------------------------------------

		if( !isset($offset) ) $offset = 0;

		// -------------------------------------
		// Limit
		// -------------------------------------
		
		if( isset($limit) and is_numeric($limit) ):
		
			$this->db->limit( $limit, $offset );
		
		elseif( isset($offset) and is_numeric($offset) ):

			$this->db->offset( $offset );
		
		endif;

		// -------------------------------------
		// Run the Get
		// -------------------------------------
		
		$obj = $this->db->get( STR_PRE.$stream->stream_slug );
		
		$data = $obj->result_array();
				
		$total = count($data);

		// -------------------------------------
		// Run formatting
		// -------------------------------------
		
		$count = 1;
		
		foreach( $data as $id => $item ):
		
			// Log the ID called
			$this->called[$this->data->stream->stream_slug][] = $item['id'];
		
			$data[$id] = $this->format_row( $item, $stream_fields, $this->data->stream, FALSE, TRUE );
			
			// Give some info on if it is the last element
			if( $count == $total ):
			
				$data[$id]['last']	= '1';
			
			else:
			
				$data[$id]['last']	= '0';
			
			endif;
			
			// Odd/Even			
			if( $count%2 == 0 ):
			
				$data[$id]['odd_even'] = 'even';
			
			else:
			
				$data[$id]['odd_even'] = 'odd';
			
			endif;
			
			$data[$id]['count'] = $count;
			
			$count++;
		
		endforeach;
		
		$return['rows'] = $data;
		
		// Reset
		$this->get_rows_hook = array();
		$this->select_string = '';
				
		return $return;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get a row
	 *
	 * @access	public
	 * @param	int
	 * @param	obj
	 * @param	[bool]
	 * @return	mixed
	 */
	public function get_row( $id, $stream, $format_output = TRUE )
	{
		// First, let's get all out fields. That's
		// right. All of them.
		if(!$this->all_fields) $this->all_fields = $this->fields_m->get_all_fields();
		
		// Now the structure. We will need this as well.
		if(!$this->structure) $this->structure = $this->gather_structure();

		$stream_fields = $this->streams_m->get_stream_fields($stream->id);

		$obj = $this->db->limit(1)->where('id', $id)->get(STR_PRE.$stream->stream_slug);
		
		if( $obj->num_rows() == 0 ):
		
			return FALSE;
		
		else:
		
			$row = $obj->row();
			
			if( $format_output ):
			
				return $this->format_row( $row , $stream_fields, $stream );
			
			else:
					
				return $row;
			
			endif;
		
		endif;
	}

	// --------------------------------------------------------------------------	
	
	/**
	 * Format Row
	 *
	 * Formats a row based on format profile
	 *
	 * @access	public
	 * @param	array or obj
	 * @param	array
	 * @param	obj
	 * @param	[bool]
	 * @param	[bool]
	 * @param	[string]
	 */
	public function format_row( $row, $stream_fields, $stream, $return_object = TRUE, $plugin_call = FALSE, $prefix = '' )
	{		
		// First, let's get all out fields. That's
		// right. All of them.
		if(!$this->all_fields) $this->all_fields = $this->fields_m->get_all_fields();
		
		// Now the structure. We will need this as well.
		if(!$this->structure) $this->structure = $this->gather_structure();

		// Set prefix delimiter
		if( $prefix != '' ) $prefix .= '.';
				
		// Format the row items
		foreach( $row as $raw_row_slug => $data ):
		
			if(in_array($raw_row_slug, array('id', 'created_by'))) continue;
					
			// Find the row slug to see if
			// we have anything separated by periods
			$slug_segs = explode(".", $raw_row_slug);
			
			if( count($slug_segs) > 1 ):
			
				$prefix 	= $slug_segs[0].'.';

				$row_slug 	= $slug_segs[count($slug_segs)-1];		
			
			else:
			
				$row_slug = $raw_row_slug;
			
			endif;
			
			// Dates
			if($row_slug == 'created' or $row_slug == 'updated'):
			
				if($return_object):
				
					$row->$raw_row_slug = strtotime($row->$raw_row_slug);
				
				else:
		
					$row[$raw_row_slug] = strtotime($row[$raw_row_slug]);
				
				endif;
			
			endif;
						
			// So, does this row exist to be formatted?
			if(!is_array($this->all_fields)) continue;
			
			if(array_key_exists($row_slug, $this->all_fields)):
			
				$format_data = $this->all_fields[$row_slug];
						
				$type = $this->type->types->{$format_data['field_type']};
							
				// First off, is this an alt process type?
				if( isset($type->alt_process) and $type->alt_process === TRUE ):
				
					$out = null;
				
					if(!$plugin_call and method_exists($type, 'alt_pre_output')):
					
						$out = $type->alt_pre_output($row->id, $format_data['field_data'], $type, $stream);
					
					endif;
					
					($return_object) ? $row->$row_slug = $out : $row[$row_slug] = $out;
					
				else:
		
					// If not, check and see if there is a method
					// for pre output or pre_output_plugin
					if( $plugin_call and method_exists($type, 'pre_output_plugin') ):
					
						$plugin_output = $type->pre_output_plugin($prefix.$row_slug.'.', $row[$raw_row_slug], $format_data['field_data'], $row_slug);
					
						// Do we get an array or a string?
						if( is_array($plugin_output) ):
						
							// For arrays we replace the node with a new array
							// of data merged into the current array
							if( is_array($row) ):
							
								if(isset($type->plugin_return) and $type->plugin_return == 'array'):
								
									$row[$row_slug] = $plugin_output;
								
								elseif(isset($type->plugin_return) and $type->plugin_return == 'cycle'):
								
									// Don't do shit
								
								else:
						
									unset($row[$row_slug]);
								
									$row = array_merge($row, $plugin_output);
								
								endif;
								
							endif;		
						
						else:
							
							// Else it was just a special plugin output and we just need 
							// to use that.
							$row[$prefix.$row_slug] = $plugin_output;
						
						endif;
	
					elseif( method_exists($type, 'pre_output') ):
										
						if(is_array($row)):
							
							$out = $type->pre_output( $row[$raw_row_slug], $format_data['field_data'] );
						
						else:
						
							$out = $type->pre_output( $row->$raw_row_slug, $format_data['field_data'] );
						
						endif;
						
						($return_object) ? $row->{$prefix.$row_slug} = $out : $row[$prefix.$row_slug] = $out;
						
					endif;
									
				endif;
			
			endif;
		
		endforeach;

		// -------------------------------------
		// Run through alt processes
		// If this is not a plugin call, we just
		// need to get the alt processes and
		// add them to the row for display
		// -------------------------------------
		
		if(!$plugin_call):
		
			foreach($stream_fields as $row_slug => $f):
			
				if(isset($f->field_type, $this->ignore)):
						
				if(
					isset($this->type->types->{$f->field_type}->alt_process) and 
					$this->type->types->{$f->field_type}->alt_process === TRUE and 
					method_exists($this->type->types->{$f->field_type}, 'alt_pre_output')
				):

					$out = $this->type->types->{$f->field_type}->alt_pre_output($row->id, $this->all_fields[$row_slug]['field_data'], $f->field_type, $stream);
						
					($return_object) ? $row->$row_slug = $out : $row[$row_slug] = $out;
					
				endif;
				
				endif;
			
			endforeach;
		
		endif;

		// -------------------------------------
		
		return $row;
	}

	// --------------------------------------------------------------------------	
	
	/**
	 * Get the structure of the streams down. We never know
	 * when we are going to need this for formatting or
	 * reference.
	 *
	 * @access	public
	 * @return	array
	 */
	public function gather_structure()
	{		
		$obj = $this->db->query('
			SELECT '.PYROSTREAMS_DB_PRE.STREAMS_TABLE.'.*, '.PYROSTREAMS_DB_PRE.STREAMS_TABLE.'.id as stream_id, '.PYROSTREAMS_DB_PRE.FIELDS_TABLE.'.* 
			FROM '.PYROSTREAMS_DB_PRE.STREAMS_TABLE.', '.PYROSTREAMS_DB_PRE.ASSIGN_TABLE.', '.PYROSTREAMS_DB_PRE.FIELDS_TABLE.'
			WHERE '.PYROSTREAMS_DB_PRE.STREAMS_TABLE.'.id='.PYROSTREAMS_DB_PRE.ASSIGN_TABLE.'.stream_id and
			'.PYROSTREAMS_DB_PRE.FIELDS_TABLE.'.id='.PYROSTREAMS_DB_PRE.ASSIGN_TABLE.'.field_id');

		$fields = $obj->result();
		
		$struct = array();
		
		foreach($this->streams_m->streams_cache as $stream_id => $stream):
		
			$struct[$stream_id]['stream'] = $stream;
			
			foreach($fields as $field):
			
				if($field->stream_slug == $stream->stream_slug):
			
					$struct[$stream_id]['fields'][] = $field;
				
				endif;
			
			endforeach;
		
		endforeach;
		
		return $struct;
	}
	
	// --------------------------------------------------------------------------	
	
	/**
	 * Update a row in a stream
	 *
	 * @access	public
	 * @param	obj
	 * @param 	string
	 * @param	int
	 * @param	skips - optional array of skips
	 * @return	bool
	 */
	public function update_row($fields, $stream, $row_id, $skips = array())
	{
		// -------------------------------------
		// Run through fields
		// -------------------------------------

		$update_data = array();
		
		foreach( $fields as $field ):
		
			if(!in_array($field->field_slug, $skips)):
		
				$type_call = $field->field_type;
			
				$type = $this->type->types->$type_call;
	
				if(!isset($type->alt_process) or !$type->alt_process):
				
					// If a pre_save function exists, go ahead and run it
					if( method_exists($type, 'pre_save') ):
					
						$update_data[$field->field_slug] = $type->pre_save(
									$this->input->post($field->field_slug),
									$field,
									$stream,
									$row_id
						);
						
					else:
					
						$update_data[$field->field_slug] = $this->input->post($field->field_slug);
	
					endif;
				
				endif;
			
			endif;		
		
		endforeach;

		// -------------------------------------
		// Set standard fields
		// -------------------------------------

		$update_data['updated'] = date('Y-m-d H:i:s');
		
		// -------------------------------------
		// Insert data
		// -------------------------------------
		
		$this->db->where('id', $row_id);
		
		if( ! $this->db->update(STR_PRE.$stream->stream_slug, $update_data) ):
		
			return FALSE;
		
		else:
		
			return $row_id;
		
		endif;
	}

}

/* End of file row_m.php */