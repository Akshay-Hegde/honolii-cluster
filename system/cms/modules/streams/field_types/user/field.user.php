<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams User Field Type
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_user
{
	public $field_type_name 		= 'User';
	
	public $field_type_slug			= 'user';
	
	public $db_col_type				= 'int';

	public $custom_parameters		= array( 'restrict_group' );

	public $version					= '1.0';

	public $author					= array('name'=>'Parse19', 'url'=>'http://parse19.com');

	public $lang					= array(
	
		'en'	=> array(
				'restrict_group'	=> 'Restrict Group'
		)
	
	);			

	// --------------------------------------------------------------------------

	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output( $params )
	{
		$this->CI->db->select('username, id');
	
		if( is_numeric($params['custom']['restrict_group']) ):
		
			$this->CI->db->where('group_id', $params['custom']['restrict_group']);
		
		endif;
		
		$this->CI->db->order_by('username', 'asc');
		$db_obj = $this->CI->db->get('users');
		
		$users_raw = $db_obj->result();
		
		$users = array();
		
		foreach( $users_raw as $user ):
		
			$users[$user->id] = $user->username;
		
		endforeach;
	
		return form_dropdown($params['form_slug'], $users, $params['value'], 'id="'.$params['form_slug'].'"');
	}

	// --------------------------------------------------------------------------

	/**
	 * Restrict to Group
	 */
	public function param_restrict_group( $value = '' )
	{
		$this->CI->db->order_by('name', 'asc');
		
		$db_obj = $this->CI->db->get('groups');
		
		$groups = array('no' => 'Don\'t Restrict Groups');
		
		$groups_raw = $db_obj->result();
		
		foreach( $groups_raw as $group ):
		
			$groups[$group->id] = $group->name;
		
		endforeach;
	
		return form_dropdown('restrict_group', $groups, $value);
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before outputting for the plugin
	 *
	 * This creates an array of data to be merged with the
	 * tag array so relationship data can be called with
	 * a {field.column} syntax
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	public function pre_output_plugin( $prefix, $input, $params )
	{
		$choices = array();
		
		$CI =& get_instance();

		$CI->load->model('users/users_m');
		
		$user = $CI->users_m->get( array('id' => $input) );
		
		$user_data[rtrim($prefix, '.')]		= $user->username;
		$user_data[$prefix.'user_id'] 		= $user->user_id;
		$user_data[$prefix.'display_name'] 	= $user->display_name;
		$user_data[$prefix.'first_name'] 	= $user->first_name;
		$user_data[$prefix.'last_name'] 	= $user->last_name;
		$user_data[$prefix.'company'] 		= $user->company;
		$user_data[$prefix.'lang'] 			= $user->lang;
		$user_data[$prefix.'email'] 		= $user->email;
		$user_data[$prefix.'username'] 		= $user->username;

		return $user_data;
	}
}
/* End of file field.user.php */