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
	 * Create a new site
	 *
	 * @param	array	$input	The post data
	 * @return	bool
	 */
	public function create_site($input)
	{
		$insert = array('name'		=>	$input['name'],
						'ref'		=>	$input['ref'],
						'domain' 	=> 	$input['domain'],
						'created_on'=>	time()
						);
				
		if ($this->insert($insert))
		{
			return $this->_make_folders($insert['ref']);
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
		
		if ($this->update($input['id'], $insert))
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

		// drop the db record
		if ($this->delete($id) AND strlen($site->ref) >= 4)
		{
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
