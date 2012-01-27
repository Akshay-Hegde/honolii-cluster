<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Blog
 * @category  	Module
 */
class Admin extends Admin_Controller
{
	protected $section = 'subscriptions';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->driver('Streams');
		
		$this->streams->cp->form('subscriptions', 'streams', $mode = 'new', $skips = NULL, $view_override = TRUE);
	}
}