<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Settings extends Sites_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->val_rules = array();
		
		// set the validation rules dynamically
		foreach ($this->settings AS $slug => $value)
		{
			$this->val_rules[] = array('field' => $slug,
									   'label' => lang('site.'.$slug),
									   'rules' => 'trim|required'
									   );
		}
	}
	
	/**
	 * Show and save settings
	 */
	public function index()
	{
		$data->settings = $this->settings_m->get_all();
		
		// Set the validation rules
		$this->form_validation->set_rules($this->val_rules);
		
		if($this->form_validation->run())
		{
			unset($_POST['btnAction']);
			
			if ($this->settings_m->update_settings($this->input->post()))
			{
				$this->session->set_flashdata('success', lang('site.settings_success'));
				redirect('sites/settings');
			}
			$this->session->set_flashdata('error', lang('site.db_error'));
			redirect('sites/settings');
		}

		// Load the view
		$this->template->title(lang('site.sites'), lang('site.settings'))
						->set('description', lang('site.settings_desc'))
						->build('settings', $data);
	}
}
