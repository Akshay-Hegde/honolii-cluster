<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * The Maintenance Module - currently only remove/empty cache folder(s)
 *
 * @author		Donald Myers
 * @package		PyroCMS
 * @subpackage	Maintenance Module
 * @category	Modules
 */

$config['maintenance.cache_protected_folders'] 	= array('simplepie');
$config['maintenance.cannot_remove_folders'] 	= array('codeigniter','themes_m');

// An array of database tables that are eligible to be exported. Sub array is additional tables that will be
// fetched and returned as sub arrays to the main table data.
$config['maintenance.export_tables']	=
//Example:'main_table'	   				 (additional table  => array(second table column	==	main table column)
	array('users' 				=> array('profiles'			=> array('user_id' 				=> 'id'),
										 'groups'			=> array('id' 					=> 'group_id'),
										 'permissions' 		=> array('group_id' 			=> 'group_id'),
										 ),
		  'contact_log' 		=> array(),
		  'file_folders' 		=> array('files'			=> array('folder_id'			=> 'id')),
		  'pages' 				=> array('page_chunks'		=> array('page_id'				=> 'id')),
		  'blog_categories'  	=> array('blog'				=> array('category_id'			=> 'id')),
		  'navigation_groups'	=> array('navigation_links'	=> array('navigation_group_id' 	=> 'id')),
		  'comments'			=> array(),
		  );