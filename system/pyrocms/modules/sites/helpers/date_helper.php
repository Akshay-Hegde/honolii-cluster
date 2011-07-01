<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * CodeIgniter Date Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Philip Sturgeon
 */

// ------------------------------------------------------------------------

function format_date($unix, $format = '')
{
	$ci =& get_instance();
	
	if ($unix == '' || ! is_numeric($unix))
	{
		$unix = strtotime($unix);
	}
	elseif($unix == 0)
	{
		return '';
	}

	if ( ! $format)
	{
		$format = $ci->settings->date_format;
	}

	return strstr($format, '%') !== FALSE
		? ucfirst(utf8_encode(strftime($format, $unix))) //or? strftime($format, $unix)
		: date($format, $unix);
}