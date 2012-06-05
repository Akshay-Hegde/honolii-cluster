<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Templates extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('templates_m');
		$this->lang->load('newsletter');

		//set validation rules
		$this->load->library('form_validation');

		$this->template_rules = array(
					array(
						'field' => 'title',
						'label' => lang('newsletters.title_label'),
						'rules'	=> 'trim|required'
					),
					array(
						'field' => 'body',
						'label' => lang('newsletters.body_label'),
						'rules' => 'trim|required'
					)
				);
		
		// determine the modules location once for use in js
		if(is_dir(ADDONPATH.'modules/newsletters'))
		{
			$this->template->append_metadata('<script type="text/javascript">var MODULE_LOCATION = "'.ADDONPATH.'modules/newsletters/";</script>');
		}
		else
		{
			$this->template->append_metadata('<script type="text/javascript">var MODULE_LOCATION = "'.SHARED_ADDONPATH.'modules/newsletters/";</script>');
		}
	}

	public function index()
	{
		//do they want to delete the template?
		if($this->input->post())
		{
			if($_POST['btnAction'] == 'delete')
			{
				$delete = $this->input->post('template_list');

				if ($this->templates_m->delete_template($delete));
				
				$this->session->set_flashdata(array('success'=> lang('newsletters.template_delete_success')));
				redirect('admin/newsletters/templates');
			}
		}
		
		//else go on with normal business
		$this->form_validation->set_rules($this->template_rules);
		
		if( $this->form_validation->run() )
		{
			if($this->templates_m->save_template($this->input->post()))
			{
				$this->session->set_flashdata(array('success'=>
													sprintf(lang('newsletters.template_add_success'),
															$this->input->post('title'))));
				redirect('admin/newsletters/templates');
			}
		}
		
		//get all templates so we can populate the dropdown
		$template_list = $this->templates_m->get_templates();
		$this->data->template_list[0] = '';
		foreach($template_list as $template)
		{
			$this->data->template_list[$template->id] = $template->title;
		}
		
		$this->template->title($this->module_details['name'], lang('newsletters.templates'))
						->set('active_section', 'templates')
						->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
						->append_js('module::functions.js')
						->append_css('module::admin.css')
						->build('admin/templates', $this->data);
	}
	
	public function get_template()
	{
		echo json_encode($this->templates_m->get_template($this->input->post('id')));
	}
	
	public function delete_template()
	{
		echo json_encode($this->templates_m->delete_template($this->input->post('id')));
	}
}