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
class Admin extends Admin_Controller {

	/**
	 * The current active section
	 *
	 * @access protected
	 * @var string
	 */
	protected $section = 'streams';

	// --------------------------------------------------------------------------   

	/**
	 * Core Namespace
	 *
	 * This is the namespace that streams operates on.
	 * Set in the constructor.
	 * Default is usually 'streams'.
	 *
	 * @access 	protected
	 * @var 	string
	 */
	protected $namespace;

	// --------------------------------------------------------------------------   

    public function __construct()
    {
        parent::__construct();

        $this->load->driver('Streams');

        $this->load->config('streams/streams');

        $this->data = new stdClass();

        $this->load->helper('streams/streams');
	
		// Set our namespace
        $this->namespace = $this->config->item('streams:core_namespace');
	}
    
	// --------------------------------------------------------------------------   

    /**
     * List Streams
     *
     * Displays a list of streams in our core namespace.
     *
     * @access 	public
     * @return 	void
     */
    public function index()
    {
		// -------------------------------------
		// Get fields
		// -------------------------------------
		
		$this->db->where('is_hidden', 'no');
    	$this->data->streams = $this->streams->streams->get_streams($this->namespace, Settings::get('records_per_page'), $this->uri->segment(4));

		// -------------------------------------
		// Pagination
		// -------------------------------------

		$this->data->pagination = create_pagination(
										'admin/streams/index',
										$this->streams_m->total_streams($this->namespace),
										Settings::get('records_per_page'),
										4);

		// -------------------------------------
		// Build Page
		// -------------------------------------

        $this->template->build('admin/streams/index', $this->data);
    }
  
 	// --------------------------------------------------------------------------   

    /**
     * Traffic Cop for manage section
     */
    private function gather_stream_data()
    {
		$this->data->stream_id = $this->uri->segment(4);
		
		if ( ! $this->data->stream = $this->streams_m->get_stream($this->data->stream_id))
		{
			show_error(lang('streams:invalid_stream_id'));
		}
    }

	// --------------------------------------------------------------------------   

	/**
	 * Manage Stream
	 *
	 * Shows basic streams data and menu options
	 * for managing a stream.
	 *
     * @access 	public
     * @return 	void
	 */
	public function manage()
	{
		role_or_die('streams', 'admin_streams');
	
		$this->gather_stream_data();
		check_stream_permission($this->data->stream);
		
		// Get DB table name
		$this->data->table_name = $this->data->stream->stream_prefix.$this->data->stream->stream_slug;
		
		// Get the table data
		$info = $this->db->query("SHOW TABLE STATUS LIKE '{$this->db->dbprefix($this->data->table_name)}'")->row();
		
		// Get the size of the table
		$this->load->helper('number');
		$this->data->total_size = byte_format($info->Data_length);
		
		$this->data->meta = $this->streams->streams->get_stream_metadata($this->data->stream, $this->namespace);

		$this->set_perm_lang();
		
		$this->template->build('admin/streams/manage', $this->data);
	}

	// --------------------------------------------------------------------------   

	// I know this is goofy
	private function set_perm_lang()
	{
		// Let's hijack the word 'Permissions'. Just so we don't have to bother our
		// translators.
		require_once(APPPATH.'modules/permissions/details.php');
		$perm = new Module_Permissions();
		$perm_info = $perm->info();
		$perm_lang = (isset($perm_info['name'][CURRENT_LANGUAGE])) ? $perm_info['name'][CURRENT_LANGUAGE] : $perm_info['name']['en'];
		$this->template->set('perm_lang', $perm_lang);
		return $perm_lang;
	}

	// --------------------------------------------------------------------------   

    /**
     * Choose which items to view
     */
 	public function view_options()
 	{
		role_or_die('streams', 'admin_streams');

  		$this->gather_stream_data();
  		check_stream_permission($this->data->stream);

  		// -------------------------------------
		// Process Data
		// ------------------------------------

		if( $this->input->post('view_options') ):
		
			$opts = $this->input->post('view_options');
		
			$update_data['view_options'] = serialize($opts);
			
			$this->db->where('id', $this->data->stream_id);
			
			if( !$this->db->update(STREAMS_TABLE, $update_data) ):
			
				$this->session->set_flashdata('notice', lang('streams:view_options_update_error'));
				
			else:
			
				$this->session->set_flashdata('success', lang('streams:view_options_update_success'));
			
			endif;
			
			redirect('admin/streams/manage/'.$this->data->stream_id);
		
		endif;

		// -------------------------------------
		// Get Stream Fields
		// ------------------------------------
		
		// @todo - do we really need the 1000, 0 here? Did I take care of that? Check it out!
		$this->data->stream_fields = $this->streams_m->get_stream_fields($this->data->stream_id, 1000, 0);

		// -------------------------------------
		// Build Pages
		// -------------------------------------
		
        $this->template->build('admin/streams/view_options', $this->data);
 	}
 
	// --------------------------------------------------------------------------   

 	/**
 	 * Manage roles for a certain stream.
 	 *
 	 * @access 	public
 	 * @return 	void
 	 */
 	public function permissions()
 	{
 		role_or_die('streams', 'admin_streams');

  		$this->gather_stream_data();
  		check_stream_permission($this->data->stream);

  		$this->set_perm_lang();

		// Get our groups.
		$this->data->groups = $this->db
									->select('*, groups.id as group_id')
									->from('groups, permissions')
									->where('groups.id', '`'.SITE_REF.'_permissions`.`group_id`', false)
									->where('permissions.module', 'streams')
									->where('groups.name !=', 'admin')->get()->result();

		// Set permissions
		if (isset($this->data->stream->permissions))
		{
			$permissions = @unserialize($this->data->stream->permissions);

			if ( ! is_array($permissions)) $permissions = array();
		}
		else
		{
			$permissions = array();
		}
		$this->template->set('permissions', $permissions);

		// Did we have an edit? If so let's just save it to the db
		// and get out of here!
		if ($this->input->post('edited') == 'y')
		{
			$groups = ( ! is_array($this->input->post('groups'))) ? array() : $this->input->post('groups');

			// Sorry about serializing this. It's not what I would do
			// in 2013, but 2009 me thinks it's awesome.
			$this->db->limit(1)->where('id', $this->data->stream->id)->update(STREAMS_TABLE, array('permissions' => serialize($groups)));

			$this->session->set_flashdata('success', lang('permissions:message_group_saved_success'));
			redirect('admin/streams/manage/'.$this->data->stream->id);
		}

        $this->template
        		->build('admin/streams/permissions', $this->data);
 	}

	// --------------------------------------------------------------------------   

    /**
     * New stream
     *
     * @access	public
     * @return	void
     */
	public function add()
	{
		role_or_die('streams', 'admin_streams');

		// -------------------------------------
		// Misc Setup
		// -------------------------------------
		
        $this->data->method = 'new';
        
		// -------------------------------------
		// Validation & Setup
		// -------------------------------------

		$this->streams_m->streams_validation[1]['rules'] .= '|stream_unique[new]';
	
		$this->streams_m->streams_validation[] = array(
			'field'	=> 'menu_path',
			'label' => 'lang:streams:menu_path',
			'rules'	=> 'trim|max_length[60]'
		);

		$this->form_validation->set_rules($this->streams_m->streams_validation);

		$this->data->stream = new stdClass();
				
		foreach ($this->streams_m->streams_validation as $field)
		{
			$key = $field['field'];

			// For some reason, set_value() isn't working.
			$this->data->stream->$key = $this->input->post($key);
			
			$key = null;
		}
	
		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run())
		{
			$extra = array();

			// If they posted a menu path, let's get that in there.
			$extra['menu_path'] = $this->input->post('menu_path');

			// To start out with, we are going to say that everyone
			// who has access to streams has access to this stream.
			// Admins can tweak it later.
            $groups = $this->db
                            ->select('*, groups.id as group_id')
                            ->from('groups, permissions')
                            ->where('groups.id', 'permissions.group_id')
                            ->where('permissions.module', 'streams')
                            ->where('groups.name !=', 'admin')->get()->result();

            $groups_arr = array();
            foreach ($groups as $g)
            {
                $groups_arr[] = $g->group_id;
            }
            $extra['permissions'] = serialize($groups_arr);			

			if ( ! $this->streams_m->create_new_stream(
										$this->input->post('stream_name'),
										$this->input->post('stream_slug'),
										$this->input->post('stream_prefix'),
										$this->config->item('streams:core_namespace'),
										$this->input->post('about'),
										$extra
								) )
			{
				$this->session->set_flashdata('notice', lang('streams:create_stream_error'));	
			}
			else
			{
				$this->session->set_flashdata('success', lang('streams:create_stream_success'));	
			}
	
			redirect('admin/streams');
		}

		// -------------------------------------
		
        $this->template
        		->append_js('module::slug.js')
        		->append_js('module::new_stream.js')
        		->build('admin/streams/form', $this->data);
	}

	// --------------------------------------------------------------------------   

    /**
     * Edit stream
     */
	public function edit()
	{
		role_or_die('streams', 'admin_streams');

		// -------------------------------------
		// Assets
		// -------------------------------------
		
        $this->data->method = 'edit';
        
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$stream_id = $this->uri->segment(4);
		
		if ( ! $this->data->stream = $this->streams_m->get_stream($stream_id))
		{
			show_error("Invalid Stream");
		}

		check_stream_permission($this->data->stream);
		
 		// -------------------------------------
		// Get Columns & Put into Array
		// -------------------------------------
       
       	$fields_obj = $this->streams_m->get_stream_fields($stream_id);
       	
        $this->data->fields = array();
        
        if( $fields_obj ):
        
	        foreach( $fields_obj as $field ):
	        	        
				$this->data->fields[$field->field_slug] = $field->field_name;
	        			        
	        endforeach;
        
        endif;
       
		// -------------------------------------
		// Validation & Setup
		// -------------------------------------

		$this->streams_m->streams_validation[1]['rules'] .= '|stream_unique['.$this->data->stream->stream_slug.']';

		$this->streams_m->streams_validation[] = array(
			'field'	=> 'menu_path',
			'label' => 'lang:streams:menu_path',
			'rules'	=> 'trim|max_length[60]'
		);
		
		$this->form_validation->set_rules($this->streams_m->streams_validation);
				
		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run()):
	
			if( ! $this->streams_m->update_stream($stream_id, $this->input->post())):
			
				$this->session->set_flashdata('notice', lang('streams:stream_update_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('streams:stream_update_success'));	
			
			endif;
	
			redirect('admin/streams/manage/'.$stream_id);
		
		endif;

		// -------------------------------------
		
		$this->template
				->append_js('module::new_stream.js')
				->build('admin/streams/form', $this->data);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete a stream
	 */
	public function delete()
	{
		role_or_die('streams', 'admin_streams');

		$stream_id = $this->uri->segment(4);
		
		if ( ! $this->data->stream = $this->streams_m->get_stream($stream_id))
		{
			show_error("Invalid Stream");
		}

		check_stream_permission($this->data->stream);

		// -------------------------------------
		// Action
		// -------------------------------------

		if( $this->input->post('action') ):
		
			$action = $this->input->post('action');
			
			if( $action == 'cancel' ):
			
				redirect('admin/streams/manage/index/'.$this->data->stream->id);
			
			else:
			
				if( ! $this->streams_m->delete_stream( $this->data->stream ) ):
				
					$this->session->set_flashdata('notice', lang('streams:stream_delete_error'));	
				
				else:
				
					$this->session->set_flashdata('success', lang('streams:stream_delete_success'));	
				
				endif;
			
				redirect('admin/streams');
			
			endif;
		
		endif;

		// -------------------------------------
		// Build Page
		// -------------------------------------
		
		$this->data->total_fields = $this->streams_m->count_stream_entries(
																$this->data->stream->stream_slug,
																$this->data->stream->stream_namespace
															);
	
		// -------------------------------------
		// Build Page
		// -------------------------------------

        $this->template->build('admin/streams/confirm_delete', $this->data);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * List out fields assigned to a stream
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function assignments()
	{
		role_or_die('streams', 'admin_streams');

		$this->gather_stream_data();
		check_stream_permission($this->data->stream);

		$extra = array(
			'return' 			=> 'admin/streams/entries/index/'.$this->data->stream->id,
			'success_message' 	=> $this->lang->line('streams:new_entry_success'),
			'failure_message'	=> $this->lang->line('streams:new_entry_error')
		);

		$extra['title'] = '<a href="admin/streams/manage/'.$this->data->stream->id.'">'.$this->data->stream->stream_name.'</a> &rarr; '.lang('streams:field_assignments');

		if ( ! $this->db->select('id')->limit(1)->where('field_namespace', $this->data->stream->stream_namespace)->get('data_fields')->row())
		{
			$extra['no_assignments_message'] = lang('streams:start.no_fields').' '.anchor('admin/streams/fields/add', lang('streams:start.add_one'));
		}

		$extra['buttons']	= array(
			array(
				'label' 	=> lang('global:edit'),
				'url'		=> 'admin/streams/edit_assignment/'.$this->data->stream->id.'/-assign_id-',
				'confirm'	=> false
			),
			array(
				'label' 	=> lang('global:delete'),
				'url'		=> 'admin/streams/remove_assignment/'.$this->data->stream->id.'/-assign_id-',
				'confirm'	=> true
			)
		);

		$this->streams->cp->assignments_table(
								$this->data->stream,
								$this->config->item('streams:core_namespace'),
								Settings::get('records_per_page'),
								'admin/streams/assignments/'.$this->data->stream->id,
								true,
								$extra);
	}

	// --------------------------------------------------------------------------
		
	/**
	 * Add a new field to a stream
	 */
	public function new_assignment()
	{
 		role_or_die('streams', 'admin_streams');

		$this->gather_stream_data();
		check_stream_permission($this->data->stream);

		// -------------------------------------
		// Get number of fields total
		// -------------------------------------
		
		$this->data->total_existing_fields = $this->db->count_all(FIELDS_TABLE);

		// -------------------------------------
	
        $this->data->method = 'new';
        
        $this->data->title_column_status = FALSE;
        
		if ($this->_manage_fields() == 'no_fields') {
			return;
		}
		
		// Get fields that are available
		$this->data->available_fields = array(null => null);
		
		if ($this->data->fields)
		{
			foreach ($this->data->fields as $field)
			{
				if ( ! in_array($field->id, $this->data->in_use))
				{
					$this->data->available_fields[$field->id] = $field->field_name;
				}
			}
		}

		// Dummy row id
		$this->data->row = new stdClass();
		$this->data->row->field_id = null;
		
		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run())
		{
			if ( ! $this->streams_m->add_field_to_stream(
										$this->input->post('field_id'),
										$this->data->stream_id,
										$this->input->post()
									))
			{
				$this->session->set_flashdata('notice', lang('streams:stream_field_ass_add_error'));	
			}
			else
			{
				$this->session->set_flashdata('success', lang('streams:stream_field_ass_add_success'));	
			}
	
			redirect('admin/streams/assignments/'.$this->data->stream_id);
		}

		// -------------------------------------
		// Build Page
		// -------------------------------------
		
		$this->template->build('admin/assignments/form', $this->data);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Edit a field assignment
	 */
	public function edit_assignment()
	{	
		role_or_die('streams', 'admin_streams');

		$this->gather_stream_data();
		check_stream_permission($this->data->stream);

		// -------------------------------------
		// Get number of fields total
		// -------------------------------------
		
		$this->data->total_existing_fields = $this->db->count_all(FIELDS_TABLE);

		// -------------------------------------
		// Get Assignment
		// -------------------------------------
		
		$id = $this->uri->segment(5);
		
		if( !is_numeric($id) ) show_error(lang('streams:invalid_id'));
		
		$this->db->limit(1)->where('id', $id);
		
		$db_obj = $this->db->get(ASSIGN_TABLE);
		
		if( $db_obj->num_rows() == 0 ) show_error(lang('streams:invalid_id'));
		
		$this->data->row = $db_obj->row();
		
		// -------------------------------------
		// Field
		// -------------------------------------

		$field = $this->fields_m->get_field($this->data->row->field_id);

		// -------------------------------------

        $this->data->method = 'edit';
        
		$this->_manage_fields();
						
		if($field->field_slug == $this->data->stream->title_column):
		
			$this->data->title_column_status = TRUE;
		
		else:
		
			$this->data->title_column_status = FALSE;
		
		endif;

		// Get fields that are available
		$this->data->available_fields = array();
		$this->data->all_fields = array();
		
		foreach($this->data->fields as $field):
		
			$this->data->all_fields[$field->id] = $field->field_name;
		
			if( !in_array($field->id, $this->data->in_use)):
			
				$this->data->available_fields[$field->id] = $field->field_name;
			
			endif;
		
		endforeach;
				
		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run()):
	
			if( !$this->fields_m->edit_assignment(
										$this->data->row->id,
										$this->data->stream,
										$this->fields_m->get_field($this->data->row->field_id),
										$this->input->post()
									) ):
			
				$this->session->set_flashdata('notice', lang('streams:stream_field_ass_upd_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('streams:stream_field_ass_upd_success'));	
			
			endif;
	
			redirect('admin/streams/assignments/'.$this->data->stream_id);
		
		endif;

		// -------------------------------------
		// Build Page
		// -------------------------------------
		
		$this->template->build('admin/assignments/form', $this->data);
	}

	// --------------------------------------------------------------------------
	
 	/**
 	 * Remove a field assignment
 	 */
 	public function remove_assignment()
 	{ 	
 		role_or_die('streams', 'admin_streams');

 		$this->gather_stream_data();
 		check_stream_permission($this->data->stream);

 		$field_assign_id = $this->uri->segment(5);
 
  		// -------------------------------------
		// Get Assignment
		// -------------------------------------

		$obj = $this->db->limit(1)->where('id', $field_assign_id)->get(ASSIGN_TABLE);
		
		if( $obj->num_rows() == 0 ) show_error(lang('streams:cannot_find_assign'));
		
		$assignment = $obj->row();
 		
 		// -------------------------------------
		// Get field
		// -------------------------------------
		
		$field = $this->fields_m->get_field( $assignment->field_id );
		 		
		// -------------------------------------
		// Remove from table
		// -------------------------------------
		
		if( ! $this->streams_m->remove_field_assignment($assignment, $field, $this->data->stream)  ):

			$this->session->set_flashdata('notice', lang('streams:remove_field_error'));
		
		else:

			$this->session->set_flashdata('success', lang('streams:remove_field_success'));
		
		endif;

		redirect('admin/streams/assignments/'.$this->data->stream_id);
  	}

	// --------------------------------------------------------------------------

	private function _manage_fields()
	{
		// -------------------------------------
		// Assets & Data
		// -------------------------------------
		
        // Get list of available fields
        $this->data->fields = $this->fields_m->get_fields($this->config->item('streams:core_namespace'));
        
        // No fields? Show a message.       
        if (count($this->data->fields) == 0)
   		{
   			$this->template->build('admin/streams/no_fields_to_add', $this->data);   			
   			return 'no_fields';
     	}
        
        // Get an array of field IDs that are already in use
        // So we can disable them in the drop down
        $obj = $this->db->where('stream_id', $this->data->stream_id)->get(ASSIGN_TABLE);
        
        $this->data->in_use = array();
        
        foreach ($obj->result() as $item)
        {
        	$this->data->in_use[] = $item->field_id;
        }
        
		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$validation = array(
			array(
				'field'	=> 'field_id',
				'label' => 'Field',
				'rules'	=> 'trim|required'
			),
			array(
				'field'	=> 'is_required',
				'label' => 'Is Required',
				'rules'	=> 'trim'
			),
			array(
				'field'	=> 'is_unique',
				'label' => 'Is Unique',
				'rules'	=> 'trim'
			),
			array(
				'field'	=> 'instructions',
				'label' => 'Instructions',
				'rules'	=> 'trim'
			)
		);
		
		$this->form_validation->set_rules($validation);

		$this->data->values = new stdClass();
		
		foreach($validation as $valid):
		
			$key = $valid['field'];
			
			// Get the data based on the method
			if( $this->data->method == 'edit' ):
			
				$current_value = $this->data->row->$key;
			
			else:
			
				$current_value = $this->input->post($key);
			
			endif;
			
			// Set the values
			if( $key == 'is_required' or $key == 'is_unique' ):
			
				if( $current_value == 'yes' ):
				
					$this->data->values->$key = true;
					
				else:
				
					$this->data->values->$key = false;
				
				endif;
			
			else:
			
				$this->data->values->$key = set_value($key, $current_value);
		
			endif;
			
			$key = null;
		
		endforeach;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Streams Backup
	 *
	 * Backs up and downloads a GZip file of the stream table
	 */
	public function backup()
	{
		role_or_die('streams', 'admin_streams');

  		$this->gather_stream_data();
  		check_stream_permission($this->data->stream);

		$this->load->dbutil();

		$table_name = $this->data->stream->stream_prefix.$this->data->stream->stream_slug;
		
		$filename = $table_name.'_backup_'.date('Y-m-d');

		$backup_prefs = array(
	        'tables'      => array($this->db->dbprefix($table_name)),
			'format'      => 'zip',
	        'filename'    => $filename.'.sql',
	        'add_drop'    => true,
	        'add_insert'  => true,
	        'newline'     => "\n"
		);
		
		$backup = $this->dbutil->backup($backup_prefs); 

		$this->load->helper('download');
		
		force_download($filename.'.zip', $backup);
	}

}