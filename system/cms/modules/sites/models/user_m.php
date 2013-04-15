<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class User_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Add a Super Admin
	 *
	 * @param	array	$user	The form data for the user
	 * @return	bool
	 */
	public function add_admin($user)
	{
		$hash = $this->_hash_password($user['password']);
		
		// we must set the id manually because ion_auth does not use auto-increment
		$query = $this->db->select_max('id')
			->get($this->_table)
			->row();

		$insert = array('id'			=>	++$query->id,
						'username'		=>	$user['username'],
						'group_id'		=>	1,
						'active'		=>	1,
						'created_on'	=>	now(),
						'last_login'	=>	0,
						'email'			=>	$user['email'],
						'password'		=>	$hash->password,
						'salt'			=>	$hash->user_salt
						);
		
		return $this->db->insert($this->_table, $insert);
	}
	
	/**
	 * Edit and existing Super
	 *
	 * @param	array	$user	Form data for the user
	 * @return	bool
	 */
	public function edit_admin($user)
	{
		$insert = array('username'		=>	$user['username'],
						'group_id'		=>	1,
						'active'		=>	1,
						'last_login'	=>	0,
						'email'			=>	$user['email']
						);

		if($user['password'] > '')
		{
			$hash = $this->_hash_password($user['password']);
			
			$insert['password'] = $hash->password;
			$insert['salt']		= $hash->user_salt;
		}
		
		return $this->update($user['id'], $insert);
	}
	
	/**
	 * Creates the first core_user when the site is being installed
	 *
	 * @param	array	$user
	 * @return	bool
	 */
	public function create_default_user($user)
	{
		$users = "CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('users') . " (
		  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
		  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `salt` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `group_id` int(11) DEFAULT NULL,
		  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `active` int(1) DEFAULT NULL,
		  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `created_on` int(11) NOT NULL,
		  `last_login` int(11) NOT NULL,
		  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `email` (`email`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Registered User Information';
		";

		$user_data = sprintf("INSERT INTO " . $this->db->dbprefix('users') . " (`id`, `email`, `password`, `salt`, `group_id`, `ip_address`, `active`, `activation_code`, `created_on`, `last_login`, `username`, `forgotten_password_code`, `remember_code`) VALUES
			(1,'%s', '%s', '%s', 1, '', 1, '', %s, %s, '%s', null, null); ",
			$user['email'],
			$user['password'],
			$user['salt'],
			now(),
			now(),
			$user['username']
			);


		$profiles = "CREATE TABLE " . $this->db->dbprefix('profiles') . " (
		  `id` int(9) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) unsigned NOT NULL,
		  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `company` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `lang` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
		  `bio` text COLLATE utf8_unicode_ci,
		  `dob` int(11) DEFAULT NULL,
		  `gender` set('m','f','') COLLATE utf8_unicode_ci DEFAULT NULL,
		  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `address_line1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `address_line2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `address_line3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `postcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `msn_handle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `yim_handle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `aim_handle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `gtalk_handle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `gravatar` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `twitter_access_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `twitter_access_token_secret` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `updated_on` int(11) unsigned DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
		";

		$profile_data = sprintf("INSERT INTO " . $this->db->dbprefix('profiles') . " (`id`, `user_id`, `first_name`, `last_name`, `display_name`, `company`, `lang`, `bio`, `dob`, `gender`, `phone`, `mobile`, `address_line1`, `address_line2`, `address_line3`, `postcode`, `msn_handle`, `yim_handle`, `aim_handle`, `gtalk_handle`, `gravatar`, `updated_on`) VALUES
			(1, 1, '%s', '%s', '%s', '', 'en', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null); ",
			$user['first_name'],
			$user['last_name'],
			$user['first_name'].' '.$user['last_name']
			);
		
		if($this->db->query($users) AND
		   $this->db->query($user_data) AND
		   $this->db->query($profiles) AND
		   $this->db->query($profile_data))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Get the first admin for a site
	 *
	 * @param	string	$ref
	 * @return	object
	 */
	public function get_default_user($ref)
	{
		// fetch the first admin user from this site
		return $this->db->query(sprintf("SELECT u.id, email, username, first_name, last_name
										FROM %s_users AS u JOIN %s_profiles AS p
										ON u.id = (u.id = p.user_id and u.id > 0)
										WHERE u.group_id = 1 LIMIT 1",
										$ref,
										$ref
										)
								)
			->row();
	}
	
	/**
	 * Update a default user's info over both tables
	 *
	 * @param	string	$ref	The site ref
	 * @param	array 	$user	The user info to insert
	 * @return	boolean
	 */
	public function update_default_user($ref, $user)
	{
		$sql = sprintf("UPDATE %s_users AS u, %s_profiles AS p
						SET u.email = '%s', u.username = '%s',
						p.first_name = '%s', p.last_name = '%s' ",
						$ref,
						$ref,
						$user['email'],
						$user['username'],
						$user['first_name'],
						$user['last_name']
						);
		
		// if they've supplied a new password then we'll insert that
		if (isset($user['password']))
		{
			$sql .= sprintf(", u.password = '%s', u.salt = '%s' ",
							$user['password'],
							$user['salt']
						   );
		}
		
		$sql .= sprintf("WHERE u.id = '%s' 
						AND p.user_id = '%s'",
						$user['id'],
						$user['id']
						);
		
		return $this->db->query($sql);
	}
	
	/**
	 * Log a Super Admin in
	 *
	 * @param	string	$email
	 * @param	string	$password
	 * @return	bool
	 */
	public function login($email, $password, $remember = false)
	{
		if (empty($email) OR empty($password)) return false;
		
		$user = $this->select('id, email, username, password, salt')
			->where('active', 1)
			->limit(1)
			->get_by('email', $email);
		
		// if there's no user with that email then bail
		if ( ! isset($user->email)) return false;
			
		$hashed = $this->_hash_password($password, $user->salt);

		if ($hashed->password === $user->password)
		{
			$session = array(
				'super_email'		=> $user->email,
				'super_id'			=> $user->id,
				'super_username'	=> $user->username
				 );
	
			$this->session->set_userdata($session);
			
			//update the last_login timestamp
			$this->update($user->id, array('last_login' => now()));
			
			if ($remember === true)
			{
				$this->remember_user($user->id, $user->email, $user->password);
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Log a Super Admin in with his cookies
	 *
	 * @return	bool
	 */
	public function login_remembered()
	{
		if ( ! get_cookie('super_identity') OR ! get_cookie('super_remember_code')) return false;
		
		$user = $this->select('id, email, username, password, salt, remember_code')
			->where('active', 1)
			->limit(1)
			->get_by('email', get_cookie('super_identity'));
		
		// if there's no user with that email then bail
		if ( ! isset($user->email)) return false;

		if (get_cookie('super_remember_code') === $user->remember_code)
		{
			$session = array(
				'super_email'		=> $user->email,
				'super_id'			=> $user->id,
				'super_username'	=> $user->username
				 );
	
			$this->session->set_userdata($session);
			
			//update the last_login timestamp
			$this->update($user->id, array('last_login' => now()));
			
			if (config_item('user_extend_on_login'))
			{
				$this->remember_user($user->id, $user->email, $user->password);
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check if a Super Admin is logged in
	 *
	 * @return	bool
	 */
	public function logged_in()
	{
		if ( ! $this->session->userdata('super_email') AND
			get_cookie('super_identity') AND
			get_cookie('super_remember_code'))
		{
			return $this->login_remembered();
		}
		return (bool) $this->session->userdata('super_email');
	}
	
	/**
	 * Get rid of the user's session
	 */
	public function logout()
	{
		$this->session->unset_userdata('super_email');
		$this->session->unset_userdata('super_username');
		
	    //delete the remember me cookies if they exist
	    if (get_cookie('super_identity'))
	    {
			delete_cookie('super_identity');
	    }
		if (get_cookie('super_remember_code'))
	    {
			delete_cookie('super_remember_code');
	    }
		
		$this->session->sess_destroy();
		return true;
	}
	
	/**
	 * Hash the password either with a generated salt
	 * or with the salt that was passed.
	 *
	 * @param	string	$pass
	 * @param	string	$salt
	 * @param
	 */
	public function _hash_password($pass, $salt = false)
	{
		$hash->user_salt	= substr(md5(uniqid(rand(), true)), 0, config_item('salt_length'));
		
		//this lets us pass the salt from the database for logins
		$chosen_salt		= ($salt === false) ? $hash->user_salt : $salt;
		
		$hash->password		= sha1($pass . $chosen_salt);
		
		return $hash;
	}
	
	/**
	 * Remember a Super Admin
	 *
	 * @param	int	$id The user id
	 * @return	bool
	 */
	private function remember_user($id, $email, $pass)
	{
		// hash to password hash to get the remember code
		$remember_code = sha1($pass);

		if ($this->update($id, array('remember_code' => $remember_code)))
		{
			set_cookie(array(
				'name'   => 'super_identity',
				'value'  => $email,
				'expire' => config_item('user_expire'),
			));
			
			set_cookie(array(
				'name'   => 'super_remember_code',
				'value'  => $remember_code,
				'expire' => config_item('user_expire'),
			));

			return true;
		}
	}
}
