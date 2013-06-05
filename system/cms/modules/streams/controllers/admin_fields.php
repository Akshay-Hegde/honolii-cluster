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
class Admin_Fields extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'fields';

	// --------------------------------------------------------------------------   

	/**
	 * Construct
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// If you are going to admin fields you need to
		// be authorized for admin_fields.
		role_or_die('streams', 'admin_fields');

		$this->load->config('streams/streams');
		$this->load->driver('Streams');

		$this->data = new stdClass();
 		$this->data->types = $this->type->types;
	}

	// --------------------------------------------------------------------------   

	/**
	 * Index
	 *
	 * List fields (using the Streams API fields_table
	 * functionality).
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function index()
	{
		$extra = array();

		$extra['title'] = lang('streams:fields');
		$extra['buttons'] = array(
			array(
				'label'		=> lang('global:edit'),
				'url'		=> 'admin/streams/fields/edit/-field_id-'
			),
			array(
				'label'		=> lang('global:delete'),
				'url'		=> 'admin/streams/fields/delete/-field_id-',
				'confirm'	=> true,
			)
		);

		$this->streams->cp->fields_table(
				$this->config->item('streams:core_namespace'),
				Settings::get('records_per_page'),
				'admin/streams/fields/index', true, $extra);
	}

	// --------------------------------------------------------------------------   

	/**
     * Create a new field
     */
	public function add()
	{
		role_or_die('streams', 'admin_fields');
	
		// -------------------------------------
		// Field Type Assets
		// -------------------------------------
		// These are assets field types may
		// need when adding/editing fields
		// -------------------------------------

		$this->type->load_field_crud_assets();

		// -------------------------------------

		$this->data->method = 'new';

		//Prep the fields
		$this->data->field_types = $this->type->field_types_array();

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------

		// Add in the unique callback
		$this->fields_m->fields_validation[1]['rules'] .= '|unique_field_slug[new]';
		
		$this->form_validation->set_rules($this->fields_m->fields_validation);
		
		$this->data->field = new stdClass();

		foreach ($this->fields_m->fields_validation as $field)
		{
			$this->data->field->{$field['field']} = $this->input->post($field['field']);
		}

		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run()):
	
			if( ! $this->fields_m->insert_field(
								$this->input->post('field_name'),
								$this->input->post('field_slug'),
								$this->input->post('field_type'),
								$this->config->item('streams:core_namespace'),
								$this->input->post()
				) ):
			
				$this->session->set_flashdata('notice', lang('streams:save_field_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('streams:field_add_success'));	
			endif;
	
			redirect('admin/streams/fields');
		
		endif;

		// -------------------------------------
		// See if we need our param fields
		// -------------------------------------
		
		if ($this->input->post('field_type') and $this->input->post('field_type')!=''):
		
			if (isset($this->type->types->{$this->input->post('field_type')})):
			
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
		// Run field setup events
		// -------------------------------------

		$this->fields->run_field_setup_events(null, null, null);

		// -------------------------------------
		
		$this->template
			->append_js('module::slug.js')
			->append_js('module::fields.js')
			->build('admin/fields/form', $this->data);
	}

	// --------------------------------------------------------------------------
		
	/**
	 * Edit a field
	 */
	public function edit()
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
		
		$this->template->append_js('module::fields.js');

		$this->data->method = 'edit';

 		// -------------------------------------
		// Parameters
		// -------------------------------------
		
		// Get the type.
		// The form has not been submitted, we must use the 
		// field's current field type
		if ( ! isset($_POST['field_type']))
		{
			$this->data->current_type = $this->type->types->{$this->data->current_field->field_type};
		}	
		else
		{
			$this->data->current_type = $this->type->types->{$this->input->post('field_type')};

			if (isset($this->data->current_type->custom_parameters))
			{		
				// Overwrite items out of post data
				foreach ($this->data->current_type->custom_parameters as $param)
				{
					$this->data->current_field->field_data[$param] = $this->input->post($param);
				}
			}
		}
		
		if ( ! isset($this->data->current_field->field_data))
		{
			$this->data->current_field->field_data = array();
		}

 		// Load Paramaters in case we need 'em
		require_once(PYROSTEAMS_DIR.'libraries/Parameter_fields.php');		
		$this->data->parameters = new Parameter_fields();

		// Prep the fields
		$this->data->field_types = $this->type->field_types_array($this->type->types);

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------

		// Add in the unique callback
		$this->fields_m->fields_validation[1]['rules'] .= '|unique_field_slug['.$this->data->current_field->field_slug.']';
		
		$this->form_validation->set_rules($this->fields_m->fields_validation);

		$this->data->field = new stdClass();
				
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
		
		if ($this->form_validation->run()):
	
			if( !$this->fields_m->update_field(
										$this->fields_m->get_field($field_id),
										$this->input->post()
									) ):
			
				$this->session->set_flashdata('notice', lang('streams:field_update_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('streams:field_update_success'));	
			
			endif;
	
			redirect('admin/streams/fields');
		
		endif;

		// -------------------------------------
		// Run field setup events
		// -------------------------------------

		$this->fields->run_field_setup_events();

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
		
			$this->session->set_flashdata('notice', lang('streams:field_delete_error'));	
		
		else:
		
			$this->session->set_flashdata('success', lang('streams:field_delete_success'));	
		
		endif;
	
		redirect('admin/streams/fields');
	}

}