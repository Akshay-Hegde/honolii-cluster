<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Admin extends Admin_Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {

        // Load the view
        $this->template
            ->title($this->module_details['name'])
            ->build('admin/index');
    }
}
    