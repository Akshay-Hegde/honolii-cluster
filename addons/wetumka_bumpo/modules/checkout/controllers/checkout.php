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
    
    /**
     * Validation rules for registration
     *
     * @var array
     * @access private
     */
    private $registration_rules = array(
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'trim'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'trim'
        ),
        array(
            'field' => 'address_line1',
            'label' => 'Address',
            'rules' => 'trim'
        ),
        array(
            'field' => 'address_line2',
            'label' => 'Apt/Suite',
            'rules' => 'trim'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'trim'
        ),
        array(
            'field' => 'postcode',
            'label' => 'Zip',
            'rules' => 'trim'
        ),
        array(
            'field' => 'email',
            'label' => 'Email Address',
            'rules' => 'trim'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim'
        )
    );

    public function __construct() {
            
        parent::__construct();
        
        // if(Settings::get('disable_payments')){
            // redirect(Settings::get('disable_url'));
        // }
        
        // Load the required classes
        $this->load->helper('form');
        
    }

    public function index() {
            
        $data = array();
                
        // Set the validation rules
        $this->form_validation->set_rules($this->registration_rules);

        if ($this->form_validation->run() )
        {
            
            $data = $this->input->post();
            
            $profile_data = array(
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'display_name' => $data['first_name'] . ' ' . $data['last_name']
            );
            
            $response = $this->ion_auth->register($data['first_name'] . '.' . $data['last_name'], $data['password'], $data['email'], $group_id = null, $profile_data, $group_name = false);
                
            redirect('/how-it-works');
            
        }
        else
        {
            
            $this->template
                ->set('data',  $data)
                ->build('index');
                
        }
    }
    
    public function creditcard() {
        
        $data['clientKey']  = Settings::get('braintree_clientKey');

        // Set the validation rules
        $this->form_validation->set_rules($this->creditcard_rules);

        if ($this->form_validation->run() )
        {
            $result = $this->braintreeAPI();
            
            if ($result->success)
            {
                $this->session->set_userdata('transID', 'Braintree: ' . $result->transaction->id);
                
                $this->payment_m->create();
                
                redirect('/payment/success');
            }
            else
            {

                if ($result->transaction)
                {
                    $data['error'] = $result->message . ' (code:' . $result->transaction->processorResponseCode . ')';
                }
                else
                {
                    $data['error'] = $result->message;
                }
                
                $this->template
                    ->set('data',  $data)
                    ->build('creditcard');
            }
            
        }
        else
        {
                
            if ( !$this->session->userdata('payment') )
            {
                
                $this->session->set_flashdata('error', '<button type="button" class="close" data-dismiss="alert">&times;</button><strong>Oops!</strong> Sorry, please fill out this form again.');
                redirect('/payment');
                
            }    

            $this->template
                ->set('data',  $data)
                ->build('creditcard');
                
        }
    }

    public function success() {
            
        $data['transID'] = $this->session->userdata('transID');
        $data['payment'] = $this->session->userdata('payment');
        $data['invoice'] = $this->session->userdata('invoice');
        $data['finishURL'] = $_SERVER['HTTP_HOST'];
        $data['title'] = 'Payment Complete';
        $data['success'] = TRUE;
        
        $this->session->unset_userdata('transID');
        $this->session->unset_userdata('payment');
        $this->session->unset_userdata('invoice');
        
        $this->template
            ->set('data', $data)
            ->build('callback');
                
    }

    public function cancel()
    {
        $this->session->unset_userdata('payment');
        $this->session->unset_userdata('invoice');
        
        $this->session->set_flashdata('notice', '<button type="button" class="close" data-dismiss="alert">&times;</button>Your payment session was canceled');
        
        redirect('/');
    }

    public function dwollaRedirect()
    {
        if(isset($_GET['status']))
        {
            if ($_GET['status'] === "Completed")
            {
                $this->session->set_userdata('transID', 'Dwolla: ' . $_GET['transaction']);
                
                $this->payment_m->create();
                
                redirect('/payment/success');
            }
        }
        elseif (isset($_GET['error']))
        {
                
            $data['payment'] = $this->session->userdata('payment');
            $data['invoice'] = $this->session->userdata('invoice');
            $data['finishURL'] = $_SERVER['HTTP_HOST'];
            $data['title'] = 'Payment Incomplete';
            $data['success'] = FALSE;
            $data['transERROR'] = $_GET['error_description'];
            
            $this->session->unset_userdata('payment');
            $this->session->unset_userdata('invoice');  
           
            $this->template
                ->set('data', $data)
                ->build('callback');
           
        }
    }
    
    private function braintreeAPI() {
        
        // load the braintree library
        require_once FCPATH . $this -> module_details['path'] . '/libraries/Braintree.php';
          
        Braintree_Configuration::environment(Settings::get('braintree_environment'));
        Braintree_Configuration::merchantId(Settings::get('braintree_merchantId'));
        Braintree_Configuration::publicKey(Settings::get('braintree_publicKey'));
        Braintree_Configuration::privateKey(Settings::get('braintree_privateKey'));
        
        return Braintree_Transaction::sale(
            array(
                "amount" =>                 $this->session->userdata('payment'),
                'customFields' => array(
                    "invoice_num" =>       $this->session->userdata('invoice')
                ),
                "creditCard" => array(
                    "number" =>             $this->input->post('cc_num'),
                    "cvv" =>                $this->input->post('cc_cvv'),
                    "expirationMonth" =>    $this->input->post('cc_month'),
                    "expirationYear" =>     $this->input->post('cc_year'),
                    "cardholderName" =>     $this->input->post('cc_name')
                ),
                'billing' => array(
                    "postalCode" =>         $this->input->post('zip')
                ),
                "options" => array(
                    "submitForSettlement" => true
                ),
            )
        );        
    }

    private function dwollaAPI() {
        $postData = array(
            "Key" =>                        Settings::get('dwolla_key'),
            "Secret" =>                     Settings::get('dwolla_secret'),
            "AllowFundingSources" =>        TRUE,
            "Test" =>                       Settings::get('dwolla_test'),
            "PurchaseOrder" => array(
                "DestinationId" =>          Settings::get('dwolla_id'),
                "Shipping" =>               0.00,
                "Tax" =>                    0.00,
                "Total" =>                  $this->input->post('payment'),
                
                "OrderItems" => array(
                    array(
                        "Description" =>        $this->input->post('invoice'),
                        "Name" =>               "Invoice Payment",
                        "Price"=>               $this->input->post('payment'),
                        "Quantity"=>            1
                    )
                )
            )
        );
         
        $ch = curl_init( 'https://www.dwolla.com/payment/request' );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec( $ch );
        
        curl_close($ch);
        
        return json_decode($response);                
    }
}