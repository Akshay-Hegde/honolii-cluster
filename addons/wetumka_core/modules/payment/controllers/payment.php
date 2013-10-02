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

    public function __construct() {
        parent::__construct();

        // load the braintree library
        require_once FCPATH . $this -> module_details['path'] . '/libraries/Braintree.php';

        // Load the required classes
        // $this->load->model('gallery_m');
        // $this->load->model('gallery_image_m');
        // $this->lang->load('galleries');
        // $this->lang->load('gallery_images');
        // $this->load->helper('html');

        //Load pagination library
        //$this->load->library('pagination');

        Braintree_Configuration::environment('sandbox');
        Braintree_Configuration::merchantId('xkf2xy7z84c85pdw');
        Braintree_Configuration::publicKey('vfqd94q7tzqg4mgh');
        Braintree_Configuration::privateKey('c62902a1ae0dd2385f3b36fbb2e1c14f');

    }

    function index() {
        $message = "Hello World!";

        if ($_POST) {

            $result = Braintree_Transaction::sale(array("amount" => $_POST["value"], "creditCard" => array("number" => $_POST["number"], "cvv" => $_POST["cvv"], "expirationMonth" => $_POST["month"], "expirationYear" => $_POST["year"]), "options" => array("submitForSettlement" => true)));

            if ($result -> success) {
                echo("Success! Transaction ID: " . $result -> transaction -> id);
            } else if ($result -> transaction) {
                echo("Error: " . $result -> message);
                echo("<br/>");
                echo("Code: " . $result -> transaction -> processorResponseCode);
            } else {
                echo("Validation errors:<br/>");
                foreach (($result->errors->deepAll()) as $error) {
                    echo("- " . $error -> message . "<br/>");
                }
            }

        }

        // Loads from addons/<site-ref>/modules/blog/views/view_name.php
        $this -> template -> set('message', $message) -> build('index');
    }

    function customer() {
        if ($_POST) {
            $result = Braintree_Customer::create(array("firstName" => $_POST["first_name"], "lastName" => $_POST["last_name"], "creditCard" => array("number" => $_POST["number"], "expirationMonth" => $_POST["month"], "expirationYear" => $_POST["year"], "cvv" => $_POST["cvv"], "billingAddress" => array("postalCode" => $_POST["postal_code"]))));

            if ($result -> success) {
                echo("Success! Customer ID: " . $result -> customer -> id);
            } else {
                echo("Validation errors:<br/>");
                foreach (($result->errors->deepAll()) as $error) {
                    echo("- " . $error -> message . "<br/>");
                }
            }
        }
        
        $this -> template -> build('customer');
    }

}
