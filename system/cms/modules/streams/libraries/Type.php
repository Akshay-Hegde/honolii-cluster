<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Field Type Library
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Type
{
	/**
	 * We build up these assets for the footer
	 */
	public $assets = array();

    function __construct()
    {
		$this->CI =& get_instance();
		
		$this->CI->load->helper( 'directory' );
		
		$this->types = new stdClass;
		
		// These have variable definitions in PyroStreams,
		// usually, but since this is core we'll set them here
		
		define('PYROSTREAMS_DB_PRE', SITE_REF.'_');
		define('PYROSTEAMS_DIR', APPPATH.'modules/streams/');
		define('PYROSTEAMS_FT_DIR', ADDONPATH);
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
		// -------------------------------------
		// Get Core Field Types
		// -------------------------------------
		
		$types_files = directory_map(PYROSTEAMS_DIR.'field_types/', 1);

		$this->_load_types($types_files, PYROSTEAMS_DIR.'field_types/', $mode = 'core');
	
		// -------------------------------------
		// Get Add-on Field Types
		// -------------------------------------
		
		if( is_dir(PYROSTEAMS_FT_DIR.'field_types/') ):

			$types_files = directory_map(PYROSTEAMS_FT_DIR.'field_types/', 1);
			
			if( is_array($types_files) && !empty($types_files) ):
		
				$this->_load_types($types_files, PYROSTEAMS_FT_DIR.'field_types/', $mode = 'addon');
			
			endif;
		
		endif;
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
		foreach( $types_files as $type ):
		
			// Is this a directory w/ a field type?
			if(is_dir($addon_path.$type) and is_file($addon_path.$type.'/field.'.$type.'.php')):
			
				require_once($addon_path.$type.'/field.'.$type.'.php');

				$class_name = 'Field_'.$type;
					
				$this->types->$type = new $class_name();

				$this->types->$type->ft_mode = $mode;
				
			elseif(is_file($addon_path.$type)):
			
				$items = explode('.', $type);	
				
				if(count($items == 3) and $items[0]=='field'):
				
					// We'll still let the file ones work
					require_once($addon_path.$type);

					$class_name = 'Field_'.$items[1];
					
					if(class_exists($class_name)):
	
						$this->types->{$items[1]} = new $class_name();
						
						$this->types->{$items[1]}->ft_mode = $mode;
					
					endif;
			
				endif;		
							
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
		$addon_paths = array(
			'core' => PYROSTEAMS_DIR.'field_types/',
			'addon' => PYROSTEAMS_FT_DIR.'field_types/'
		);
		
		foreach($addon_paths as $mode => $addon_path):
				
			// Is this a directory w/ a field type?
			if(is_dir($addon_path.$type) and is_file($addon_path.$type.'/field.'.$type.'.php')):
			
				require_once($addon_path.$type.'/field.'.$type.'.php');

				$class_name = 'Field_'.$type;
					
				$c = new $class_name();

				$c->ft_mode = $mode;
				
				return $c;
				
			elseif(is_file($addon_path.'field.'.$type.'.php')):
			
				// We'll still let the file ones work
				require_once($addon_path.'field.'.$type.'.php');

				$class_name = 'Field_'.$item[1];
				
				if(class_exists($class_name)):

					$c = new $class_name();
				
				endif;
							
				$c->ft_mode = $mode;
				
				return $c;

			endif;				
				
		endforeach;
		
		return NULL;
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
		if($this->types->$type->ft_mode == 'core'):
		
			$path = PYROSTEAMS_DIR."field_types/$type/";
		
		else:

			$path = PYROSTEAMS_FT_DIR."field_types/$type/";
		
		endif;
		
		// Thanks MY_Loader!
		$this->CI->load->set_view_path($path.'views/');		
				
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