<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Multiple Relationships Field Type
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_multiple
{
	public $field_type_slug			= 'multiple';
	
	public $alt_process				= TRUE;
	
	public $db_col_type				= FALSE;

	public $custom_parameters		= array('choose_stream');

	public $version					= '1.2';

	public $author					= array('name'=>'Parse19', 'url'=>'http://parse19.com');

	// --------------------------------------------------------------------------

	/**
	 * Process before saving to database
	 *
	 * @access	public
	 * @param	string
	 * @param	obj
	 * @param	obj
	 * @param	int
	 * @return	void
	 */
	public function pre_save($input, $field, $stream, $id)
	{
		// Get the other stream & table name
		$linked_stream = $this->CI->streams_m->get_stream($field->field_data['choose_stream']);
		$table_name = STR_PRE.$stream->stream_slug.'_'.$linked_stream->stream_slug;
	
		// Are we editing this row?
		// If so, clear the data. We are just going to
		// replace it so now sense in trying to update it
		if(is_numeric($row_id = $this->CI->input->post('row_edit_id'))):
		
			$this->CI->db->where('row_id', $this->CI->input->post('row_edit_id'))->delete($table_name);
		
		else:
		
			$row_id = $id;
		
		endif;
		
		// Go through and add the values
		$items = explode(',', $input);
		
		foreach($items as $item):
		
			if(trim($item) == '') continue;
		
			$item_id = str_replace($field->field_slug.'_', '', $item);
		
			$insert_data = array(
				'row_id'							=> $row_id,
				$stream->stream_slug.'_id'			=> $stream->id,
				$linked_stream->stream_slug.'_id'	=> $item_id
			);
			
			$this->CI->db->insert($table_name, $insert_data);
	
		endforeach;
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before outputting to the backend
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function alt_pre_output( $row_id, $extra, $type, $stream )
	{
		if(! $join_stream = $this->CI->streams_m->get_stream($extra['choose_stream'])) return null;

		$title_column = $join_stream->title_column;
		
		// Default to ID for title column if not present
		if(!trim($title_column) or !$this->CI->db->field_exists($title_column, STR_PRE.$join_stream->stream_slug)):
		
			$title_column = 'id';
		
		endif;

		$form_data = array();

		// -------------------------------------
		// Figure out Join Table
		// -------------------------------------

		$join_table = PYROSTREAMS_DB_PRE.STR_PRE.$stream->stream_slug.'_'.$join_stream->stream_slug;
				
		// -------------------------------------
		// Get current data
		// -------------------------------------

		$html = '<ul>';
				
		$this->CI->db->where('jt.row_id', $row_id, FALSE);
		$this->CI->db->from($join_table.' AS jt');
		$this->CI->db->join(STR_PRE.$join_stream->stream_slug, 'jt.'.$join_stream->stream_slug.'_id = '.STR_PRE.$join_stream->stream_slug.'.id');
		$query = $this->CI->db->get();
		
		foreach($query->result() as $node):
		
			$html .= '<li><a href="'.site_url('admin/streams/entries/view/'.$join_stream->id.'/'.$node->id).'">'.$node->$title_column.'</a></li>';
		
		endforeach;

		$html .= '</ul>';
		
		return $html;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Alt Plugin Process
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function alt_process_plugin($data)
	{
		$params = $data['attributes'];
		
		// Get the stream
		$join_stream = $this->CI->streams_m->get_stream($data['field']->field_data['choose_stream']);
			
		// Get the fields		
		$this->fields = $this->CI->streams_m->get_stream_fields($join_stream->id);
		
		// Add the join_multiple hook to the get_rows function
		$this->CI->row_m->get_rows_hook = array($this, 'join_multiple');
		$this->CI->row_m->get_rows_hook_data = array(
		
			'join_table' => STR_PRE.$data['field']->stream_slug.'_'.$join_stream->stream_slug,
			'join_stream' => $join_stream,
			'row_id' =>  $data['row']['id']
		
		);

		// Get the rows
		$this->rows = $this->CI->row_m->get_rows($params, $this->fields, $join_stream);
		
		$html = '';
		
		foreach($this->rows['rows'] as $row):
		
			$html .= $this->CI->raw_parser->parse_string($data['content'], $row, TRUE);
			
		endforeach;
		
		return $html;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Join multiple hook
	 */
	function join_multiple($data)
	{
		$this->CI->db->join(	
			$data['join_table'],
			$data['join_table'].'.'.$data['join_stream']->stream_slug.'_id = '.STR_PRE.$data['join_stream']->stream_slug.".id",
			'LEFT' );
		$this->CI->db->where($data['join_table'].'.row_id', $data['row_id']);
	}

	// --------------------------------------------------------------------------

	/**
	 * Event
	 *
	 * @access	public
	 * @return	void
	 */
	public function event()
	{
		$this->CI->type->add_css('multiple', 'multiple.css');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Process for when adding field assignment
	 */
	function field_assignment_construct($field, $stream)
	{
		$this->CI->load->dbforge();
				
		// Get the stream we are attaching to.
		$linked_stream = $this->CI->streams_m->get_stream($field->field_data['choose_stream']);
		
		// Make a table
		$table_name = STR_PRE.$stream->stream_slug.'_'.$linked_stream->stream_slug;

		$fields = array(
	        'id' => array(
	                 'type' => 'INT',
	                 'constraint' => 11, 
	                 'unsigned' => TRUE,
	                 'auto_increment' => TRUE),
	        'row_id' => array(
	                 'type' => 'INT',
	                 'constraint' => 11),
	        $stream->stream_slug.'_id' => array(
	                 'type' => 'INT',
	                 'constraint' => 11),
	        $linked_stream->stream_slug.'_id' => array(
	                 'type' => 'INT',
	                 'constraint' => 11)
		);
		
		$this->CI->dbforge->add_field($fields);
		$this->CI->dbforge->add_key('id', TRUE);
		
		$this->CI->dbforge->create_table($table_name);
	}

	// --------------------------------------------------------------------------

	/**
	 * Process for when removing field assignment
	 *
	 * @access	public
	 * @param	obj
	 * @param	obj
	 * @return	void
	 */
	public function field_assignment_destruct($field, $stream)
	{
		// Get the stream we are attaching to.
		$linked_stream = $this->CI->streams_m->get_stream($field->field_data['choose_stream']);
		
		// @todo:
		// If the linked stream was already deleted, we have a bit
		// of a problem since we can't get the stream slug.
		// Until we figure that out, here's this:
		if ( ! $linked_stream) return NULL;
				
		// Get the table name
		$table_name = STR_PRE.$stream->stream_slug.'_'.$linked_stream->stream_slug;
		
		// Remove the table		
		$this->CI->dbforge->drop_table($table_name);
	}

	// --------------------------------------------------------------------------

	/**
	 * Entry delete
	 *
	 * @access	public
	 * @param	obj
	 * @param	obj
	 * @return	void
	 */
	public function entry_destruct($entry, $field, $stream)
	{
		// Delete the entries in our binding table
		$linked_stream = $this->CI->streams_m->get_stream($field->field_data['choose_stream']);
				
		// Get the table name
		$table_name = STR_PRE.$stream->stream_slug.'_'.$linked_stream->stream_slug;
		
		$this->CI->db->where('row_id', $entry->id)->delete($table_name);
	}

	// --------------------------------------------------------------------------

	/**
	 * Process renaming column
	 *
	 * @access	public
	 * @param	obj
	 * @param	obj
	 * @return	void
	 */
	public function alt_rename_column($field, $stream)
	{
		// Check and see if the relationship stream changed
		return null;
	}

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function form_output($data, $row, $field)
	{
		if(! $stream = $this->CI->streams_m->get_stream($data['custom']['choose_stream'])) return NULL;
		
		$title_column = $stream->title_column;
		
		// Default to ID for title column
		if(!trim($title_column) or !$this->CI->db->field_exists($title_column, STR_PRE.$stream->stream_slug)):
		
			$title_column = 'id';
		
		endif;

		$form_data = array();
		$form_data['slug'] = $data['form_slug'];

		// -------------------------------------
		// Figure out Join Table
		// -------------------------------------

		$join_table = STR_PRE.$field->stream_slug.'_'.$stream->stream_slug;
		
		// -------------------------------------
		// Get current data
		// -------------------------------------

		$skips = array();
		$form_data['current'] = array();
				
		$current = array();

		if(isset($row->id) and is_numeric($row->id)):
		
			$this->CI->db->where('jt.row_id', $row->id, FALSE);
			$this->CI->db->from($join_table.' AS jt');
$this->CI->db->join(STR_PRE.$stream->stream_slug, 'jt.'.$stream->stream_slug.'_id = '.STR_PRE.$stream->stream_slug.'.id');
			$query = $this->CI->db->get();
			
			foreach($query->result() as $node):
			
				$skips[]							= $node->id;
				$form_data['current'][$node->id] 	= $node->$title_column;
				$current[] 							= $form_data['slug'].'_'.$node->id;
			
			endforeach;

		endif;
		
		// Populate the values
		// Did we submit the form and need to get it from the post val?
		if($this->CI->input->post($form_data['slug'])):
			
			$items = explode(', ', $this->CI->input->post($form_data['slug']));
			
			foreach($items as $item):
			
				$item = trim($item);
				$id = str_replace($stream->stream_slug.'_', '', $item);
			
				if(is_numeric($id)) $this->CI->db->or_where('id', $id);
			
			endforeach;
			
			$obj = $this->CI->db->get(STR_PRE.$stream->stream_slug);
			
			foreach($obj->result() as $node):

				$form_data['current'][$node->id] 	= $node->$title_column;
			
			endforeach;
		
			// We need the imploded current string as well
			$form_data['current_string'] = $this->CI->input->post($form_data['slug']);
		
		else:
		
			// Nope, we just need to take it from the db
			$form_data['current_string'] = implode(',', $current);
		
		endif;
	
		// -------------------------------------
		// Get the entries		
		// -------------------------------------
		
		foreach($skips as $skip) $this->CI->db->where('id != ', $skip);
		
		$obj = $this->CI->db->get(STR_PRE.$stream->stream_slug);
				
		foreach($obj->result() as $row):
		
			// Need to replace with title column
			$form_data['choices'][$row->id] = $row->$title_column;
		
		endforeach;
		
		return $this->CI->type->load_view('multiple', 'sort_table', $form_data, TRUE);
	}

	// --------------------------------------------------------------------------

	/**
	 * Get a list of streams to choose from
	 *
	 * @access	public
	 * @param	int - stream_id
	 * @return	string
	 */
	public function param_choose_stream($stream_id = FALSE)
	{
		$this->CI = get_instance();
		
		$this->CI->db->select('id, stream_name');
		$db_obj = $this->CI->db->get('data_streams');
		
		$streams = $db_obj->result();
		
		foreach ($streams as $stream):
		
			$choices[$stream->id] = $stream->stream_name;
		
		endforeach;
		
		// Is this an edit? and this has a field assignment
		// already? Then unfortunately you can't change the stream.
		// It would be pointless because we'd just have to wipe
		// the data anyways
		$extra = '';
				
		if( $this->CI->uri->segment(4) == 'edit' ):

			//$this->CI->db->select('COUNT(*) as total')->where('')->get('');
		
			return 'You cannot change a multiple relationship field stream once it has been assigned.';
		
			$extra = 'readonly';
		
		endif;
		
		return form_dropdown('choose_stream', $choices, $stream_id, $extra);
	}
}

/* End of file field.multiple.php */