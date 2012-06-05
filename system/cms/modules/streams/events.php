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

        // Delete the row_m and streams cache on create/update/delete
        Events::register('streams_post_insert_entry', array($this, 'clear_cache'));
        Events::register('streams_post_update_entry', array($this, 'clear_cache'));
        Events::register('streams_post_delete_entry', array($this, 'clear_cache'));

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

    // --------------------------------------------------------------------------

    /**
     * Empty PyroStreams Cache 
     *
     * Both the tag cache and the row_m cache.
     *
     * @access  public
     * @return  void
     */
    public function clear_cache()
    {
        $this->CI->pyrocache->delete_all('row_m');
        $this->CI->pyrocache->delete_all('pyrostreams');
    }

}