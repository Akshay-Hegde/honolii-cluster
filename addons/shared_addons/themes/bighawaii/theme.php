<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Bighawaii extends Theme {

    public $name			= 'Big Hawaii Theme';
    public $author			= 'Wetumka Interactive';
    public $author_website	= 'http://wetumka.net/';
    public $website			= 'http://pyrocms.com/';
    public $description		= 'Multi Platform Design, Foundation Framework';
    public $version			= '1.0';
	public $options 		= array('show_breadcrumbs' => 	array('title' 		=> 'Show Breadcrumbs',
																'description'   => 'Would you like to display breadcrumbs?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => TRUE),
								   );
}

/* End of file theme.php */
