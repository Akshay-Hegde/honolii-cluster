<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * PyroStreams Event Class
 *
 * Currently, this plugs into the admin_notification to use PyroStreams validation.
 * 
 * @package		PyroStreams
 * @category	events
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Events_Streams {
    
    protected $CI;
 
  	// --------------------------------------------------------------------------
   
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // Register the admin_notification event
        Events::register('admin_notification', array($this, 'display_notifications'));
    }
 
 	// --------------------------------------------------------------------------
   
    /**
     * Display PyroStreams custom notifications
     *
     * @access	public
     * @return	void
     */
    public function display_notifications()
    {
    	if(class_exists('Streams_validation')):
    	
			if ($this->CI->streams_validation->error_string()):
			
				echo '<div class="alert error">'.
					$this->CI->streams_validation->error_string().
					'</div>';
						
			endif;

		endif;
    }

}