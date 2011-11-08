<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Maintenance Module
 *
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Maintenance module
 * @category 	Modules
 */
class Maintenance_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function export($table = '', $type = 'xml', $table_list)
	{
		switch ($table) {
			case 'users':
				$data_array = $this->db->select('users.*, profiles.*, profiles.id profile_id')
					->from('users')
					->join('profiles', 'profiles.user_id = users.id')
					->get()
					->result_array();
			break;
		
			case 'files':
				$data_array = $this->db->select('files.*, file_folders.name folder_name, file_folders.slug')
					->from('files')
					->join('file_folders', 'files.folder_id = file_folders.id')
					->get()
					->result_array();				
			break;
		
			default:
				$data_array = $this->db->get($table)
					->result_array();			
			break;
		}
		force_download($table.'.'.$type, $this->format->factory($data_array)->{'to_'.$type}());
	}
}