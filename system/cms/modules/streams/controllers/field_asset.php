<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Field Asset Controller
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_asset extends Public_Controller {

	var $path;
	
	var $field_type;

	// --------------------------------------------------------------------------

    function __construct()
    {
        parent::__construct();
        
        $this->load->library('streams/Type');
        
        $this->load->helper('file');
    }
 
 	// --------------------------------------------------------------------------
   
   	/**
   	 * Remap based on URL call
   	 */
    function _remap($method)
    {
    	$this->path = '';
    
    	// Check the type
    	$type = $this->uri->segment(4);
    	
    	$this->field_type = $this->type->load_single_type($type);
    	
    	// Check the file
    	$file = $this->uri->segment(5);
    	
    	if(trim($file) == '') return;
    	
  		$file = $this->security->sanitize_filename($file);

		// Set the path
    	// *Note - we will assume the types are in a folder here
    	if($this->field_type->ft_mode == 'core'):
    	
    		$this->path = PYROSTEAMS_DIR.'field_types/'.$type.'/';
    	
    	else:
    	
    		$this->path = PYROSTEAMS_FT_DIR.'field_types/'.$type.'/';
    	
    	endif;
    	
    	// Call the method
    	if($method == 'css'):
    	
    		$this->_css($file);
    	
    	elseif($method == 'js'):
 
     		$this->_js($file);
   	
    	endif;
    }

	// --------------------------------------------------------------------------

    /**
     * Pull CSS
     *
     * @access	private
     * @param	string - css file name
     * @return	void
     */
    private function _css($file)
    {
    	header("Content-Type: text/css");
    	
    	$file = $this->path.'css/'.$file;
    	
   	 	if(!is_file($file)) return;
   	 	
		echo read_file($file);   	 	
    }
  
  	// --------------------------------------------------------------------------

    /**
     * Pull JS
     *
     * @access	private
     * @param	string - css file name
     * @return	void
     */
    private function _js($file)
    {
    	header("Content-Type: text/javascript");
    	
    	$file = $this->path.'js/'.$file;
    	
   	 	if(!is_file($file)) return;
   	 	
		echo read_file($file);   	 	
    }
  
}

/* End of file field_asset.php */