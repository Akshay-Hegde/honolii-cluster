<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * This is the multi-site management module
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS Premium
 * @subpackage 	Site Manager Module
 */

// we're simply giving widgets and themes something to extend
// so we can easily open them to get their info
class Widgets extends MY_Model {};
class Theme extends MY_Model {};

class Addons_m extends MY_Model
{
	protected $_table = 'modules';
	private $_module_exists = array();

	/**
	 * Constructor method
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Check to see if an addon already exists
	 *
	 * @param string	$slug
	 * @return bool
	 */
	public function exists($slug)
	{
		$shared_path 	= SHARED_ADDONPATH.$this->type.'s/'.$slug;
		$addon_path 	= ADDON_FOLDER.$this->ref.'/'.$this->type.'s/'.$slug;
	
		if (($this->type == 'widget' OR
			$this->type == 'module') AND
			$this->db->where('slug', $slug)->count_all_results($this->type.'s') > 0)
		{
			return true;
		}
		elseif (is_dir($shared_path) OR is_file($shared_path))
		{
			return true;
		}
		elseif (is_dir($addon_path) OR is_file($addon_path))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Add
	 *
	 * Adds a module to the database
	 *
	 * @access	public
	 * @param	array	$module		Information about the module
	 * @return	object
	 */
	public function add_module($module)
	{
		return $this->db->insert($this->_table, array(
			'name'			=> serialize($module['name']),
			'slug'			=> $module['slug'],
			'version'		=> $module['version'],
			'description'	=> serialize($module['description']),
			'skip_xss'		=> ! empty($module['skip_xss']),
			'is_frontend'	=> ! empty($module['frontend']),
			'is_backend'	=> ! empty($module['backend']),
			'menu'			=> ! empty($module['menu']) ? $module['menu'] : false,
			'enabled'		=> ! empty($module['enabled']),
			'installed'		=> ! empty($module['installed']),
			'is_core'		=> ! empty($module['is_core'])
		));
	}

	/**
	 * Delete
	 *
	 * Delete an addon from the database
	 *
	 * @access	public
	 * @return	object
	 */
	public function delete($id = false)
	{
		return $this->db->delete($this->type.'s', array('slug' => $this->slug));
	}

	/**
	 * Enable
	 *
	 * Enables an addon
	 *
	 * @return	bool
	 */
	public function enable()
	{
		$insert = array('enabled' => 1);
		
		// if it's a widget we may have to add it to the database also
		if ($this->type == 'widget')
		{
			// there isn't a record yet, we need to insert it
			if ($this->db->where('slug', $this->slug)->count_all_results($this->type.'s') == 0);
			{
				if ( ! $widget_class = $this->_spawn_class($this->type, $this->slug, $this->shared))
				{
					return false;
				}
	
				// Get some basic info from the file
				$insert['slug']			= $this->slug;
				$insert['title']		= serialize($widget_class->title);
				$insert['description']	= serialize($widget_class->description);
				$insert['author']		= $widget_class->author;
				$insert['website']		= $widget_class->website;
				$insert['version']		= $widget_class->version;
				
				// Insert and enable it
				return $this->db->insert($this->type.'s', $insert);
			}
			
			// Enable it
			return $this->db->where('slug', $this->slug)
				->update($this->type.'s', $insert);
		}
		
		return $this->db->where('slug', $this->slug)
			->update($this->type.'s', $insert);
	}

	/**
	 * Disable
	 *
	 * Disables an addon
	 *
	 * @return	bool
	 */
	public function disable()
	{
		return $this->db->where('slug', $this->slug)
			->update($this->type.'s', array('enabled' => 0));
	}

	/**
	 * Install
	 *
	 * Installs a module
	 *
	 * @return	bool
	 */
	public function install()
	{	
		if ( ! $details_class = $this->_spawn_class($this->type, $this->slug, $this->shared))
		{
			return false;
		}
		
		if ($this->db->where('slug', $this->slug)->count_all_results($this->type.'s') == 0)
		{
			// Get some info for the db
			$module = $details_class->info();
		
			// Now lets set some details ourselves
			$module['slug']			= $this->slug;
			$module['version']		= $details_class->version;
			$module['enabled']		= false;
			$module['installed']	= true;
			$module['is_core']		= false;
		
			// It's a valid module let's make a record of it
			$this->add_module($module);
		}
		else
		{
			$this->db->where('slug', $this->slug)
				->update($this->type.'s', array('installed' => 1));
		}
		
		// set the site_ref and upload_path for third-party devs
		$details_class->site_ref 	= $this->ref;
		$details_class->upload_path	= 'uploads/'.$this->ref.'/';
		
		// Run the install method to get it into the database
		return $details_class->install();
	}

	/**
	 * Uninstall
	 *
	 * Uninstalls a module
	 *
	 * @return	bool
	 */
	public function uninstall()
	{		
		if ( ! $details_class = $this->_spawn_class($this->type, $this->slug, $this->shared))
		{
			// the files are missing so let's clean the table
			return $this->delete();
		}
		
		// set the site_ref and upload_path for third-party devs
		$details_class->site_ref 	= $this->ref;
		$details_class->upload_path	= 'uploads/'.$this->ref.'/';

		// Run the uninstall method to drop the module's tables
		if ( ! $details_class->uninstall())
		{
			return false;
		}

		if ($this->delete())
		{
			// Get some info for the db
			$module = $details_class->info();
	
			// Now lets set some details ourselves
			$module['slug']			= $this->slug;
			$module['version']		= $details_class->version;
			$module['enabled']		= false;
			$module['installed']	= false;
			$module['is_core']		= false;
	
			// We record it again here. If they really want to get rid of it they'll use Delete
			return $this->add_module($module);
		}
		return false;
	}
	
	/**
	 * Upgrade
	 *
	 * Upgrade a module
	 *
	 * @return	bool
	 */
	public function upgrade()
	{
		// Get info on the new module
		if ( ! $details_class = $this->_spawn_class($this->type, $this->slug, $this->shared ))
		{
			return false;
		}
		
		// Get info on the old module
		if ( ! $old_module = $this->db->where('slug', $this->slug)->get('modules')->row_array() )
		{
			return false;
		}
		
		// Get the old module version number
		$old_version = $old_module['version'];
		
		// set the site_ref and upload_path for third-party devs
		$details_class->site_ref 	= $this->ref;
		$details_class->upload_path	= 'uploads/'.$this->ref.'/';

		// Run the upgrade method to get it into the database
		if ($details_class->upgrade($old_version))
		{
			// Update version number
			$this->db->where('slug', $this->slug)
				->update('modules', array('version' => $details_class->version));
			
			return true;
		}
		
		// The upgrade failed
		else
		{
			return false;
		}
	}
	
	public function index_plugins()
    {
    	$plugins = array();
		$i = 0;

		foreach (array(SHARED_ADDONPATH) AS $directory)
    	{
    		if ($plugins_array = glob($directory.'plugins/*.php', GLOB_NOSORT))
    		{
				foreach ($plugins_array AS $location)
				{
					$plugin = basename($location);

					// This isn't really a file
					if ( ! is_file($location))
					{
						continue;
					}
					
					$name = str_replace('.php', '', $plugin);

					// Get some basic info from the file
					$plugins[$i]['name']		= ucfirst($name);
					$plugins[$i]['slug']		= $name;
					$plugins[$i]['shared']		= true;
					
					$i++;
				}
			}
		}

		return $plugins;
	}
	
	public function index_themes()
    {
    	$themes = array();
		$shared = false;
		$i = 0;

		foreach (array(ADDON_FOLDER.$this->ref.'/', SHARED_ADDONPATH) AS $directory)
    	{
    		if ($themes_array = glob($directory.'themes/*', GLOB_ONLYDIR))
    		{
				foreach ($themes_array as $theme_name)
				{
					$slug = basename($theme_name);

					// This doesnt have a valid theme file! :o
					if ( ! $theme_class = $this->_spawn_class('theme', $slug, $shared))
					{
						continue;
					}

					// Get some basic info from the file
					$themes[$i]['name']			= $theme_class->name;
					$themes[$i]['description']	= $theme_class->description;
					$themes[$i]['slug']			= $slug;
					$themes[$i]['version']		= $theme_class->version;
					$themes[$i]['shared']		= $shared;
					
					$i++;
				}
			}

			// Going back around, 2nd time is shared_addons
			$shared = true;
		}

		return $themes;
	}
	
	public function index_widgets()
    {
    	$widgets = array();
		$shared = false;
		$i = 0;

		foreach (array(ADDON_FOLDER.$this->ref.'/', SHARED_ADDONPATH) AS $directory)
    	{
    		if ($widgets_array = glob($directory.'widgets/*', GLOB_ONLYDIR))
    		{
				foreach ($widgets_array as $widget_name)
				{
					$slug = basename($widget_name);

					// This doesnt have a valid widget file! :o
					if ( ! $widget_class = $this->_spawn_class('widget', $slug, $shared))
					{
						continue;
					}

					// Get some basic info from the file
					$widgets[$i]['title']		= $widget_class->title;
					$widgets[$i]['description']	= $widget_class->description;
					$widgets[$i]['slug']		= $slug;
					$widgets[$i]['version']		= $widget_class->version;
					$widgets[$i]['shared']		= $shared;
					
					// Find out what the site knows about it
					$widgets[$i]['database']	= $this->db->where('slug', $slug)
													->get($this->ref.'_widgets')
													->row_array();
					$i++;
				}
			}

			// Going back around, 2nd time is shared_addons
			$shared = true;
		}

		return $widgets;
	}
	
	public function index_modules()
    {
    	$modules = array();
		$shared = false;
		$i = 0;

		foreach (array(ADDON_FOLDER.$this->ref.'/', SHARED_ADDONPATH) AS $directory)
    	{
    		if ($modules_array = glob($directory.'modules/*', GLOB_ONLYDIR))
    		{
				foreach ($modules_array as $module_name)
				{
					$slug = basename($module_name);

					// This doesnt have a valid details.php file! :o
					if ( ! $details_class = $this->_spawn_class('module', $slug, $shared))
					{
						continue;
					}

					// Get some basic info from the file
					$modules[$i]['info']		= $details_class->info();
					$modules[$i]['slug']		= $slug;
					$modules[$i]['version']		= $details_class->version;
					$modules[$i]['shared']		= $shared;
					
					// Find out what the site knows about it
					$modules[$i]['database']	= $this->db->where('slug', $slug)
													->get($this->ref.'_modules')
													->row_array();
					$i++;
				}
			}

			// Going back around, 2nd time is shared_addons
			$shared = true;
		}

		return $modules;
	}


	/**
	 * Spawn Class
	 *
	 * Checks to see if a [type].php file exists and returns a class
	 *
	 * @param	string	$type		The type of addon
	 * @param	string	$slug		The folder name of the module
	 * @param	string	$shared		Whether the addon is in the shared folder or not
	 * @access	private
	 * @return	array
	 */
	private function _spawn_class($type, $slug, $shared = false)
	{
		$path = $shared ? SHARED_ADDONPATH : ADDON_FOLDER.$this->ref.'/';

		switch($type)
		{
			case 'module':
				$file 			= 'details';
				$class_segment 	= 'Module_';
				break;
			case 'widget':
				$file 			= $slug;
				$class_segment 	= 'Widget_';
				break;
			case 'theme':
				$file 			= 'theme';
				$class_segment 	= 'Theme_';
		}
		
		$details_file = $path . $type . 's/' . $slug . '/' . $file.EXT;
		
		// Check to make sure some loon didn't ftp the same addon to both addons & shared_addons
		if ( class_exists($class_segment.ucfirst(strtolower($slug))) )
		{
			$this->template->set('messages', array('error' => sprintf(lang('site:addon_duplicate'), $slug)));
			return false;
		}

		// Check if the details file exists
		if ( ! is_file($details_file))
		{
			return false;
		}

		// Sweet, include the file
		include_once $details_file;

		// Now call the details class
		$class = $class_segment.ucfirst(strtolower($slug));

		// Now we need to talk to it
		return class_exists($class) ? new $class : false;
	}
}