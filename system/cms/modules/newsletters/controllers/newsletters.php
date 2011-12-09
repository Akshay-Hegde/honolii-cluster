<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Newsletters extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('newsletters_m');
		$this->load->model('subscribers_m');
		$this->lang->load('newsletter');
	}
	
	//show a list of all newsletters
	public function index()
	{
		$this->data->newsletters = $this->newsletters_m->get_newsletters(array('order'=>'created_on DESC'));

		$this->template->build('index', $this->data);
	}
	
	//Display newsletter for users that wish to read on the website
	public function archive($id = '')
	{
		$this->data->newsletter = $this->newsletters_m->get_newsletter($id, $this->data);

		if($this->data->newsletter)
		{
			$this->template->build('view', $this->data);
		}
		else
		{
			redirect('404');
		}
	}
	
	public function subscribe()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', lang('newsletters.email_label'), 'trim|required|valid_email|callback__default');
		
		if( $this->form_validation->run() )
		{
			if( $this->subscribers_m->subscribe($this->input->post()) )
			{
				if ($this->settings->newsletter_opt_in == '0')
				{
					$this->session->set_flashdata(array('success'=> lang('newsletters.subscribed_success')));
					redirect('newsletters/subscribe');
				}
				else
				{
					$this->session->set_flashdata(array('notice'=> lang('newsletters.opt_in_message')));
					redirect('newsletters/subscribe');					
				}
			}
			else
			{
				$this->session->set_flashdata(array('error'=> lang('newsletters.add_mail_success')));
				redirect('newsletters/subscribe');
			}
		}
		$this->template->build('subscribe_form', $this->data);
	}
	
	public function activate($hash = '')
	{
		if ($this->subscribers_m->activate($hash))
		{
			$this->session->set_flashdata(array('success'=> lang('newsletters.opt_in_success')));
		}
		else
		{
			$this->session->set_flashdata(array('error'=> lang('newsletters.opt_in_error')));
		}
		redirect();
	}

	public function unsubscribe($hash = '') 
	{
		if($hash === '')
		{
			redirect('');
		}
		
		if ($this->subscribers_m->unsubscribe($hash))
		{
			$this->session->set_flashdata(array('success'=> lang('newsletters.delete_mail_success')));
			redirect('');
		}
		else
		{
			$this->session->set_flashdata(array('notice'=> lang('newsletters.delete_mail_error')));
			redirect('');
		}
	}
	
	//Record a click, then redirect to target url
	function short($hash = '')
	{
		$url = $this->newsletters_m->click($hash);
		
		if($url)
		{
			redirect($url->target);
		}
		else
		{
			redirect('404');
		}
	}
	
	//Record newsletter open when image is requested
	function img($id = '')
	{
		$this->newsletters_m->open($id);
		
		echo image('spacer.gif', 'newsletters');
	}
	
	//Fetch a file from the /uploads folder and output it
	//so newsletters can have images with an absolute path
	function files($filename)
	{
		if (defined('UPLOAD_PATH'))
		{
			echo file_get_contents(UPLOAD_PATH . 'files/' . $filename);
		}
		else
		{
			echo file_get_contents(BASE_URL . '/uploads/files/' . $filename);
		}
	}
	
	//make sure they didn't just submit the default email
	function _default($email)
	{
		if($email == lang('newsletters.example_email'))
		{
			return FALSE;
		}
	}
}