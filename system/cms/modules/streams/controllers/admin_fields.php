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
class Admin_Fields extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'fields';

	// --------------------------------------------------------------------------   

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
	}

	// --------------------------------------------------------------------------   

    /**
     * List fields
     */
    function index()
    {     	
		// -------------------------------------
		// Get fields
		// -------------------------------------
		
		$this->data->fields = $this->fields_m->get_fields( $this->settings->item('records_per_page'), $this->uri->segment(5) );

		// -------------------------------------
		// Pagination
		// -------------------------------------

		$this->data->pagination = create_pagination(
										'admin/streams/fields/index',
										$this->fields_m->count_fields(),
										$this->settings->item('records_per_page'),
										5);

		// -------------------------------------

        $this->template->build('admin/fields/index', $this->data);
    }
  
	// --------------------------------------------------------------------------   

    /**
     * Create a new field
     */
	function add()
	{
		role_or_die('streams', 'admin_fields');
	
		// -------------------------------------
		// Field Type Assets
		// These are assets field types may
		// need when adding/editing fields
		// -------------------------------------
   		
   		$this->type->load_field_crud_assets();
   		
   		// -------------------------------------
        
        $this->data->method = 'new';
        
        //Prep the fields
		$this->data->field_types = field_types_array(TRUE);

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------

		// Add in the unique callback
		$this->fields_m->fields_validation[1]['rules'] .= '|unique_field_slug[new]';
		
		$this->streams_validation->set_rules( $this->fields_m->fields_validation  );
				
		foreach($this->fields_m->fields_validation as $field):
	
			$this->data->field->{$field['field']} = $this->input->post($field['field']);
	
		endforeach;

		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->streams_validation->run()):
	
			if( ! $this->fields_m->insert_field(
								$this->input->post('field_name'),
								$this->input->post('field_slug'),
								$this->input->post('field_type'),
								$this->input->post()
				) ):
			
				$this->session->set_flashdata('notice', lang('streams.save_field_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('streams.field_add_success'));	
			endif;
	
			redirect('admin/streams/fields');
		
		endif;

		// -------------------------------------
		// See if we need our param fields
		// -------------------------------------
		
		if($this->input->post('field_type') and $this->input->post('field_type')!=''):
		
			if(isset($this->type->types->{$this->input->post('field_type')})):
			
				// Get the type so we can use the custom params
				$this->data->current_type = $this->type->types->{$this->input->post('field_type')};
				
				// Get our standard params
				require_once(PYROSTEAMS_DIR.'libraries/Parameter_fields.php');
				
				$this->data->parameters = new Parameter_fields();
				
				$this->data->current_field->field_data = array();				
				
				if(isset($this->data->current_type->custom_parameters) and is_array($this->data->current_type->custom_parameters)):
				
					// Build items out of post data
					foreach($this->data->current_type->custom_parameters as $param):
					
						$this->data->current_field->field_data[$param] = $this->input->post($param);
					
					endforeach;
				
				endif;
			
			endif;
			
		endif;

		// -------------------------------------
		
		$this->template
				->append_metadata( js('slug.js', 'streams') )
				->append_metadata( js('fields.js', 'streams') )
				->build('admin/fields/form', $this->data);
	}

	// --------------------------------------------------------------------------
		
	/**
	 * Edit a field
	 */
	function edit()
	{
		role_or_die('streams', 'admin_fields');
	
		$field_id = $this->uri->segment('5');
		
		if( ! $this->data->current_field = $this->fields_m->get_field($field_id) ):
		
			// @todo language
			show_error("Invalid Field ID");
		
		endif;

		// -------------------------------------
		// Field Type Assets
		// These are assets field types may
		// need when adding/editing fields
		// -------------------------------------
   		
   		$this->type->load_field_crud_assets();

		// -------------------------------------
		
        $this->template->append_metadata(js('fields.js', 'streams'));
        
        $this->data->method = 'edit';
 
 		// -------------------------------------
		// Parameters
		// -------------------------------------
		
		// Get the type.
		// The form has not been submitted, we must use the 
		// field's current field type
		if(!isset($_POST['field_type'])):
		
			$this->data->current_type = $this->type->types->{$this->data->current_field->field_type};
			
		else:
		
			$this->data->current_type = $this->type->types->{$this->input->post('field_type')};
				
			// Overwrite items out of post data
			foreach($this->data->current_type->custom_parameters as $param):
			
				$this->data->current_field->field_data[$param] = $this->input->post($param);
			
			endforeach;
			
		endif;
		
 		// Load Paramaters in case we need 'em
		require_once(PYROSTEAMS_DIR.'libraries/Parameter_fields.php');		
		$this->data->parameters = new Parameter_fields();
       
        // Prep the fields
		$this->data->field_types = field_types_array( $this->type->types );

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------

		// Add in the unique callback
		$this->fields_m->fields_validation[1]['rules'] .= '|unique_field_slug['.$this->data->current_field->field_slug.']';
		
		$this->streams_validation->set_rules( $this->fields_m->fields_validation  );
				
		foreach($this->fields_m->fields_validation as $field):
		
			$key = $field['field'];
			
			if(!isset($_POST[$key])):
			
				$this->data->field->$key = $this->data->current_field->$key;
			
			else:
			
				$this->data->field->$key = $this->input->post($key);
			
			endif;
			
			$key = null;
		
		endforeach;
		
		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->streams_validation->run()):
	
			if( !$this->fields_m->update_field(
										$this->fields_m->get_field($field_id),
										$this->input->post()
									) ):
			
				$this->session->set_flashdata('notice', lang('streams.field_update_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('streams.field_update_success'));	
			
			endif;
	
			redirect('admin/streams/fields');
		
		endif;

		// -------------------------------------
		
		$this->template->build('admin/fields/form', $this->data);
	}

	// --------------------------------------------------------------------------   

	/**
	 * Delete a field
	 *
	 * @access	public
	 * @return	void
	 */	
	public function delete()
	{
		role_or_die('streams', 'admin_fields');
	
		$field_id = $this->uri->segment(5);
		
		if( ! $this->fields_m->delete_field($field_id) ):
		
			$this->session->set_flashdata('notice', lang('streams.field_delete_error'));	
		
		else:
		
			$this->session->set_flashdata('success', lang('streams.field_delete_success'));	
		
		endif;
	
		redirect('admin/streams/fields');
	}

	// --------------------------------------------------------------------------   

	/**
	 * Show field types with their
	 * authors and versions
	 */	
	function types()
	{	
		$this->template->build('admin/fields/list_types', $this->data);
	}

}

/* End of file admin_fields.php */