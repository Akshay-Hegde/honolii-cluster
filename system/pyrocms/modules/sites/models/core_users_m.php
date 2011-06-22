<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Core_users_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get the info for a single user
	 *
	 * @param	int		$id	The user's core id
	 * @return	mixed
	 */
	public function get_user($id)
	{
		return $this->db->query('SELECT * FROM core_users WHERE id='.$id)->row();
	}

	/**
	 * Get a list of all super-admins
	 *
	 * @return	mixed
	 */
	public function get_users()
	{
		return $this->db->query('SELECT * FROM core_users')->result();
	}
	
	/**
	 * Get a list of all regular site users
	 *
	 * @param	string	$ref	The table to retrieve users from
	 * @return	mixed
	 */
	public function get_normal_users($ref)
	{
		return $this->db->query(sprintf('SELECT * FROM %s', $ref.'_users'))->result();
	}
	
	/**
	 * Filter Users
	 *
	 * @param	int		$f_active	0/1/2 User active or not
	 * @param	string	$f_group	The site to load users from
	 * @param	string	$f_keywords	Additional keywords
	 */
	public function filter($params)
	{
		if ($params['f_group'] === '0')
		{
			// set a flag so we can tell where these users came from
			$data->core = TRUE;
			$data->ref = $params['f_group'];
			
			$data->users = $this->db->query('SELECT * FROM core_users')
				->result();
				
			return $data;
		}
		else
		{
			$core_emails = array();
			$data->core = FALSE;
			$data->ref = $params['f_group'];
			
			//select the emails so we can remove duplicates
			$core_users = $this->db->query('SELECT email FROM core_users')
				->result();
				
			// build an array of emails to search
			foreach ($core_users AS $user)
			{
				$core_emails[] = $user->email;
			}
		}

		$this->db->set_dbprefix($data->ref.'_');
		
		$data->users = $this->db
			->from('users')
			->join('profiles', 'users.id = profiles.user_id')
			->like('users.username', trim($params['f_keywords']))
			->or_like('users.email', trim($params['f_keywords']))
			->or_like('profiles.first_name', trim($params['f_keywords']))
			->or_like('profiles.last_name', trim($params['f_keywords']))
			->get()
			->result();
	
		// check each user to make sure they aren't a super admin already
		foreach ($data->users AS $key => $user)
		{
			if (in_array($user->email, $core_emails))
			{
				unset($data->users{$key});
			}
		}
		return $data;
	}
	
	/**
	 * Turn an existing user into a super-admin
	 *
	 * @param	int	$id		The id from the user table
	 * @param	string	$site	The ref of the user's site
	 * @return	bool
	 */
	public function make_super_admin($ref, $id)
	{
		return $this->db->query("INSERT INTO core_users SELECT * FROM ".$ref."_users WHERE id=".$id);
	}
	
	/**
	 * Update a user. Enable/Disable
	 *
	 * @param	int	$id		The user's id
	 * @param	int	$toggle	Enable or disable
	 * @return	bool
	 */
	public function update($id, $toggle)
	{
		return $this->db->query(sprintf(
				'UPDATE core_users SET active="%s"
				WHERE id='.$id.';',
			$toggle));
	}
	
	/**
	 * Delete A User
	 *
	 * @param	int		$id		The id of the user
	 * @return	bool
	 */
	public function delete($id)
	{	
		return $this->db->query("DELETE FROM core_users WHERE id=".$id);
	}
	
	/**
	 * Check if the current user is a super admin or not
	 *
	 * @return bool
	 */
	public function is_super_admin()
	{
		 return (bool) $this->db->query('SELECT * FROM core_users WHERE id='.$this->user->id.' AND active=1')->result();
	}
}
