<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Install_newsletters extends CI_Migration {

	public function up()
	{
		$this->load->model('modules/module_m');
		
		if ( ! $this->db->table_exists('newsletters'))
		{
			$this->module_m->install('newsletters', TRUE);
		}
		else
		{
			$this->module_m->upgrade('newsletters');
		}
	}

	public function down()
	{
		return TRUE;
	}
}