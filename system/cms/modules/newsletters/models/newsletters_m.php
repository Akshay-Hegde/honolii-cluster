<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Newsletters_m extends MY_Model
{
	protected $_table = 'newsletters';
	
	public function get_newsletters($params = array())
	{	
		if(isset($params['order'])) $this->db->order_by($params['order']);
		
		// Limit the results based on 1 number or 2 (2nd is offset)
		if(isset($params['limit']) && is_int($params['limit'])) $this->db->limit($params['limit']);
		
		elseif(isset($params['limit']) && is_array($params['limit'])) $this->db->limit($params['limit'][0], $params['limit'][1]);
		
		return $this->db->get('newsletters')->result();
	}
	
	public function get_cron_newsletters()
	{
		return $this->db->where('send_cron', 1)
					->get('newsletters')
					->result();
	}
	
	//get newsletter & parse the body
	public function get_newsletter($id, $data)
	{
		$data->newsletter = $this->db->get_where('newsletters', array('id' => $id))
							   ->row();

		$data->newsletter->body = preg_replace('/(src=")(.*)(uploads\/files\/)/', 'src="/uploads/files/', $data->newsletter->body);

		// parse the body for tags
		$data->newsletter->body = $this->parser->parse_string(str_replace('&quot;', '"', $data->newsletter->body), $data, TRUE);
		
		return $data->newsletter;
	}
	
	//get newsletter without parsing body (used in Edit)
	public function get_newsletter_source($id)
	{
		return $this->db->get_where('newsletters', array('id' => $id))
						->row();
	}
	
	public function count_newsletters($params = array())
	{
		return $this->db->count_all_results('newsletters');
	}
	
	public function create_newsletter($input = array())
	{
		$this->db->insert('newsletters',
						  array(
							'title' => $input['title'],
							'body' => $input['body'],
							'read_receipts' => $input['read_receipts'],
							'created_on' => now()
						  ));
		
		//key = target url, value = hash tag
		$combined_urls = array_combine($input['tracked_urls']['target'], $input['tracked_urls']['hash']);

		foreach($combined_urls as $key => $value)
		{
			if($key > '' AND $value > '')
			{
				$this->db->insert('newsletter_urls',
								  array(
									'target' => $key,
									'hash' => end(explode('/', $value)),
									'newsletter_id' => $this->db->insert_id()
								  ));
			}
		}
		
		return TRUE;
	}
	
	public function edit_newsletter($input, $id)
	{	
		$this->db->where('id', $id)
						->update('newsletters',
						  array(
							'title' => $input['title'],
							'body' => $input['body'],
							'read_receipts' => $input['read_receipts']
						  ));
						
		//get rid of old urls so there's no duplicates
		$this->db->delete('newsletter_urls', array('newsletter_id' => $id));

		//key = target url, value = hash tag
		$combined_urls = array_combine($input['tracked_urls']['target'], $input['tracked_urls']['hash']);

		foreach($combined_urls as $key => $value)
		{
			if($key > '' AND $value > '')
			{
				$this->db->insert('newsletter_urls',
								  array(
									'target' => $key,
									'hash' => end(explode('/', $value)),
									'newsletter_id' => $id
								  ));
			}
		}
		
		return TRUE;
	}
	
	public function delete_newsletter($id)
	{	
		return $this->db->delete('newsletters', array('id' => $id));
	}
	
	public function set_cron($id)
	{
		return $this->db->where('id', $id)
					->update('newsletters', array('send_cron' => 1));
	}
	
	public function send_newsletter($id, $batch, $data)
	{
		$this->load->library('email');
		
		//get the newsletter directly so we can parse after the email address is available
		$data->newsletter = $this->db->get_where('newsletters', array('id' => $id))
							   ->row();
		//make all wysiwyg images absolute
		$data->newsletter->body = preg_replace('/(src=")(.*)(uploads(.*)\/files\/)/', 'src="{{ url:site }}newsletters/files/', $data->newsletter->body);

		if(!$data->newsletter)
		{
			return 'Error';
		}
		
		//append the tracking image if it's enabled
		if($data->newsletter->read_receipts === '1')
		{
			$data->newsletter->body .= '<img src="{{ url:site }}newsletters/img/'.$id.'"/>';
		}

		//get the id of the last email so we know when we're done
		$last_email = $this->db->select('id')
							   ->from('newsletter_emails')
							   ->where('active', 1)
							   ->order_by('id', 'desc')
							   ->limit(1)
							   ->get()
							   ->row();
		
		//get the offset value from last sent id
		$offset = $this->db->from('newsletter_emails')
						   ->where('id <=', $data->newsletter->last_sent)
						   ->where('active', 1)
						   ->count_all_results();

		//we'll send them 50 per batch if Settings does not have a limit set
		$emails = $this->db->where('active', 1)
			->get('newsletter_emails', $this->settings->newsletter_email_limit > 0 ? $this->settings->newsletter_email_limit : 50, $offset)
			->result();
		
		if(!$emails)
		{
			return array('message' => lang('newsletters.no_subscribers'), 'status' => 'Finished');
		}
		
		foreach ($emails as $data->recipient)
		{
			// parse the body for tags
			$body = html_entity_decode($this->parser->parse_string(str_replace(array('&quot;', '&#39;'), array('"', "'"), $data->newsletter->body), $data, TRUE));
		
			$this->email->clear();
		
			$this->email->from(Settings::get('newsletter_from'));
			$this->email->reply_to(Settings::get('newsletter_reply_to'));
			$this->email->to($data->recipient->email);
			$this->email->subject($data->newsletter->title .' | '.Settings::get('site_name') .' '.lang('newsletters.subject_suffix'));
			$this->email->message($body);

			if( ! $this->email->send() )
			{
				return 'Error';
			}
		
			//We don't want to tick someone off by sending a duplicate if we have to abort & resend
			$this->db->update('newsletters', array('last_sent' => $data->recipient->id), array('id' => $id));
		}

		if($data->recipient->id == $last_email->id)
		{
			$this->db->update('newsletters', array('sent_on' => now()), array('id' => $id));
			
			return array('message' => sprintf(lang('newsletters.all_sent'), $this->db->where('active', 1)->count_all_results('newsletter_emails')), 'status' => 'Finished');
		}
		else
		{
			return array('message' => sprintf(lang('newsletters.number_sent'), $offset + 1, $this->db->count_all('newsletter_emails')), 'status' => $batch == 'false' ? 'Incomplete' : 'Finished');
		}
	}
	
	public function get_newsletter_urls($id)
	{
		return $this->db->get_where('newsletter_urls', array('newsletter_id' => $id))
						->result();
	}
	
	public function click($hash)
	{
		$url = $this->db->get_where('newsletter_urls', array('hash' => $hash))
						->row();
						
		//increment the click counter
		$this->db->insert('newsletter_clicks',
						  array('url_id' => $url->id,
								'newsletter_id' => $url->newsletter_id,
								'ip' => $this->input->ip_address(),
								'time' => now()
						  ));
		return $url;
	}
	
	public function open($id)
	{
		return $this->db->insert('newsletter_opens',
						  array('newsletter_id' => $id,
								'ip' => $this->input->ip_address(),
								'time' => now()
						  ));
	}
	
	public function get_statistics($id)
	{
		//fetch the links used in this newsletter
		$urls = $this->db->select('id, target')
						 ->from('newsletter_urls')
						 ->get()
						 ->result();
						 
		foreach($urls as $url)
		{
			$stats->{$url->target}->unique_visitors = $this->unique_visitors($url->id);
			
			$stats->{$url->target}->total_visitors = $this->total_visitors($url->id);
		}
		
		//get the data from the image tracking
		$stats->unique_opens = $this->unique_opens($id);
		$stats->total_opens = $this->total_opens($id);
		return $stats;
	}
	
	//total visitors to one tracked url
	public function total_visitors($url_id)
	{
		$records = $this->db->query('SELECT `ip` FROM ' . $this->db->dbprefix('newsletter_clicks') . ' WHERE `url_id` = '. $url_id)->result();
		
		return count($records);
	}
	
	//unique visitors to one tracked url
	public function unique_visitors($url_id)
	{
		$records = $this->db->query('SELECT DISTINCT `ip` FROM ' . $this->db->dbprefix('newsletter_clicks') . ' WHERE `url_id` = '. $url_id)->result();
		
		return count($records);
	}
	
	//all img opens + unique link clicks = total newsletter opens
	public function total_opens($id)
	{
		$unique_clicks = $this->db->query('SELECT DISTINCT `ip` FROM ' . $this->db->dbprefix('newsletter_clicks') . ' WHERE `newsletter_id` = '.$id)
								  ->result_array();
		$opens = $this->db->query('SELECT `ip` FROM ' . $this->db->dbprefix('newsletter_opens') . ' WHERE `newsletter_id` = '. $id)
						  ->result_array();

		foreach($unique_clicks as $click)
		{
			if(!in_array($click, $opens))
			{
				$opens[] = $click;
			}
		}
		return count($opens);
	}
	
	//unique newsletter opens by ip using img tracking plus clicks
	public function unique_opens($id)
	{
		$records = $this->db->query('SELECT DISTINCT `ip` FROM ' . $this->db->dbprefix('newsletter_clicks') . ' WHERE `newsletter_id` = '.$id.' UNION SELECT DISTINCT `ip` FROM ' . $this->db->dbprefix('newsletter_opens') . ' WHERE `newsletter_id` = '.$id)
						->result();
		
		return count($records);
	}
}