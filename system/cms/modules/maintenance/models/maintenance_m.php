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
		// Check to see if it's a simple export
		if (empty($table_list[$table]))
		{
			$data_array = $this->db->get($table)->result_array();
			
			force_download($table.'.'.$type, $this->format->factory($data_array)->{'to_'.$type}());
		}
		// Nope we gotta fetch some related tables
		else
		{
			$data_array = $this->db->get($table)->result_array();

			// run through the records from the main table
			foreach ($data_array AS $i => $item)
			{
				// this is the table list from the config file
				foreach ($table_list[$table] AS $join_table => $keys)
				{
					// grab the key => value for this table to use as a where clause
					foreach ($keys AS $secondary_column => $main_column);

					$data_array[$i][$join_table] = $this->db->where($secondary_column, $item[$main_column])
														->get($join_table)
														->result_array();
				}
			}

			force_download($table.'.'.$type, $this->format->factory($data_array)->{'to_'.$type}());
		}
	}
}