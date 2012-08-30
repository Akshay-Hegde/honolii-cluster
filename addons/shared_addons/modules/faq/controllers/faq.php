<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * FAQ public controller
 * 
 * @author      Stephen Cozart
 * @link		http://www.stephencozart.com
 * @package 	PyroCMS
 * @subpackage  FAQ Module
 * @category	controller
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
class Faq extends Public_Controller {
    
    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('faqs_categories_m');
        $this->load->model('faq_m');
        $this->lang->load('faq');
        
        $this->template
        	->set_breadcrumb(lang('breadcrumb_base_label'), '/')
            ->append_css('module::faq.css')
            ->append_js('module::faq_public.js');
    }
    
    public function _remap($method)
    {
        if ($method == 'index')
        {
            $this->index();
        }
        else
        {
            $this->view($method);
        }
    }
    
    /**
     * index method
     *
     * @access public
     * @return void
     */
    public function index()
    {
        //get all published faq categories
        $categories = $this->faqs_categories_m->published_categories();
        
        //get faq's not in any category
        $questions = $this->faq_m->published_faqs(0);
        
        $this->template
        	->set_breadcrumb( lang('faq_home_title') )
            ->set('questions', $questions)
            ->set('categories', $categories)
            ->build('index');
    }
    
    public function view($slug = FALSE)
    {
        if ( ! $slug)
        {
            redirect('faq');
        }
        else
        {
            $col = $this->faqs_categories_m->get_by('slug', $slug);
            $faqs = $this->faq_m->published_faqs($col->id);
            
            $this->template
            	->set_breadcrumb(lang('faq_home_title'), 'faq')
	            ->set_breadcrumb($col->title)
	            ->set('faqs', $faqs)
	            ->set('title', $col->title)
	            ->build('view', $this->data);
        }
    }
    
}
/* End of file controllers/faq.php */