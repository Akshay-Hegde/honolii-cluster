<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Subscribers extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('subscribers_m');
		$this->lang->load('newsletter');

		//set validation rules
		$this->load->library('form_validation');
		
		$this->unsubscribe_rules = array(
					array(
						'field' => 'email',
						'label' => lang('newsletters.email_label'),
						'rules' => 'trim|valid_email|required'
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
		$data->subscribers = $this->subscribers_m->count_subscribers();
		
		$this->template->title($this->module_details['name'], lang('newsletters.subscribers'))
						->set('active_section', 'subscribers')
						->append_js('module::functions.js')
						->append_css('module::admin.css')
						->build('admin/subscribers', $data);
	}
	
	public function export($type = 'xml')
	{
		$this->load->helper('download');
		$this->load->library('Format');

		$subscribers = $this->db->get('newsletter_emails')->result_array();
		
		force_download('subscribers.'.$type, $this->format->factory($subscribers)->{'to_'.$type}());
	}

	public function subscribe()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', lang('newsletters.email_label'), 'trim|required|valid_email');
		
		if( $this->form_validation->run() )
		{
			if( $this->subscribers_m->subscribe($this->input->post(), 1) )
			{
				if ($this->settings->newsletter_opt_in == '0')
				{
					$this->session->set_flashdata(array('success'=> lang('newsletters.admin_subscribed_success')));
				}
			}
			else
			{
				$this->session->set_flashdata(array('error'=> lang('newsletters.add_mail_success')));
			}
		}
		redirect('admin/newsletters/subscribers');
	}

	//manually unsubscribe an email
	public function unsubscribe()
	{
		$this->form_validation->set_rules($this->unsubscribe_rules);
		
		if( $this->form_validation->run() )
		{
			if( $this->subscribers_m->admin_unsubscribe($this->input->post('email') ) )
			{
				$this->session->set_flashdata('success', lang('newsletters.unsubscribe_success'));
			}
			else
			{
				$this->session->set_flashdata(array('error'=> lang('newsletters.unsubscribe_error')));
			}
		}
		redirect('admin/newsletters/subscribers');
	}
}