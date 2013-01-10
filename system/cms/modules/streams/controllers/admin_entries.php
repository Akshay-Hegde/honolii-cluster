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

	public function __construct()
	{
		parent::__construct();

		// -------------------------------------
		// Resources Load
		// -------------------------------------

  		$this->load->config('streams_core/streams');
  		$this->load->helper('streams');
  		$this->lang->load('streams_core/pyrostreams');    
		$this->load->library('streams_core/Type');	
	    $this->load->model(array('streams_core/fields_m', 'streams_core/streams_m', 'streams_core/row_m'));
		$this->load->library('form_validation');
       
		$this->data = new stdClass();
 		$this->data->types = $this->type->types;
 		
		// -------------------------------------
 		// Gather stream data.
		// -------------------------------------
 		// Each one of our functions requires
 		// us to be within a stream.
		// -------------------------------------
		
		$this->data->stream_id = $this->uri->segment(5);
		
		if ( ! $this->data->stream = $this->streams_m->get_stream($this->data->stream_id))
		{
			show_error(lang('streams.invalid_stream_id'));
		}

		check_stream_permission($this->data->stream);
		
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
		
		$this->data->stream_fields = new stdClass();
 		$this->data->stream_fields = $this->streams_m->get_stream_fields($this->data->stream_id);

 		if ( ! $this->data->stream_fields)
 		{
 			$this->data->stream_fields = new stdClass();
 		}

 		$this->data->stream_fields->id = new stdClass();
 		$this->data->stream_fields->created = new stdClass();
 		$this->data->stream_fields->updated = new stdClass();
 		$this->data->stream_fields->created_by = new stdClass();

  		$this->data->stream_fields->id->field_name 				= lang('streams.id');
		$this->data->stream_fields->created->field_name 		= lang('streams.created_date');
 		$this->data->stream_fields->updated->field_name 		= lang('streams.updated_date');
 		$this->data->stream_fields->created_by->field_name 		= lang('streams.created_by');

 		$offset = $this->uri->segment($offset_uri, 0);

		if ($this->data->stream->sorting == 'custom')
		{
			// As an added measure of obsurity, we are going to encrypt the
			// slug of the module so it isn't easily changed.
			$this->load->library('encrypt');

			// We need some variables to use in the sort.
			$this->template->append_metadata('<script type="text/javascript" language="javascript">var stream_id='.$this->data->stream->id.'; var stream_offset='.$offset.'; var streams_module="'.$this->encrypt->encode('streams').'";
				</script>');
			$this->template->append_js('streams/entry_sorting.js');
		}
  
 		// -------------------------------------
		// Get data
		// -------------------------------------

		$this->db->limit(Settings::get('records_per_page'), $offset);		
	
		$this->data->data = $this->streams_m->get_stream_data(
														$this->data->stream,
														$this->data->stream_fields, 
														Settings::get('records_per_page'),
														$this->uri->segment($offset_uri));
	
		// -------------------------------------
		// Pagination
		// -------------------------------------

		$this->data->pagination = create_pagination(
									$pagination_uri,
									$this->db->count_all($this->data->stream->stream_prefix.$this->data->stream->stream_slug ),
									Settings::get('records_per_page'),
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

		$extra = array(
			'return' 			=> 'admin/streams/entries/index/'.$this->data->stream->id,
			'success_message' 	=> $this->lang->line('streams.new_entry_success'),
			'failure_message'	=> $this->lang->line('streams.new_entry_error')
		);
		
		$fields = $this->fields->build_form($this->data->stream, 'new', false, false, false, array(), $extra);
	
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
	public function edit($row_id_uri)
	{
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$stream_id = $this->uri->segment(5);
		
		if ( ! $this->data->stream = $this->streams_m->get_stream($stream_id)) show_error(lang('streams.invalid_stream'));
	
 		// -------------------------------------
		// Get Row
		// -------------------------------------
		
		$row_id = $this->uri->segment(6);
		
		if ( ! $row = $this->row_m->get_row($row_id, $this->data->stream, false)) show_error(lang('streams.invalid_row'));

 		// -------------------------------------
		// Run Form
		// -------------------------------------

 		$this->load->library('streams_core/Fields');

		$extra = array(
			'return' 			=> 'admin/streams/entries/index/'.$this->data->stream->id,
			'success_message' 	=> $this->lang->line('streams.edit_entry_success'),
			'failure_message'	=> $this->lang->line('streams.edit_entry_error')
		);
		
		$fields = $this->fields->build_form($this->data->stream, 'edit', $row, false, false, array(), $extra);
	
		if ($fields === false)
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
						'mode'		=> 'edit');
			
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
		
		if( ! $this->row_m->delete_row($row_id, $this->data->stream))
		{
			$this->session->set_flashdata('notice', lang('streams.delete_entry_error'));	
		}
		else
		{
			$this->session->set_flashdata('success', lang('streams.delete_entry_success'));	
		}

		redirect('admin/streams/entries/index/'.$this->data->stream_id);
	}

}