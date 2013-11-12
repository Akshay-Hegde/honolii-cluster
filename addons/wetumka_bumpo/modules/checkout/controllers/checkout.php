<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 *
 * 
 *
 * @author 		Edward Meehan - Wetumka.net
 * @package 	PyroCMS
 * @subpackage 	Checkout Module
 * @category 	Modules
 * @license 	Apache License v2.0
 */
class Checkout extends Public_Controller {
    
    /*
     * 
     * Validation rules
     * 
     */
    private $registration_rules = array(
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'address_line1',
            'label' => 'Address',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'address_line2',
            'label' => 'Apt/Suite',
            'rules' => 'trim'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'trim|required|exact_length[2]|alpha'
        ),
        array(
            'field' => 'postcode',
            'label' => 'Zip',
            'rules' => 'trim|required|exact_length[5]|numeric'
        ),
        array(
            'field' => 'email',
            'label' => 'Email Address',
            'rules' => 'rim|required|valid_email|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[6]|max_length[20]|alpha_numeric'
        ),
        array(
            'field' => 'password_confirm',
            'label' => 'Confirm Password',
            'rules' => 'trim|matches[password]'
        )
    );

    public function __construct() {
            
        parent::__construct();
                
        // Load the required classes
        $this->load->library('cart');
        $this->load->helper('form');
        
    }

     /*
     * 
     * Default - Checkout
     * Display payment summary and registration form
     * 
     */
    public function index() {
            
        // check for product params
        // TODO find better solution
        if (count($_GET) > 0)
        {
            // build cart product
            $productArray = $this->getProduct();
            // destroy old cart
            $this->cart->destroy();
            // build new cart
            $this->cart->insert($productArray);
        }
        
        // check if cart or params exist - redirect user if not
        // TODO find better solution
        if($this->cart->total() == 0 && count($_GET) == 0)
        {
            $this->session->set_flashdata('notice', 'No product selections made.');
            redirect('/membership');
        }
          
        // Set the validation rules
        $this->form_validation->set_rules($this->registration_rules);

        if ($this->form_validation->run() )
        {
            
            $data = $this->input->post();
            
            // custom profile fields - needed to work with user profile
            $profile_data = array(
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'display_name' => $data['first_name'] . ' ' . $data['last_name'],
                'address_line1' => $data['address_line1'],
                'address_line2' => $data['address_line2'],
                'city' => $data['city'],
                'state' => $data['state'],
                'postcode' => $data['postcode'],
                'product_id' => $data['product_id'],
                'referral_id' => $data['referral_id']
            );
            
            // check if email has been registered before
            if(!$this->ion_auth->email_check($data['email']))
            {
                $response = $this->ion_auth->register(
                    NULL, // auto username
                    $data['password'],
                    $data['email'],
                    $group_id = 3,
                    $profile_data, // array of profile data
                    $group_name = 'members'
                );
                
                if($response){
                    // all set, ready to move on
                    redirect('/how-it-works');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Sorry, we are having problems and can not complete your order at this time.');
                    redirect('/home');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'That email is already being used, please try a different one.');
                redirect('/checkout');
            }
        }
            
        $this->template
            ->set('productID', @$productID)
            ->set('cart', $this->cart->contents())
            ->build('index');
                
    }

    /*
     * 
     * Payment Screen
     * Setup credit card transaction for recurring billing
     * 
     */
    public function payment(){
        
        // check if cart or params exist - redirect user if not
        // TODO find better solution
        if(!$this->cart->total())
        {
            $this->session->set_flashdata('notice', 'No product selections made.');
            redirect('/membership');
        }
        
        $this->template
            ->set('cart', $this->cart->contents())
            ->build('payment');
    }
    
    /*
     * 
     * Build product array for cart
     * 
     */
    private function getProduct(){
        
        // TODO build a admin product catalog - very custom right now

        $product = array(
            'guru'      => 1,
            'swami'     => 2
        );
        
        $frequency = array(
            'bi-monthly'    => 1,
            'monthly'       => 2
        );
        
        $temp = array(
            'tropical'  => 1,
            'warm'      => 2,
            'cool'      => 3,
            'cold'      => 4
        );
        
        $productID = 'A0000';
        $productID .= $product[$_GET['product']];
        $productID .= $frequency[$_GET['frequency']];
        $productID .= $temp[$_GET['temp']];
        
        switch ($_GET['product']) {
            case 'guru':
                $price = 5.00;
                break;
            
            case 'swami':
                $price = 7.00;
                break;
        }
        
        return array(
            'id'      => $productID,
            'qty'     => 1,
            'price'   => $price,
            'name'    => 'BumpoBox',
            'options' => array() //options array
        );
    }
}