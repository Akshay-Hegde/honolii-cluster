<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Field Type Library
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Type
{
	/**
	 * We build up these assets for the footer
	 *
	 * @access	public
	 * @var		array
	 */
	public $assets = array();

	// --------------------------------------------------------------------------
	
	/**
	 * Places where our field types may be
	 *
	 * @access	public
	 * @var		array
	 */
	public $addon_paths = array();
	
	// --------------------------------------------------------------------------
	
	/**
	 * Current language (folder name)
	 *
	 * @access	public
	 * @var		string
	 */
	public $current_lang = 'english';

	// --------------------------------------------------------------------------

    function __construct()
    {    
		$this->CI =& get_instance();
		
		$this->CI->load->helper('directory');
		$this->CI->load->config('language');

		// Get Lang (full name for language file)
		// This defaults to english.
		$langs = $this->CI->config->item('supported_languages');
		
		if(isset($langs[CURRENT_LANGUAGE])) $this->current_lang = $langs[CURRENT_LANGUAGE]['folder'];
		
		// Obj to hold all our field types
		$this->types = new stdClass;
		
		// We either need a prefix or not
		// This is for legacy and if any 3rd party
		// field types use this constant
		(CMS_VERSION < 1.3) ? define('PYROSTREAMS_DB_PRE', '') : define('PYROSTREAMS_DB_PRE', SITE_REF.'_');
		
		// Where oh where is PyroStreams?
		// We'll find out with an ugly series of if statements
		if(is_dir(APPPATH.'modules/streams/')):
			
			// This is a core module. Must be PyroCMS pro edition. Good choice!
			define('PYROSTEAMS_DIR', APPPATH.'modules/streams/');
			
		elseif(is_dir(ADDONPATH.'modules/streams/')):
		
			define('PYROSTEAMS_DIR', ADDONPATH.'modules/streams/');
			
		elseif(defined('SHARED_ADDONPATH') and is_dir(SHARED_ADDONPATH.'modules/streams')):
		
			define('PYROSTEAMS_DIR', SHARED_ADDONPATH.'modules/streams/');
			
		else:
		
			show_error(lang('streams.cannot_find_pyrostreams'));
			
		endif;

		// Set our addon paths
		$this->addon_paths = array(
			'core' 			=> PYROSTEAMS_DIR.'field_types/',
			'addon' 		=> ADDONPATH.'field_types/',
			'addon_alt' 	=> SHARED_ADDONPATH.'field_types/'
		);
		
		// Go ahead and gather our types
		$this->gather_types();
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get the types together as a big object
	 *
	 * @access	public
	 * @return	void
	 */
	public function gather_types()
	{
		foreach($this->addon_paths as $raw_mode => $path):
		
			if(!is_dir($path)) continue;
		
			$types_files = directory_map($path, 1);
	
			($raw_mode == 'core') ? $mode = 'core' : $mode = 'addon';
	
			$this->_load_types($types_files, $path, $mode);
			
			unset($types_files);
		
		endforeach;	
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Load types
	 *
	 * @access	private
	 * @param	array
	 * @param	string
	 * @return	void
	 */	
	private function _load_types($types_files, $addon_path, $mode = 'core')
	{
		foreach($types_files as $type):
		
			// Is this a directory w/ a field type?
			if(is_dir($addon_path.$type) and is_file($addon_path.$type.'/field.'.$type.'.php')):
			
				$this->types->$type = $this->_load_type($addon_path, 
									$addon_path.$type.'/field.'.$type.'.php',
									$type,
									$mode);
								
			elseif(is_file($addon_path.'field.'.$type.'.php')):
			
				$this->types->$type = $this->_load_type($addon_path, 
									$addon_path.'field.'.$type.'.php',
									$type,
									$mode);
															
			endif;

		endforeach;
	}

	// --------------------------------------------------------------------------

	/**
	 * Load single type
	 *
	 * @access	public
	 * @param	string - type name
	 * @return	obj or null
	 */	
	public function load_single_type($type)
	{
		foreach($this->addon_paths as $mode => $path):
				
			// Is this a directory w/ a field type?
			if(is_dir($path.$type) and is_file($path.$type.'/field.'.$type.'.php')):
			
				return $this->_load_type($path, 
									$path.$type.'/field.'.$type.'.php',
									$type,
									$mode);		
				
			elseif(is_file($path.'field.'.$type.'.php')):
			
				return $this->_load_type($path, 
									$path.'field.'.$type.'.php',
									$type,
									$mode);		

			endif;				
							
		endforeach;
		
		return NULL;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Load the actual field type into the
	 * types object
	 *
	 * @access	private
	 * @param	string - addon path
	 * @param	string - path to the file (with the file name)
	 * @param	string - the field type
	 * @param	string - mode
	 * @return	obj - the type obj
	 */
	private function _load_type($path, $file, $type, $mode)
	{
		// -------------------------
		// Load the language file
		// -------------------------

		if(is_dir($path.$type.'/language')):
		
			$lang = $this->current_lang;
		
			// Fallback on English
			if(!is_dir($path.$type.'/language/'.$lang)) $lang = 'english';
						
			$this->CI->lang->load($type, $lang, FALSE, TRUE, $path.$type.'/');
			
			unset($lang);
		
		endif;

		// -------------------------
		// Load file
		// -------------------------

		require_once($file);
		
		$tmp = new stdClass;
	
		$class_name = 'Field_'.$type;
		
		if(class_exists($class_name)):
	
			$tmp = new $class_name();
			
			// Set some ft class vars
			$tmp->ft_mode 		= $mode;
			$tmp->ft_root_path 	= $path;
			$tmp->ft_path 		= $path.'/'.$type.'/';
			
			// And give us a CI instance
			$tmp->CI			= get_instance();
			
			// Field type name is languagized
			if(!isset($tmp->field_type_name)):
			
				$tmp->field_type_name = $this->CI->lang->line('streams.'.$type.'.name');
			
			endif;
		
		endif;
	
		return $tmp;
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Add a field type CSS file
	 */
	public function add_css($field_type, $file)
	{
		$html = '<link href="'.site_url('streams/field_asset/css/'.$field_type.'/'.$file).'" type="text/css" rel="stylesheet" />';
	
		$this->CI->template->append_metadata($html);
		
		$this->assets[] = $html;	
	}

	// --------------------------------------------------------------------------

	/**
	 * Add a field type JS file
	 */
	public function add_js($field_type, $file)
	{
		$html = '<script type="text/javascript" src="'.site_url('streams/field_asset/js/'.$field_type.'/'.$file).'"></script>';
	
		$this->CI->template->append_metadata($html);
		
		$this->assets[] = $html;	
	}

	// --------------------------------------------------------------------------

	/**
	 * Add a field type JS file
	 */
	public function add_misc($html)
	{	
		$this->CI->template->append_metadata($html);
		
		$this->assets[] = $html;	
	}

	// --------------------------------------------------------------------------

	/**
	 * Load a view from a field type
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 */
	public function load_view($type, $view_name, $data = array())
	{	
		// Thanks MY_Loader!
		$this->CI->load->set_view_path($this->types->$type->ft_path.'views/');		
				
		return $view_data = $this->CI->load->_ci_load(array('_ci_view' => $view_name, '_ci_vars' => $this->object_to_array($data), '_ci_return' => true));
	}

	// --------------------------------------------------------------------

	/**
	 * Object to Array
	 *
	 * Takes an object as input and converts the class variables to array key/vals
	 *
	 * From CodeIgniter's Loader class a moved over here since it was protected.
	 *
	 * @param	object
	 * @return	array
	 */
	protected function object_to_array($object)
	{
		return (is_object($object)) ? get_object_vars($object) : $object;
	}

	// --------------------------------------------------------------------------

	/**
	 * Load crud assets for all field crud assets
	 *
	 * @access	public
	 * @return	void
	 */	
	public function load_field_crud_assets()
	{
		foreach($this->types as $type):
		
			if(method_exists($type, 'add_edit_field_assets')):
			
				$type->add_edit_field_assets();
			
			endif;
		
		endforeach;
	}

}

/* End of file Type.php */