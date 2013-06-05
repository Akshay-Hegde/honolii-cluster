<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Settings_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get settings in an easy to access format
	 * This is the same as $this->settings->get_all() except that this
	 * only returns settings that are in the database.
	 *
	 * @return	object
	 */
	public function get_settings()
	{
		$settings = new stdClass();
		
		$result = $this->get_all();
		
		foreach ($result AS $setting)
		{
			$settings->{$setting->slug} = $setting->value;
		}
		
		return $settings;
	}
	
	/**
	 * Update all settings
	 *
	 * @param	array	$settings
	 * @return boolean
	 */
	public function update_settings($settings)
	{
		foreach ($settings AS $slug => $value)
		{
			$this->db->where('slug', $slug)
				->set('value', $value)
				->update($this->_table);
		}
		return true;
	}
}
