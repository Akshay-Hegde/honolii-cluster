<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Feature Plugin
 *
 * Pulls stream content by ID, or an array of ID's
 *
 * @author      Edward Meehan
 * @package     Project Bumpo
 */
class Plugin_Feature extends Plugin
{
    public $version = '1.0.0';

    public $name = array(
        'en'    => 'Feature'
    );

    public $description = array(
        'en'    => 'Pulls stream content by ID, or an array of ID'
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
            'Feature Plugin' => array(
                'description' => array(// a single sentence to explain the purpose of this method
                    'en' => 'Pulls stream content by ID, or an array of ID'
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
     * Feature
     *
     * Usage:
     * {{ feature:single id='1' side='left' }}
     *
     * @return string
     */
    function single()
    {
        $id = $this->attribute('id', NULL); // id of feature - null by default
        $side = $this->attribute('side', 'left'); // side to display main content
        
        if($id == NULL)
        {
           return; 
        }
        
        if($side != 'left')
        {
            $sideA = 'col-md-push-5';
            $sideB = 'col-md-pull-7';
        }
        
        $html = '';
        $html .= '{{ streams:single stream="features" id="'.$id.'" }}';
        $html .= '<section class="row featurette">';
        $html .=    '<div class="col-md-7 ' . @$sideA . '">';
        $html .=        '<h2 class="featurette-heading">{{ headline }}{{ if headline_muted }}<span class="text-muted"> {{ headline_muted }}</span>{{ endif }}</h2>';
        $html .=        '<div class="lead">{{ content }}</div>';
        $html .=    '</div>';
        $html .=    '<div class="col-md-5 ' . @$sideB . '">{{ image:img }}</div>';
        $html .= '</section>';
        $html .= '{{ /streams:single }}';
        
        return $html;
    }
}

/* End of file example.php */