<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Admin
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Admin_Entries extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'streams';

	// --------------------------------------------------------------------------   

    function __construct()
    {
        parent::__construct();
        
 		// -------------------------------------
		// Resources Load
		// -------------------------------------

  		$this->load->config('streams_core/streams');
  		$this->lang->load('streams_core/pyrostreams');    
		$this->load->library('streams_core/Type');	
	    $this->load->model(array('streams_core/fields_m', 'streams_core/streams_m', 'streams_core/row_m'));
		$this->load->library('form_validation');
		$this->load->library('streams_core/Streams_validation');	
       
 		$this->data->types = $this->type->types;
 		
		// -------------------------------------
 		// Gather stream data.
		// -------------------------------------
 		// Each one of our functions requires
 		// us to be within a stream.
		// -------------------------------------
		
		$this->data->stream_id = $this->uri->segment(5);
		
		if(!$this->data->stream = $this->streams_m->get_stream($this->data->stream_id)):
		
			show_error(lang('streams.invalid_stream_id'));
		
		endif;
		
		// -------------------------------------
 		// Makes sure stream is in the core
 		// namespace
		// -------------------------------------

		// @todo
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
 		
  		$this->data->stream_fields->id->field_name 				= lang('streams.id');
		$this->data->stream_fields->created->field_name 		= lang('streams.created_date');
 		$this->data->stream_fields->updated->field_name 		= lang('streams.updated_date');
 		$this->data->stream_fields->created_by->field_name 		= lang('streams.created_by');

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
									$this->db->count_all($this->data->stream->stream_prefix.$this->data->stream->stream_slug ),
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
		$this->load->library('streams_core/Fields');
		
		$this->data->success_message 	= $this->lang->line("streams.new_entry_success");
		$this->data->failure_message 	= $this->lang->line("streams.new_entry_error");
		
		$fields = $this->fields->build_form($this->data->stream, "new");
	
		if ($fields === FALSE)
        {
        	$this->template->build('admin/entries/no_fields', $this->data);
		}		
		else
		{
			$data = array(
						'fields' 	=> $fields,
						'stream'	=> $this->data->stream,
						'mode'		=> 'new');
			
			$form = $this->load->view('admin/partials/streams/form', $data, TRUE);
		
			$this->data->content = $form;
		
			$this->template->build('admin/partials/blank_section', $this->data);
		}
	}

	// --------------------------------------------------------------------------   

	/**
	 * Shared Edit Row Function
	 *
	 * @access	private
	 * @param	string
	 */
	function edit($row_id_uri)
	{
		$this->data->success_message 	= $this->lang->line("streams.edit_entry_success");
		$this->data->failure_message 	= $this->lang->line("streams.edit_entry_error");

		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$stream_id = $this->uri->segment(5);
		
		if(!$this->data->stream = $this->streams_m->get_stream($stream_id)) show_error(lang('streams.invalid_stream'));
	
 		// -------------------------------------
		// Get Row
		// -------------------------------------
		
		$row_id = $this->uri->segment(6);
		
		if(!$row = $this->row_m->get_row($row_id, $this->data->stream, FALSE ) ) show_error(lang('streams.invalid_row'));

 		// -------------------------------------
		// Run Form
		// -------------------------------------

 		$this->load->library('streams_core/Fields');
 		
 		$this->data->row_edit_id = $row->id;
 		
		$fields = $this->fields->build_form($this->data->stream, 'edit', $row);
	
		if ($fields === FALSE)
        {
        	// @todo - message about not finding an entry
        	$this->template->build('admin/entries/no_fields', $this->data);
		}		
		else
		{
			$data = array(
						'fields' 	=> $fields,
						'stream'	=> $this->data->stream,
						'entry'		=> $row,
						'mode'		=> 'new');
			
			$form = $this->load->view('admin/partials/streams/form', $data, TRUE);
		
			$this->data->content = $form;
		
			$this->template->build('admin/partials/blank_section', $this->data);
		}

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
		
		// @todo - languagize
		if(!$row_id || !is_numeric($row_id)) show_error("Invalid ID");
		
		// Get the row
		$this->data->row = $this->row_m->get_row($row_id, $this->data->stream);
				
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
		
		if( ! $this->streams_m->delete_row($row_id, $this->data->stream)):

			$this->session->set_flashdata('notice', lang('streams.delete_entry_error'));	

		else:

			$this->session->set_flashdata('success', lang('streams.delete_entry_success'));	
		
		endif;

		redirect('admin/streams/entries/index/'.$this->data->stream_id);
	}

}