<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Sites_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get a site by id along with first admin
	 *
	 * @param	int	$id	Site id
	 * @return	obj
	 */
	public function get_site($id)
	{
		$site = $this->get($id);
		
		$user = $this->users_m->get_default_user($site->ref);
			
		$site->user_id			= $user->id;
		$site->email			= $user->email;
		$site->user_name 		= $user->username;
		$site->first_name 		= $user->first_name;
		$site->last_name 		= $user->last_name;
		$site->password 		= '';
		$site->confirm_password = '';
		
		return $site;
	}
	
	/**
	 * Create a new site
	 *
	 * @param	array	$input	The post data
	 * @return	bool
	 */
	public function create_site($input)
	{
		$user_salt	= substr(md5(uniqid(rand(), true)), 0, 5);
		$password	= sha1($input['password'] . $user_salt);
		
		$insert = array('name'		=>	$input['name'],
						'ref'		=>	$input['ref'],
						'domain' 	=> 	$input['domain'],
						'created_on'=>	time()
						);
		
		$user = array('user_name'		=>	$input['user_name'],
					  'first_name'		=>	$input['first_name'],
					  'last_name'		=>	$input['last_name'],
					  'email'			=>	$input['email'],
					  'password'		=>	$password,
					  'salt'			=>	$user_salt
					  );
				
		if ($this->insert($insert))
		{
			if($this->_make_folders($insert['ref']))
			{
				// Install all modules
				$this->db->set_dbprefix($insert['ref'].'_');
				if ($this->module_import->import_all())
				{
					$this->dbforge->add_field(array(
						'version' => array('type' => 'INT', 'constraint' => 3),
					));
		
					$this->dbforge->create_table('schema_version', TRUE);
		
					if ($this->db->insert('schema_version', array('version' => config_item('migrations_version'))) )
					{
						return $this->users_m->create_default_user($user);
					}
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * Edit a site
	 *
	 * @param	array	$input	The post data
	 * @param	array	$site	The old site data
	 * @return	bool
	 */
	public function edit_site($input, $site)
	{
		$insert = array('name'		=>	$input['name'],
						'ref'		=>	$input['ref'],
						'domain' 	=> 	$input['domain'],
						'updated_on'=>	time()
						);
		
		$user = array('id'			=>	$input['user_id'],
					  'email'		=>	$input['email'],
					  'username'	=>	$input['user_name'],
					  'first_name'	=>	$input['first_name'],
					  'last_name'	=>	$input['last_name']
		);
		
		if($input['password'] > '')
		{
			$user_salt	= substr(md5(uniqid(rand(), true)), 0, 5);
			$password	= sha1($input['password'] . $user_salt);
		
			$user['password'] 	= $password;
			$user['salt']		= $user_salt;
		}
		
		if ($this->update($input['id'], $insert) AND
			$this->users_m->update_default_user($site->ref, $user)
			)
		{
			// make sure there aren't orphaned folders from a previous install
			if ($insert['ref'] != $site->ref)
			{
				foreach ($this->locations AS $folder_check => $sub_folders)
				{
					if (is_dir($folder_check.'/'.$insert['ref']))
					{
						$this->session->set_flashdata('error', sprintf(lang('site.folder_exists'),
																	   $folder_check.'/'.$insert['ref']));
						redirect('sites/edit/'.$input['id']);
					}
				}
				
				return $this->_rename_folders($input['ref'], $site->ref);
			}
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Delete A Site
	 *
	 * @param	int		$id		The id of the site
	 * @param	object	$site	The site data
	 * @return	bool
	 */
	public function delete_site($id, $site)
	{
		$unwritable = array();
		$tables = $this->db->list_tables();

		// drop the db record
		if ($this->delete($id) AND strlen($site->ref) >= 4)
		{
			// now drop the site's own tables
			foreach ($tables AS $table)
			{
				// only delete the table if it starts with our prefix
				if (strpos($table, $site->ref.'_') === (int) 0)
				{
					$this->db->query("DROP TABLE IF EXISTS `".$table."`");
				}
			}
			
			// get rid of all folders
			foreach ($this->locations AS $root => $sub)
			{
				if (is_really_writable($root.'/'.$site->ref))
				{
					$this->_remove_tree($root.'/'.$site->ref);
				}
				else
				{
					$unwriteable[] = $root.'/'.$site->ref;
				}
			}
		}
		return (count($unwritable) > 0) ? $unwritable : TRUE;
	}
	
	/**
	 * Create a new site's folder set
	 *
	 * return TRUE on success or array of failed folders
	 *
	 * @param	string	$new_ref	The new site ref
	 * @return	boolean
	 */
	private function _make_folders($new_ref)
	{
		$unwritable = array();
		
		foreach ($this->locations AS $location => $sub_folders)
		{
			//check perms and log the failures
			if ( ! is_really_writable($location))
			{
				if (is_array($sub_folders))
				{
					foreach ($sub_folders AS $folder)
					{
						$unwritable[] = $location.'/'.$new_ref.'/'.$folder.'/index.html';
					}
				}
				else
				{
					$unwritable[] = $location.'/'.$new_ref.'/index.html';
				}
			}
			// it's writable, time to create
			else
			{
				if (count($sub_folders) > 0)
				{
					foreach ($sub_folders AS $folder)
					{
						if ( ! is_dir($location.'/'.$new_ref.'/'.$folder))
						{
							@mkdir($location.'/'.$new_ref.'/'.$folder, 0777, TRUE);
							write_file($location.'/'.$new_ref.'/'.$folder.'/index.html', '');
							write_file($location.'/'.$new_ref.'/index.html', '');
						}
					}
				}
				else
				{
					if ( ! is_dir($location.'/'.$new_ref))
					{
						@mkdir($location.'/'.$new_ref, 0777, TRUE);
						write_file($location.'/'.$new_ref.'/index.html', '');
					}
				}
			}
		}
		return (count($unwritable) > 0) ? $unwritable : TRUE;
	}
	
	/**
	 * Rename an array of folders
	 *
	 * return TRUE on success or array of failed folders
	 *
	 * @param	string	$new_ref	The new site ref
	 * @param	string	$old_ref	The old site's ref
	 * @return	boolean
	 */
	private function _rename_folders($new_ref, $old_ref)
	{
		$unwritable = array();
		
		foreach ($this->locations AS $location => $sub_folders)
		{
			if ( ! is_really_writable($location.'/'.$old_ref))
			{
				// log it so we can tell them to do it manually
				$unwritable[$location.'/'.$old_ref] = $location.'/'.$new_ref;
			}
			else
			{
				rename($location.'/'.$old_ref, $location.'/'.$new_ref);
			}
		}
		return (count($unwritable) > 0) ? $unwritable : TRUE;
	}
	
	/**
	 * Remove the folders when a site is deleted
	 *
	 * @param string $ref The site ref to delete
	 * @return bool
	 */
	private function _remove_tree($root)
	{
		$status = array();

        if(is_file($root))
		{
           @unlink($root);
        }
        elseif(is_dir($root))
		{
            $scan = glob(rtrim($root,'/').'/*');
			foreach($scan AS $index => $path)
			{
				$this->_remove_tree($path);
            }
			@rmdir($root);
        }
	}
}
