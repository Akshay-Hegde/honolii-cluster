<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Users_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function add_admin($user)
	{
		$user_salt	= substr(md5(uniqid(rand(), true)), 0, 5);
		$password	= sha1($user['password'] . $user_salt);
		
		$insert = array('username'		=>	$user['username'],
						'group_id'		=>	1,
						'active'		=>	1,
						'created_on'	=>	time(),
						'last_login'	=>	time(),
						'email'			=>	$user['email'],
						'password'		=>	$password,
						'salt'			=>	$user_salt
						);
		
		return $this->insert($insert);
	}
	
	public function edit_admin($user)
	{
		$user_salt	= substr(md5(uniqid(rand(), true)), 0, 5);
		$password	= sha1($user['password'] . $user_salt);
		
		$insert = array('username'		=>	$user['username'],
						'group_id'		=>	1,
						'active'		=>	1,
						'created_on'	=>	time(),
						'last_login'	=>	time(),
						'email'			=>	$user['email']
						);

		if($user['password'] > '')
		{
			$user_salt	= substr(md5(uniqid(rand(), true)), 0, 5);
			$password	= sha1($user['password'] . $user_salt);
		
			$insert['password'] = $password;
			$insert['salt']		= $user_salt;
		}
		
		return $this->update($user['id'], $insert);
	}
	
	public function create_default_user($user)
	{
		$users = "CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('users') . " (
		  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
		  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `salt` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `group_id` int(11) DEFAULT NULL,
		  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
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

		$user_data = "INSERT INTO " . $this->db->dbprefix('users') . " (`id`, `email`, `password`, `salt`, `group_id`, `ip_address`, `active`, `activation_code`, `created_on`, `last_login`, `username`, `forgotten_password_code`, `remember_code`) VALUES
			(1,'".$user['email']."', '".$user['password']."', '".$user['salt']."', 1, '', 1, '', ".time().", ".time().", '".$user['username']."', NULL, NULL); ";


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

		$profile_data = "INSERT INTO " . $this->db->dbprefix('profiles') . " (`id`, `user_id`, `first_name`, `last_name`, `display_name`, `company`, `lang`, `bio`, `dob`, `gender`, `phone`, `mobile`, `address_line1`, `address_line2`, `address_line3`, `postcode`, `msn_handle`, `yim_handle`, `aim_handle`, `gtalk_handle`, `gravatar`, `updated_on`) VALUES
			(1, 1, '".$user['first_name']."', '".$user['last_name']."', '".$user['first_name'].' '.$user['last_name']."', '', 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL); ";
		
		if($this->db->query($users) AND
		   $this->db->query($user_data) AND
		   $this->db->query($profiles) AND
		   $this->db->query($profile_data))
		{
			return TRUE;
		}
		return FALSE;
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
		return $this->db->query("SELECT u.id, email, username, first_name, last_name FROM ".$ref.
							  "_users AS u JOIN ".$ref."_profiles AS p
							  ON u.id = (u.id = p.user_id AND u.id > 0)
							  WHERE u.group_id = 1 LIMIT 1")
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
		$sql = "UPDATE ".$ref."_users AS u, ".$ref."_profiles AS p
				SET u.email = '".$user['email']."', u.username = '".$user['username']."',
					p.first_name = '".$user['first_name']."', p.last_name = '".$user['last_name']."' ";
		
		// if they've supplied a new password then we'll insert that
		if (isset($user['password']))
		{
			$sql .= ", u.password = '".$user['password']."', u.salt = '".$user['salt']."' ";
		}
		
		$sql .= "WHERE u.id = '".$user['id']."' 
				AND p.user_id = '".$user['id']."'";
		
		return $this->db->query($sql);
	}
}
