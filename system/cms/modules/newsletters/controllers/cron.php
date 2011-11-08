<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Cron extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('newsletters_m');
	}
	
	//send all newsletters that are marked for cron
	public function index($key = '')
	{
		//keep random users from triggering
		if($key == $this->settings->newsletter_cron_key)
		{
			//send a copy of $this->data along for the parser
			$data =& $this->data;
			
			if($newsletters = $this->newsletters_m->get_cron_newsletters())
			{
				foreach($newsletters as $newsletter)
				{
					$this->newsletters_m->send_newsletter($newsletter->id, '', $data);
				}
			}
		}
	}
}