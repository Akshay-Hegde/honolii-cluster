<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Checkout extends Module {

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Checkout'
            ),
            'description' => array(
                'en' => 'Checkout with Dwolla'
            ),
            'frontend' => true,
            'backend' => true,
            //'menu' => 'data', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
            
        );
    }

    public function install()
    {

        // We made it!
        return true;
    }

    public function uninstall()
    {

        // Put a check in to see if something failed, otherwise it worked
        return true;
    }


    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        $helpString = '';
        
        return $helpString;

    }
}