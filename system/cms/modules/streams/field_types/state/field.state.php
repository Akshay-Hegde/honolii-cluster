<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams US State Field Type
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_state
{
	public $field_type_name 		= 'US State';
	
	public $field_type_slug			= 'state';
	
	public $db_col_type				= 'varchar';

	public $version					= '1.1';

	public $author					= array('name'=>'Parse19', 'url'=>'http://parse19.com');

	// --------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->states = $this->states();
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($data)
	{
		return form_dropdown($data['form_slug'], $this->states, $data['value'], 'id="'.$data['form_slug'].'"');
	}

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function pre_output( $input )
	{
		if( isset($this->states[$input]) ):
		
			return $this->states[$input];
		
		else:
		
			return;
		
		endif;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * State
	 *
	 * Returns an array of states
	 *
	 * @access	private
	 * @return	array
	 */
	private function states()
	{
		return array('AL'=>"AL",  
			'AK'=>"AK",  
			'AZ'=>"AZ",  
			'AR'=>"AR",  
			'CA'=>"CA",  
			'CO'=>"CO",  
			'CT'=>"CT",  
			'DE'=>"DE",  
			'DC'=>"DC",  
			'FL'=>"FL",  
			'GA'=>"GA",  
			'HI'=>"HI",  
			'ID'=>"ID",  
			'IL'=>"IL",  
			'IN'=>"IN",  
			'IA'=>"IA",  
			'KS'=>"KS",  
			'KY'=>"KY",  
			'LA'=>"LA",  
			'ME'=>"ME",  
			'MD'=>"MD",  
			'MA'=>"MA",  
			'MI'=>"MI",  
			'MN'=>"MN",  
			'MS'=>"MS",  
			'MO'=>"MO",  
			'MT'=>"MT",
			'NE'=>"NE",
			'NV'=>"NV",
			'NH'=>"NH",
			'NJ'=>"NJ",
			'NM'=>"NM",
			'NY'=>"NY",
			'NC'=>"NC",
			'ND'=>"ND",
			'OH'=>"OH",  
			'OK'=>"OK",  
			'OR'=>"OR",  
			'PA'=>"PA",  
			'RI'=>"RI",  
			'SC'=>"SC",  
			'SD'=>"SD",
			'TN'=>"TN",  
			'TX'=>"TX",  
			'UT'=>"UT",  
			'VT'=>"VT",  
			'VA'=>"VA",  
			'WA'=>"WA",  
			'WV'=>"WV",  
			'WI'=>"WI",  
			'WY'=>"WY");
	}
	
}

/* End of file field.state.php */
/* Location: /streams/field_types/field.state.php */