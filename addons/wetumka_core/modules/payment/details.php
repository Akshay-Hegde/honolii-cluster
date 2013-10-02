<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Payment extends Module {

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Payment'
            ),
            'description' => array(
                'en' => 'Uses the BrainTree API for payments online'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
            
            // 'sections' => array(
                // 'items' => array( //"items" will be the same in the Admin controller as protected $section filed
                    // 'name'  => 'sample:items', // These are translated from your language file
                    // 'uri'   => 'admin/sample',
                        // 'shortcuts' => array(
                            // 'create' => array(
                                // 'name'  => 'sample:create',
                                // 'uri'   => 'admin/sample/create',
                                // 'class' => 'add'
                                // )
                            // )
                        // )
                // )
        );
    }

    public function install()
    {
        $this->dbforge->drop_table('payment');
        $this->db->delete('settings', array('module' => 'payment'));

        $payment = array(
            'id' => array(
            'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => true
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
        );

        $payment_setting = array(
            'slug' => 'sample_setting',
            'title' => 'Sample Setting',
            'description' => 'A Yes or No option for the Sample module',
            'default' => '1',
            'value' => '1',
            'type' => 'select',
            'options' => '1=Yes|0=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'sample'
        );

        $this->dbforge->add_field($payment);
        $this->dbforge->add_key('id', true);

        // Let's try running our DB Forge Table and inserting some settings
        if ( ! $this->dbforge->create_table('payment') OR ! $this->db->insert('settings', $payment_setting))
        {
            return false;
        }

        // No upload path for our module? If we can't make it then fail
        if ( ! is_dir($this->upload_path.'payment') AND ! @mkdir($this->upload_path.'payment',0777,true))
        {
            return false;
        }

        // We made it!
        return true;
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('payment');

        $this->db->delete('settings', array('module' => 'payment'));

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
        return "Here you can enter HTML with paragrpah tags or whatever you like";

        // or

        // You could include a file and return it here.
        return $this->load->view('help', null, true); // loads modules/sample/views/help.php
    }
}