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
			$where_array = array();

			// run through the records from the main table
			foreach ($data_array AS $i => $item)
			{
				// this is the table list from the config file
				foreach ($table_list[$table] AS $join_table => $keys)
				{
					// grab the key => value for this table to use as a where clause
					foreach ($keys AS $secondary_column => $main_column);

					// we do a multidimensional array for json and xml
					if ($type != 'csv')
					{
						$data_array[$i][$join_table] = $this->db->where($secondary_column, $item[$main_column])
															->get($join_table)
															->result_array();
					}
					// but we'll want to fetch matching records from each table later and stick it in a separate file for csv
					else
					{
						$where_array[$join_table][] = array($secondary_column => $item[$main_column]);
					}
				}
			}

			// It's either json or xml so we'll output it all in one file
			if ($type != 'csv')
			{
				force_download($table.'.'.$type, $this->format->factory($data_array)->{'to_'.$type}());
			}
			// Awe come on csv, quit your whining
			else
			{
				// get the records that are related to the main table
				foreach ($where_array AS $related_table => $where)
				{
					foreach ($where AS $value)
					{
						$this->db->or_where($value);
					}
					
					// add a file to the zip containing the related records
					$join_array = $this->db->get($related_table)->result_array();
					$this->zip->add_data($related_table.'.'.$type, $this->format->factory($join_array)->{'to_'.$type}());
				}
				
				// we have all the related data, now we'll add the file for the main table
				$this->zip->add_data($table.'.'.$type, $this->format->factory($data_array)->{'to_'.$type}());
				
				// now download it
				$this->zip->download($table.'.zip');		
			}
		}
	}
}