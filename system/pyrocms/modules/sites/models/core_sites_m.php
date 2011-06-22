<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */
class Core_sites_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get the info for a single site
	 *
	 * @param	int		$id	The site's id
	 * @return	mixed
	 */
	public function get_site($id)
	{
		return $this->db->query('SELECT * FROM core_sites WHERE id='.$id)->row();
	}

	/**
	 * Get a list of all installed sites
	 *
	 * @return	mixed
	 */
	public function get_sites()
	{
		return $this->db->query('SELECT * FROM core_sites')->result();
	}
	
	/**
	 * Create a new site
	 *
	 * @param	array	$input	The post data
	 * @return	bool
	 */
	public function create_site($input)
	{
		$this->load->helper('file');
		
		$folders = array();
		$message = '';
		$abort = FALSE;
		
		// strip the running installation's slug from the path and use what's left
		$addons 	= trim(str_replace(SITE_SLUG, $input['domain'], ADDONPATH), '/');
		$uploads 	= trim(str_replace(SITE_SLUG, $input['ref'], UPLOAD_PATH), '/');
		$cache 		= APPPATH.'cache/'.$input['ref'];
		
		// addons status messages
		$folders[$addons.'modules'] = @mkdir($addons.'modules', DIR_WRITE_MODE, TRUE);
		$folders[$addons.'themes']	= @mkdir($addons.'themes', DIR_WRITE_MODE, TRUE);
		$folders[$addons.'widgets'] = @mkdir($addons.'widgets', DIR_WRITE_MODE, TRUE);
		
		// uploads status messages
		$folders[$uploads] = @mkdir($uploads, DIR_WRITE_MODE, TRUE);
		
		// cache status messages
		$folders[$cache] = @mkdir($cache, DIR_WRITE_MODE, TRUE);

		foreach ($folders AS $folder => $status)
		{
			// if one of them could not be created then log the message and quit
			if ( ! $status)
			{
				$message .= '<li>'.$folder.'</li>';
				$abort = TRUE;
			}
		}

		// if the directories created ok then make the record
		if ( ! $abort)
		{
			// we just created the folders so let's throw some html files in them
			write_file($addons.'modules/index.html');
			write_file($addons.'themes/index.html');
			write_file($addons.'widgets/index.html');
			write_file($uploads.'/index.html');
			write_file($cache.'/index.html');
			
			return $this->db->query(sprintf(
				'INSERT INTO core_sites (name, ref, domain, created_on)
				VALUES ("%s", "%s", "%s", "%s");',
					$input['name'],
					$input['ref'],
					$input['domain'],
					now()));
		}
		return '<ul>' . sprintf(lang('site.chmod_error'), $message) . '</ul>';
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
		$folders = array();
		$message = '';
		$abort = FALSE;
		
		// strip the running installation's slug from the path and use what's left
		$old_addons 	= trim(str_replace(SITE_SLUG, $site->domain, ADDONPATH), '/');
		$old_uploads 	= trim(str_replace(SITE_SLUG, $site->ref, UPLOAD_PATH), '/');
		$old_cache 		= APPPATH.'cache/'.$site->ref;

		$new_addons 	= trim(str_replace(SITE_SLUG, $input['domain'], ADDONPATH), '/');
		$new_uploads 	= trim(str_replace(SITE_SLUG, $input['ref'], UPLOAD_PATH), '/');
		$new_cache 		= APPPATH.'cache/'.$input['ref'];
		
		$query_status = $this->db->query(sprintf(
			"UPDATE core_sites SET name='%s', ref='%s', domain='%s', created_on='%s'
			WHERE id=".$input['id'].";",
				$input['name'],
				$input['ref'],
				$input['domain'],
				now()));
		
		if ($query_status === TRUE)
		{
			$folders[$old_addons. ' > ' .$new_addons] 	= rename($old_addons, $new_addons);
			$folders[$old_uploads. ' > ' .$new_uploads] = rename($old_uploads, $new_uploads);
			$folders[$old_cache. ' > ' .$new_cache]		= rename($old_cache, $new_cache);
			
			// check to see how many need to be renamed manually
			foreach ($folders AS $folder => $status)
			{
				if (! $status)
				{
					$message .= '<li>'.$folder.'</li>';
					$abort = TRUE;
				}
			}
			
			// everything passed with flying colors
			if ( ! $abort)
			{
				return TRUE;
			}
			
			return '<ul>' . sprintf(lang('site.rename_notice'), $message) . '</ul>';
		}
		return FALSE;
	}
	
	/**
	 * Delete A Site
	 *
	 * @param	int		$id		The id of the site
	 * @param	array	$site	The site data
	 * @return	bool
	 */
	public function delete($id, $site)
	{
		$message = '';
		
		// strip the running installation's slug from the path and use what's left
		$addons 	= trim(str_replace(SITE_SLUG, $site->domain, ADDONPATH), '/');
		$uploads 	= trim(str_replace(SITE_SLUG, $site->ref, UPLOAD_PATH), '/');
		$cache 		= APPPATH.'cache/'.$site->ref;
		
		// addons status messages
		$folders[$addons] = $this->_remove_tree($addons);
		
		// uploads status messages
		$folders[$uploads] = $this->_remove_tree($uploads);
		
		// cache status messages
		$folders[$cache] = $this->_remove_tree($cache);

		// run through the top level folders and check their error messages
		foreach ($folders AS $folder => $files)
		{
			// if one of them is not writable then log the message and run
			if ( in_array(FALSE, $files))
			{
				$message .= '<li>'.$folder.'</li>';
				$abort = TRUE;
			}
		}
	
		$this->db->query("DELETE FROM core_sites WHERE id=".$id.";");

		if ($abort)
		{
			return sprintf(lang('site.site_delete_error'), $message);
		}
		else
		{
			return TRUE;
		}
	}
	
	/**
	 * Remove the folders when a site is deleted
	 *
	 * @param string $root The folder to delete
	 * @return bool
	 */
	private function _remove_tree($root)
	{
		$status = array();
		
        if(is_file($root))
		{
            $status[] = @unlink($root);
        }
        elseif(is_dir($root))
		{
            $scan = glob(rtrim($root,'/').'/*');
			foreach($scan AS $index => $path)
			{
				$status[] = $this->_remove_tree($path);
            }
            $status[] = @rmdir($root);
        }
		return $status;
	}
}
