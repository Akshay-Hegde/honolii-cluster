<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Choice Field Type
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 * @license		http://parse19.com/pyrostreams/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_choice
{
	public $field_type_name 		= 'Choice';
	
	public $field_type_slug			= 'choice';
	
	public $db_col_type				= 'varchar';

	public $version					= '1.1';

	public $author					= array('name'=>'Parse19', 'url'=>'http://parse19.com');

	public $custom_parameters		= array('choice_data', 'choice_type', 'default_value');
	
	public $plugin_return			= 'merge';
	
	public $lang					= array(
	
		'en'	=> array(
				'choice_data'	=> 'Choice Data',
				'choice_type'	=> 'Choice Type'
		)
	
	);
	
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output( $params )
	{
		$return = null;
		
		$choices = $this->_choices_to_array( $params['custom']['choice_data'], $params['custom']['choice_type'] );
		
		// Put it into a drop down
		if($params['custom']['choice_type'] == 'dropdown'):
		
			$return = form_dropdown($params['form_slug'], $choices, $params['value'], 'id="'.$params['form_slug'].'"');
			
		else:

			// If these are checkboxes, then break up the vals
			if($params['custom']['choice_type'] == 'checkboxes'):
			
				// We may have an array from $_POST or a string
				// from the data
				if(is_string($params['value'])):
				
					$vals = explode("\n", $params['value']);
				
				else:
				
					$vals = $params['value'];
				
				endif;
				
				foreach($vals as $k => $v): $vals[$k] = trim($v); endforeach;
			
			endif;
		
			$return .= '<ul class="form_list">';
		
			foreach( $choices as $choice_key => $choice ):
						
				if($params['custom']['choice_type'] == 'radio'):

					($params['value'] == $choice_key) ? $selected = TRUE : $selected = FALSE;
			
					$return .= '<li>'.form_radio($params['form_slug'], $choice_key, $selected).' '.$choice.'</li>';
				
				else:
				
					(in_array($choice_key, $vals)) ? $selected = TRUE : $selected = FALSE;
				
					$return .= '<li>'.form_checkbox($params['form_slug'].'[]', $choice_key, $selected, 'id="'.$choice_key.'"').' <label class="ghost_label" for="'.$choice_key.'">'.$choice.'</label></li>';
				
				endif;
			
			endforeach;

			$return .= '</ul>';
		
		endif;
		
		return $return;
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before outputting
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input, $data)
	{
		$choices = $this->_choices_to_array( $data['choice_data'], $data['choice_type'] );
		
		// Checkboxes?
		if($data['choice_type'] == 'checkboxes'):
		
			$vals = explode("\n", $input);
			
			$html = '<ul>';

			foreach($vals as $v):
			
				if(isset($choices[$v])) $html .= '<li>'.$choices[$v].'</li>';				
				
			endforeach;
					
			return $html .= '</ul>';
		
		endif;
		
		if( isset($choices[$input]) and $input != '' ):
		
			return $choices[$input];
			
		elseif( isset($choices[$input]) and $input == '' ):
		
			return;
		
		else:
		
			return '';
		
		endif;
	}

	// --------------------------------------------------------------------------

	/**
	 * Pre-save
	 */	
	public function pre_save($input, $field)
	{
		// We only need to do this for checkboxes
		if($field->field_data['choice_type'] == 'checkboxes' and is_array($input)):

			// One per line
			return implode("\n", $input);		

		else:
		
			// Booooo
			return $input;
		
		endif;
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Pre field add
	 *
	 * Before we add the field to a stream 
	 *
	 * @access	public
	 * @param	obj
	 * @param	obj
	 * @return	void
	 */
	public function field_assignment_construct($field, $stream)
	{
		// We need more room for checkboxes
		if($field->field_data['choice_type'] == 'checkboxes'):
		
			$this->db_col_type = 'text';
		
		endif;
	}

	// --------------------------------------------------------------------------

	/**
	 * Breaks up the items into key/val for template use
	 *
	 * @access	public
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	public function pre_output_plugin($prefix, $input, $params)
	{
		$options = $this->_choices_to_array($params['choice_data'], $params['choice_type']);

		// Checkboxes
		if($params['choice_type'] == 'checkboxes'):
		
			$this->plugin_return = 'array';
			
			$values = explode("\n", $input);
			
			$return = array();
			
			foreach($values as $k => $v):
			
				if(isset($options[$v])):
				
					$return[$k]['value'] 		= $options[$v];
					$return[$k]['value.key'] 	= $v;
				
				else:
				
					// We don't want undefined values
					unset($values[$k]);
				
				endif;
			
			endforeach;
			
			return $return;
		
		endif;

		$this->plugin_return = 'merge';
	
		if( isset($options[$input]) and $input != '' ):
		
			$choices[rtrim($prefix, '.')] = $options[$input];
			$choices[$prefix.'key']	= $input;
			$choices[$prefix.'val']	= $options[$input];
		
			return $choices;
		
		else:
		
			return '';
		
		endif;
	}

	// --------------------------------------------------------------------------

	/**
	 * Data for choice. In x : X format or just X format
	 */	
	public function param_choice_data( $params = FALSE )
	{
		$instructions = '<p class="note">Put each choice on one line. If you want a separate value for each choice, you can separate them by a colon (:). Ex: pyro : PyroCMS</p>';
	
		return '<div style="float: left;">'.form_textarea('choice_data', $params).$instructions.'</div>';
	}

	// --------------------------------------------------------------------------

	/**
	 * Display as Dropdown
	 */	
	public function param_choice_type( $params = FALSE )
	{
		$choices = array(
			'dropdown' => 'Dropdown',
			'radio' => 'Radio Buttons',
			'checkboxes' => 'Checkboxes'
		);
		
		return form_dropdown('choice_type', $choices, $params);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Take a string of choices and make them into an array
	 *
	 * @access	public
	 * @param	string
	 * @return	array
	 */
	public function _choices_to_array($choices_raw, $type = 'dropdown')
	{
		$lines = explode("\n", $choices_raw);
		
		if( $type == 'dropdown' ):
		
			$choices = array('' => '-- Choose --');
		
		endif;
		
		foreach( $lines as $line ):
		
			$bits = explode(":", $line);
			
			if( count($bits) == 1 ):

				$choices[trim($bits[0])] = trim($bits[0]);
			
			else:
			
				$choices[trim($bits[0])] = trim($bits[1]);
			
			endif;
		
		endforeach;
		
		return $choices;
	}
	
}

/* End of file field.choice.php */