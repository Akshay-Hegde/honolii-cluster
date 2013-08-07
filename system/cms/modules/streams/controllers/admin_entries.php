<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Admin
 *
 * @package		PyroStreams
 * @author		PyroCMS
 * @copyright	Copyright (c) 2011 - 2013, PyroCMS
 */
class Admin_Entries extends Admin_Controller {

	/**
	 * The current active section
	 *
	 * @access protected
	 * @var string
	 */
	protected $section = 'streams';

	// --------------------------------------------------------------------------   

    public function __construct()
    {
        parent::__construct();
        
		$this->load->driver('Streams');
		$this->load->config('streams/streams');
		$this->load->helper('streams/streams');

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
			show_error(lang('streams:invalid_stream_id'));
		}

		check_stream_permission($this->data->stream);
	}

	// --------------------------------------------------------------------------   

	/**
	 * Entries Index
	 *
	 * View a list of entries.
	 *
	 * @access	private
	 * @return	void
	 */
	public function index()
	{
		$offset_uri = 6;
		$pagination_uri = 'admin/streams/entries/index/'.$this->data->stream->id;
	
 		// -------------------------------------
		// Get Streams Entries Table
		// -------------------------------------

		$extra = array(
				'title'		=> $this->data->stream->stream_name,
				'buttons'	=> array(
					array(
						'label' 	=> lang('global:edit'),
						'url'		=> 'admin/streams/entries/edit/'.$this->data->stream->id.'/-entry_id-',
						'confirm'	=> false
					),
					array(
						'label' 	=> lang('global:delete'),
						'url'		=> 'admin/streams/entries/delete/'.$this->data->stream->id.'/-entry_id-',
						'confirm'	=> true
					)
				)
			);

		$this->streams->cp->entries_table(
								$this->data->stream->stream_slug,
								$this->data->stream->stream_namespace,
								Settings::get('records_per_page'),
								$pagination_uri,
								true,
								$extra);

	}

	// --------------------------------------------------------------------------   

	/**
	 * Add an entry
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function add()
	{
		$extra = array(
			'return' 			=> 'admin/streams/entries/index/'.$this->data->stream->id,
			'success_message' 	=> $this->lang->line('streams:new_entry_success'),
			'failure_message'	=> $this->lang->line('streams:new_entry_error')
		);

		// Title
		$extra['title'] = '<a href="admin/streams/entries/index/'.$this->data->stream->id.'">'.$this->data->stream->stream_name.'</a> &rarr; '.lang('streams:new_entry');

		$this->streams->cp->entry_form($this->data->stream, $this->config->item('streams:core_namespace'), 'new', null, true, $extra);		
	}

	// --------------------------------------------------------------------------   

	/**
	 * Shared Edit Row Function
	 *
	 * @access	public
	 * @param	string
	 */
	public function edit()
	{
		$extra = array(
			'return' 			=> 'admin/streams/entries/index/'.$this->data->stream->id,
			'success_message' 	=> $this->lang->line('streams:edit_entry_success'),
			'failure_message'	=> $this->lang->line('streams:edit_entry_error')
		);

		// Title
		$extra['title'] = '<a href="admin/streams/entries/index/'.$this->data->stream->id.'">'.$this->data->stream->stream_name.'</a> &rarr; '.lang('streams:edit_entry');

		$entry_id = $this->uri->segment(6);

		if ( ! $entry_id)
		{
			show_error('streams:invalid_row');
		}

		$this->streams->cp->entry_form($this->data->stream, $this->config->item('streams:core_namespace'), 'edit', $entry_id, true, $extra);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * View Entry
	 *
	 * @access	public
	 * @param	string
	 */
	public function view()
	{
		$row_uri_segment = 6;
			
		// -------------------------------------
		// Get Data
		// -------------------------------------

		$this->data->stream_fields = $this->streams_m->get_stream_fields($this->data->stream_id);
		
		$row_id = $this->uri->segment($row_uri_segment, false);
		
		if ( ! $row_id or ! is_numeric($row_id))
		{
			show_error(lang('streams:invalid_id'));
		}

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
	public function delete()
	{
		$row_uri_segment = 6;
	
		$row_id = $this->uri->segment($row_uri_segment);
		
		if( ! $this->row_m->delete_row($row_id, $this->data->stream))
		{
			$this->session->set_flashdata('notice', lang('streams:delete_entry_error'));	
		}
		else
		{
			$this->session->set_flashdata('success', lang('streams:delete_entry_success'));	
		}

		redirect('admin/streams/entries/index/'.$this->data->stream_id);
	}

}