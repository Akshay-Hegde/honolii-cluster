<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 *
 * The galleries module enables users to create albums, upload photos and manage their existing albums.
 *
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Gallery Module
 * @category 	Modules
 * @license 	Apache License v2.0
 */
class Payment extends Public_Controller {

    /**
     * Validation rules for payment method
     *
     * @var array
     * @access private
     */
    private $method_rules = array(
        array(
            'field' => 'payment',
            'label' => 'Payment Amount',
            'rules' => 'trim|required|greater_than[0]'
        ),
        array(
            'field' => 'invoice',
            'label' => 'Invoice Number',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'method',
            'rules' => 'trim'
        )
    );
    
    /**
     * Validation rules for credit card
     *
     * @var array
     * @access private
     */
    private $creditcard_rules = array(
        array(
            'field' => 'cc_num',
            'label' => 'Card Number',
            'rules' => 'trim'
        ),
        array(
            'field' => 'cc_cvv',
            'label' => 'CVV',
            'rules' => 'trim'
        ),
        array(
            'field' => 'cc_month',
            'label' => 'Expiration Month',
            'rules' => 'trim'
        ),
        array(
            'field' => 'cc_year',
            'label' => 'Expiration Year',
            'rules' => 'trim'
        ),
        array(
            'field' => 'zip',
            'label' => 'Billing Zip Code',
            'rules' => 'trim'
        ),
        array(
            'field' => 'cc_name',
            'label' => 'Card Holder Name',
            'rules' => 'trim'
        )
    );

    public function __construct() {
            
        parent::__construct();

        // Load the required classes
        $this->load->helper('form');

    }

    public function index() {
        
        // Set the validation rules
        $this->form_validation->set_rules($this->method_rules);

        if ($this->form_validation->run() )
        {
            
            // save form data in flash session
            $this->session->set_userdata('payment', $this->input->post('payment'));
            $this->session->set_userdata('invoice', $this->input->post('invoice'));
            
            // Dwolla Payment Gateway
            if ($this->input->post('method') === 'dwollapayment')
            {
                $result = $this->dwollaAPI();
                
                if($result->Result === 'Success'){
                    redirect('https://www.dwolla.com/payment/checkout/' . $result->CheckoutId);
                }
                else
                {
                    
                }
            }
            
            // Braintree Payment Form
            if ($this->input->post('method') === 'cardpayment')
            {                 
                redirect('/payment/cc');
            }
            
        }
        else
        {
            
            $this->template->build('index');
                
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
                $this->session->set_userdata('transID', $result->transaction->id);
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
        
        $this->template
            //->set('data',  $data)
            ->build('success');
                
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
            "Redirect" =>                   "http://wetumka.local/payment/success",
            "Callback" =>                   "http://wetumka.local/payment/success",
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
