<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Newsletter module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Newsletter module
 * @category 	Modules
 */
class Templates_m extends MY_Model
{
	protected $_table = 'newsletter_templates';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_templates()
	{
		return $this->db->get('newsletter_templates')
						->result();
	}
	
	public function get_template($id)
	{
		return $this->db->get_where('newsletter_templates', array('id' => $id))
						->row();
	}
	
	public function save_template($input)
	{
		//save edit
		if($input['id'] > 0)
		{
		return $this->db->where('id', $input['id'])
						->update('newsletter_templates',
						  array(
							'title' => $input['title'],
							'body' => $input['body']
						  ));
		}
		//save new
		return $this->db->insert('newsletter_templates',
						  array(
							'title' => $input['title'],
							'body' => $input['body']
						  ));
	}
	
	public function delete_template($id)
	{	
		return $this->db->delete('newsletter_templates', array('id' => $id));
	}
}