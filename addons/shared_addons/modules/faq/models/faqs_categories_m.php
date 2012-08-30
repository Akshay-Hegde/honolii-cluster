<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * FAQ categories model
 * 
 * @author      Stephen Cozart
 * @link		http://www.stephencozart.com
 * @package 	PyroCMS
 * @subpackage  FAQ Module
 * @category	model
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
class Faqs_categories_m extends MY_Model {
    
    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }
    
    function create_category($data)
    {
        return $this->insert($data, TRUE);
    }
    
    function category_options()
    {
        $categories = $this->get_all();
        
        $options = array();
        
        if(!empty($categories))
        {
            foreach($categories as $category)
            {
                $options[$category->id] = $category->title;
            }
        }
        
        return $options;
    }
    
    function published_categories()
    {
        return $this->get_many_by('published', 'yes');
    }
    
}
/* End of file faqs_categories_m.php */