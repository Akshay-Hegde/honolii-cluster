<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Example Plugin
 *
 * Quick plugin to demonstrate how things work
 *
 * @author      PyroCMS Dev Team
 * @package     PyroCMS\Addon\Plugins
 * @copyright   Copyright (c) 2009 - 2010, PyroCMS
 */
class Plugin_Instagram extends Plugin
{
    public $version = '1.0.0';

    public $name = array(
        'en'    => 'Example'
    );

    public $description = array(
        'en'    => 'Example of PyroCMS plugin structure.'
    );

    /**
     * Returns a PluginDoc array that PyroCMS uses 
     * to build the reference in the admin panel
     *
     * All options are listed here but refer 
     * to the Blog plugin for a larger example
     *
     * @return array
     */
    public function _self_doc()
    {
        $info = array(
            'hello' => array(
                'description' => array(// a single sentence to explain the purpose of this method
                    'en' => 'A simple "Hello World!" example.'
                ),
                'single' => true,// will it work as a single tag?
                'double' => false,// how about as a double tag?
                'variables' => '',// list all variables available inside the double tag. Separate them|like|this
                'attributes' => array(
                    'name' => array(// this is the name="World" attribute
                        'type' => 'text',// Can be: slug, number, flag, text, array, any.
                        'flags' => '',// flags are predefined values like asc|desc|random.
                        'default' => 'World',// this attribute defaults to this if no value is given
                        'required' => false,// is this attribute required?
                    ),
                ),
            ),
        );
    
        return $info;
    }

    /**
     * Hello
     *
     * Usage:
     * {{ instagram:hello }}
     *
     * @return string
     */
    function hello()
    {
        $userID = $this->attribute('user', 'self');
        $token = $this->attribute('token', '199142042.7ba59b7.1580e0d0c3204e8e8c70076a4e2b0bc5');
        
        $testing = file_get_contents('https://api.instagram.com/v1/users/'. $userID .'/media/recent/?access_token='.$token);
        if($testing)
        {
            return '<script type="text/javascript"> testing = ' . $testing . ';</script>';
        }
    }
}

/* End of file example.php */