<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * FAQ admin controller
 * 
 * @author      Stephen Cozart
 * @link		http://www.stephencozart.com
 * @package 	PyroCMS
 * @subpackage  FAQ Module
 * @category	controller
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
class Admin extends Admin_Controller
{
	/**
	 * Validation rules for creating a new faq
	 *
	 * @var array
	 * @access private
	 */
	private $validation_rules = array();

	public $section = 'faqs';
	
	public function __construct()
	{
		// First call the parent's constructor
		parent::__construct();

		// Load all the required classes
		$this->load->model(array('faq_m', 'faqs_categories_m'));
		$this->lang->load('faq');
		
		$this->load->library('form_validation');

		// Set the validation rules
		$this->validation_rules = array(
			array(
				'field' => 'question',
				'label' => lang('faq_question_label'),
				'rules' => 'trim|max_length[255]|required'
			),
			array(
				'field' => 'answer',
				'label' => lang('faq_answer_label'),
				'rules' => 'trim|required'
			),
			array(
				'field' => 'published',
				'label'	=> lang('faq_published_label'),
				'rules'	=> 'trim|required'
			)

		);
		$this->template->publish_options = array(
			'yes' => lang('faq_published_yes'),
			'no' => lang('faq_published_no')
		);
		
		$no_category[0] = lang('faq_category_option');
			
		$categories = $this->faqs_categories_m->category_options();
		
		$this->template->category_options = $no_category + $categories;
	
		$this->template
			->append_metadata( $this->load->view('fragments/wysiwyg', array(), TRUE) )
			->append_js('module::faq.js');
		
		//if the request is ajax set layout to false		
		$this->_is_ajax() and $this->template->set_layout(FALSE);
	}

	/**
	 * List all faq's
	 *
	 * @author Stephen Cozart
	 * @access public
	 * @return void
	 */
	public function index()
	{
		//Get the records and assign to template
		$this->template->faq = $this->faq_m->get_all_faqs();
		
		//build output
		$this->template->build('admin/index');
	}

	/**
	 * Create a new faq
	 *
	 * @author Stephen Cozart
	 * @access public
	 * @return void
	 */
	public function create()
	{
		// Set the validation rules
		$this->form_validation->set_rules($this->validation_rules);
		
		//validate the form
		if($this->form_validation->run())
		{
			//prep data array
			$data = array(
						'question' => $this->input->post('question'),
						'answer' => $this->input->post('answer'),
						'published' => $this->input->post('published'),
						'category_id' => $this->input->post('category')
				    );
			
			//insert data
			if($this->faq_m->create_faq($data))
			{
				//success message
				$message = lang('faq_create_success');
				$status = 'success';
			}
			else
			{
				//failure message
				$message= lang('faq_create_error');
				$status = 'error';
			}
			
			//form validated so either the record saved or there was a db error
			if($this->_is_ajax())
			{
				//lets only return a json encoded array
				$json = array('message' => $message,
					      'status' => $status
					      );
				echo json_encode($json);
				return TRUE;
			}
			
			//not ajax lets redirect
			else
			{
				$this->session->set_flashdata($status, $message);
				redirect('admin/faq');
			}
		}
		
		//form didn't validate and post is set so we should return our validation errors in json
		if($this->_is_ajax() && $_POST)
		{
			echo json_encode(
							array(
								'status' => 'error',
								'message' => validation_errors()
							)
						);
		}
		
		//just show the form view
		else
		{
			// Load the view
			$this->template->build('admin/create');	
		}
	}

	/**
	 * Delete an single or many faq's
	 *
	 * @author Stephen Cozart
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$ids = $this->input->post('action_to');
		
		if(!empty($ids))
		{
			//counter
			$i = 0;
			
			$count = count($ids);
			
			//loop through each id and try to delete
			foreach($ids as $id)
			{
				//delete success
				if($this->faq_m->delete($id))
				{
					$i++;
				}
			}
			$this->session->set_flashdata('success', sprintf(lang('faq_delete_success'), $i, $count));
		}
		else
		{
			//oops no ids.. ids required here.
			$this->session->set_flashdata('notice', lang('faq_action_empty'));
		}
		//no need to keep hanging around here,  redirect back to faq list
		redirect('admin/faq');
	}

	/**
	 * Edit an existing faq
	 *
	 * @author Stephen Cozart
	 * @param id the ID to edit
	 * @access public
	 * @return void
	 */
	public function edit($id = FALSE)
	{
		$id_rule = array(
						'field' => 'faq_id',
						'label' => lang('faq_id_label'),
						'rules' => 'required|is_numeric|trim'
					);
		
		//push the special id rule into the validation rules
		array_push($this->validation_rules, $id_rule);
		
		$this->form_validation->set_rules($this->validation_rules);
		
		//form valid lets do something with the data
		if($this->form_validation->run())
		{
			//prep the data
			$data = array('question' => $this->input->post('question'),
				      'answer' => $this->input->post('answer'),
				      'published' => $this->input->post('published'),
				      'category_id' => $this->input->post('category')
				      );
			//update data
			if($this->faq_m->update($this->input->post('faq_id'), $data, TRUE))
			{
				$message = lang('faq_update_success');
				$status = 'success';
			}
			else
			{
				$message = lang('faq_update_error');
				$status = 'error';
			}
			
			if($this->_is_ajax())
			{
				$json = array('message' => $message,
					      'status' => $status
					      );
				echo json_encode($json);
				return TRUE;
			}
			else
			{
				$this->session->set_flashdata($status, $message);
				redirect('admin/faq');
			}
		}
		
		//id is set lets gooo.
		if($id)
		{
			//get the faq we want to edit and assign to template variable
			$this->template->faq = $this->faq_m->get($id);
		}
		else
		{
			//oops no id can't do nothing without that!
			redirect('admin/faq');
		}
		
		//form didn't validate and post is set so we should return our validation errors in json
		if($this->_is_ajax() && $_POST)
		{
			echo json_encode(
							array(
								'status' => 'error',
								'message' => validation_errors()
							)
						);
		}
		
		//just build the output
		else
		{
			$this->template->build('admin/edit');
		}
	}
	
	/**
	 * Helper method to allow one form to controll multiple actions
	 *
	 * @access public
	 * @return void
	 */
	public function action()
	{		
		if($this->input->post('btnAction') == 'delete')
		{
			$this->delete();
		}
	}
	
	/**
	 * Ajax helper to update the sort/display order
	 *
	 * @access	public
	 * @param	none
	 * @return	void
	 */
	public function update_order()
	{
		$data = $this->input->post('order');
		if(is_array($data))
		{
			$order = 1;
			foreach($data as $id)
			{
				$this->faq_m->update_order($id, $order);
				$order++;
			}
		}
	}
	
	protected function _is_ajax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? TRUE : FALSE;
	}
}
