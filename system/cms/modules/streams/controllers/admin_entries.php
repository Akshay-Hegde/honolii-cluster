<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Admin
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Admin_Entries extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'streams';

    function __construct()
    {
        parent::__construct();
                
        // If you are going to admin fields you gotta 
        // pass the test!
		role_or_die('streams', 'admin_fields');

  		$this->load->config('streams/streams');
  		$this->lang->load('streams/pyrostreams');    
        $this->load->helper('streams/streams');
        
        streams_constants();
        admin_resources();
        
 		$this->data->types = $this->type->types;
 		
 		// Gather stream data.
 		// Each one of our functions requires we are within a stream.
		$this->data->stream_id = $this->uri->segment(5);
		
		if(!$this->data->stream = $this->streams_m->get_stream($this->data->stream_id)):
		
			show_error("Invalid Stream ID");
		
		endif;
	}

	// --------------------------------------------------------------------------   

	/**
	 * Entries Index
	 *
	 * @access	private
	 * @return	void
	 */
	public function index()
	{
		$offset_uri = 6;
		$pagination_uri = 'admin/streams/entries/index/'.$this->data->stream->id;
	
 		// -------------------------------------
		// Get fields for headers and add specials
		// -------------------------------------
		
 		$this->data->stream_fields = $this->streams_m->get_stream_fields($this->data->stream_id);
 		
  		$this->data->stream_fields->id->field_name 				= "ID";
		$this->data->stream_fields->created->field_name 		= "Created On";
 		$this->data->stream_fields->updated->field_name 		= "Updated On";
 		$this->data->stream_fields->created_by->field_name 		= "Created By";

 		$offset = $this->uri->segment($offset_uri, 0);

		if( $this->data->stream->sorting == 'custom' ):

			// We need some variables to use in the sort. I guess.
			$this->template->append_metadata('<script type="text/javascript" language="javascript">var stream_id='.$this->data->stream->id.';var stream_offset='.$offset.';</script>');
		
			// We want to sort this shit
		    $this->template->append_metadata( js('entry_sorting.js', 'streams') );
		    		      
			// Comeon' Livequery! You're goin' in!
			$this->template->append_metadata( js('jquery.livequery.js', 'streams') );
		
		endif;
  
 		// -------------------------------------
		// Get data
		// -------------------------------------

		$this->db->limit($this->settings->item('records_per_page'), $offset);		
	
		$this->data->data = $this->streams_m->get_stream_data(
														$this->data->stream,
														$this->data->stream_fields, 
														$this->settings->item('records_per_page'),
														$this->uri->segment($offset_uri));
	
		// -------------------------------------
		// Pagination
		// -------------------------------------

		$this->data->pagination = create_pagination(
									$pagination_uri,
									$this->db->count_all(STR_PRE.$this->data->stream->stream_slug ),
									$this->settings->item('records_per_page'),
									6);

		// -------------------------------------
		// Build Pages
		// -------------------------------------
		
        $this->template->build('admin/entries/index', $this->data);
	}

	// --------------------------------------------------------------------------   

	/**
	 * Add an entry
	 */
	function add()
	{
		$this->load->library('streams/Fields');
	
		if( $this->fields->build_form( $this->data, "new" ) === FALSE ):
        
        	$this->template->build('admin/entries/no_fields', $this->data);
				
		endif;
	}

	// --------------------------------------------------------------------------   

	/**
	 * Edit an entry
	 */
	function edit()
	{			
		$this->_edit_row(6);	
	}

	// --------------------------------------------------------------------------   

	/**
	 * Shared Edit Row Function
	 *
	 * @access	private
	 * @param	string
	 */
	function _edit_row($row_id_uri)
	{
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$stream_id = $this->uri->segment(5);
		
		if(!$this->data->stream = $this->streams_m->get_stream($stream_id)) show_error("Invalid Stream");
	
 		// -------------------------------------
		// Get Row
		// -------------------------------------
		
		$row_id = $this->uri->segment($row_id_uri);
		
		if(!$row = $this->row_m->get_row($row_id, $this->data->stream, FALSE ) ) show_error("Invalid row");

 		// -------------------------------------
		// Run Form
		// -------------------------------------

 		$this->load->library('streams/Fields');
 		
 		$this->data->row_edit_id = $row->id;
 		
		$this->fields->build_form($this->data, "edit", $row);	
	}

	// --------------------------------------------------------------------------
	
	/**
	 * View row
	 */
	public function view()
	{
		$row_uri_segment = 6;
			
		// -------------------------------------
		// Get Data
		// -------------------------------------

		$this->data->stream_fields = $this->streams_m->get_stream_fields($this->data->stream_id);
		
		$row_id = $this->uri->segment($row_uri_segment, FALSE);
		
		if(!$row_id || !is_numeric($row_id)) show_error("Invalid ID");
		
		// Get the row
		$this->data->row = $this->row_m->get_row( $row_id, $this->data->stream );
				
		// -------------------------------------
		// Build Page
		// -------------------------------------
		
		$this->template->build('admin/entries/view', $this->data);	
	}

	// --------------------------------------------------------------------------

	/**
	 * Delete row
	 */	
	function delete()
	{
		$row_uri_segment = 6;
	
		$row_id = $this->uri->segment($row_uri_segment);
		
		if( ! $this->streams_m->delete_row( $row_id, $this->data->stream) ):

			$this->session->set_flashdata('notice', lang('streams.delete_entry_error'));	

		else:

			$this->session->set_flashdata('success', lang('streams.delete_entry_success'));	
		
		endif;

		redirect('admin/streams/entries/index/'.$this->data->stream_id);
	}

}

/* end admin_entries.php */