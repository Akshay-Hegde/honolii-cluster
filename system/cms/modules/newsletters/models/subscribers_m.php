<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Subscribers_m extends MY_Model
{
	protected $_table = 'newsletter_emails';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function subscribe($input = array(), $admin = 0)
	{
		$email = $input['email'];
		$hash = $this->rand_string(10);
			
		if (!$this->check_email($email))
		{
			// if subscribed via admin panel we won't send activation email regardless of setting
			if ($this->settings->newsletter_opt_in == '0' OR $admin = 1)
			{
				$this->db->insert('newsletter_emails', array('email' => $email,
															 'hash' => $hash,
															 'active' => 1,
															 'registered_on'=>now()
															 ));
				return TRUE;
			}
			else
			{
				$this->db->insert('newsletter_emails', array('email' => $email,
															 'hash' => $hash,
															 'active' => 0,
															 'registered_on'=>now()
															 ));
					
				$data['slug'] 					= 'newsletters_opt_in';
				$data['newsletter_activation']	= site_url('newsletters/activate/'.$hash);
				$data['to']						= $email;
				$data['from']					= $this->settings->newsletter_from;
				$data['reply-to']				= $this->settings->reply_to;
				$data['name']					= $this->settings->reply_to;

				/**
				 * We do this in this module so that it's backwards compatible with PyroCMS v1.2.0
				 * When we drop support for that we'll be able to just use Events::trigger()
				 * and let the templates module take care of the rest
				 */
				$this->load->model('templates/email_templates_m');
			
				$lang = Settings::get('site_lang');
			
				//get all email templates
				$templates = $this->email_templates_m->get_templates($data['slug']);
					
				//make sure we have something to work with
				if ( ! empty($templates))
				{
					$lang	  = isset($data['lang']) ? $data['lang'] : Settings::get('site_lang');
					$from	  = isset($data['from']) ? $data['from'] : Settings::get('server_email');
					$reply_to = isset($data['reply-to']) ? $data['reply-to'] : $from;
					$to		  = isset($data['to']) ? $data['to'] : Settings::get('contact_email');
			
					$subject = array_key_exists($lang, $templates) ? $templates[$lang]->subject : $templates['en']->subject ;
					$subject = $this->parser->parse_string($subject, $data, TRUE);
			
					$body = array_key_exists($lang, $templates) ? $templates[$lang]->body : $templates['en']->body ;
					$body = $this->parser->parse_string($body, $data, TRUE);
			
					$this->email->from($from, $data['name']);
					$this->email->reply_to($reply_to);
					$this->email->to($to);
					$this->email->subject($subject);
					$this->email->message($body);
			
					if ($this->email->send() == TRUE)
					{
						return TRUE;
					}
					else
					{
						$this->session->set_flashdata('error', 'Your activation email could not be sent. Please inform the site owner.');
						redirect('newsletters/subscribe');
					}
				}
				return FALSE;
				/**
				 * End backwards compatibility
				 */
			}
		}
		return FALSE;
	}
	
	public function unsubscribe($hash)
	{
		return $this->db->delete('newsletter_emails', array('hash' => $hash));
	}
	
	public function activate($hash)
	{
		$this->db->where('hash', $hash)->update('newsletter_emails', array('active' => 1));
		
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function admin_unsubscribe($email)
	{
		return $this->db->delete('newsletter_emails', array('email' => $email));
	}
	
	public function count_subscribers()
	{
		// whenever we count the subscribers we'll also delete the ones that
		// have sat for 14 days without activating
		$this->db->where('active', 0)
			->where('registered_on <', (now() - 1204000))
			->delete('newsletter_emails');
			
		return $this->db->where('active', 1)
			->count_all_results('newsletter_emails');
	}
	
	public function rand_string($length = 10)
	{
		$chars = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
		$max = strlen($chars)-1;
		$string = '';
		mt_srand((double)microtime() * 1000000);
		while (strlen($string) < $length)
		{
			$string .= $chars{mt_rand(0, $max)};
		}
		return $string;
	}
	
	public function check_email($email)
	{
		$query = $this->db->get_where('newsletter_emails', array('email' => $email));
		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}