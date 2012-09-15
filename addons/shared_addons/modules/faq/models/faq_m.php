<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * FAQ model
 * 
 * @author      Stephen Cozart
 * @link		http://www.stephencozart.com
 * @package 	PyroCMS
 * @subpackage  FAQ Module
 * @category	model
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
class Faq_m extends MY_Model {
    
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
    
    
    public function create_faq($data)
    {
        return $this->insert($data, TRUE);
    }
    
    public function get_all_faqs()
    {
        return $this->order_by("{$this->_table}.order", 'ASC')
                    ->order_by('category_id', 'ASC')
                    ->get_all();
    }
    
    public function clear_category($id)
    {
        return $this->db->where('category_id', $id)
                    ->set('category_id', 0)
                    ->update($this->_table);
    }
    
    public function published_faqs($id)
    {
        $this->db->where('published', 'yes');
        return $this->order_by("{$this->_table}.order", 'ASC')
                        ->get_many_by('category_id', $id);
    }
    
    public function update_order($id, $order)
    {
       return $this->update($id, array("{$this->_table}.order" => $order), TRUE);
    }
}
/* End of file faq_m.php */
/* Location: ./addons/modules/faq/models/faq_m.php */