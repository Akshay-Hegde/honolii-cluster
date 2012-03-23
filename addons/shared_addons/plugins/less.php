<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * LessC Plugin
 *
 * Development Less Compiler
 *
 * @package		PyroCMS
 * @author		Wetumka Interactive
 * @copyright	Copyright (c) 2011 - 2012 Wetumka LLC
 *
 */
class Plugin_Less extends Plugin
{
	/**
	 * Compile
	 *
	 * Usage:
	 * {{ less:compile file="" output="" }}
	 *
	 * @param	array
	 * @return	array
	 */
	function compile()
	{
		$this->load->library('lessc');
		$this->load->library('asset');
		
		$file 		= $this->attribute('file', 'style.less');
		$attributes	= $this->attributes();
		$module		= $this->attribute('module', '_theme_');
		$output 	= $this->attribute('output', 'style.css');
		$base		= $this->attribute('base', '');
		
		foreach (array('file', 'module', 'base', 'output') as $key)
		{
			if (isset($attributes[$key]))
			{
				unset($attributes[$key]);
			}
			else if ($key === 'file')
			{
				return '';
			}
		}
		
		try {
			
			lessc::ccompile('./'.$this->asset->css_path($file,$module),'./'.$this->asset->css_path($output,$module));
			
			return $this->asset->css($output, $module, $attributes, $base);
			
		} catch (exception $ex) {
			exit('lessc fatal error:<br />'.$file.','.$module.','.$base.'<br />'.$ex->getMessage());
		}
	}
}
