<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * FAQ details file
 * 
 * @author      Stephen Cozart
 * @link		http://www.stephencozart.com
 * @package 	PyroCMS
 * @subpackage  FAQ Module
 * @category	module class
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
class Module_Faq extends Module {

	public $version = '2.1.0';
	
	public function info()
	{
		return array(
			'name' => array(
				'en' => 'FAQ'
			),
			'description' => array(
				'en' => 'Manage frequently asked questions.'
			),
			'frontend' => TRUE,
			'backend'  => TRUE,
			'menu'	  => 'content',
			'author' => 'Stephen Cozart',
			'sections' => array(
				'faqs' => array(
					'name'=>'faq_questions_title',
					'uri'=>'admin/faq',
				    'shortcuts' => array(
						array(
					 	   'name' => 'faq_create_title',
						    'uri' => 'admin/faq/create',
							'class'=>'add'
						),
					),
				),
			    'categories' => array(
				    'name' => 'faq_category_index_title',
				    'uri' => 'admin/faq/categories',
				    'shortcuts' => array(
						array(
					 	   'name' => 'faq_category_create_title',
						    'uri' => 'admin/faq/categories/create',
							'class'=>'add'
						),
					),
				),
			)
		);
	}
	
	public function install()
	{
		
		$this->dbforge->drop_table('faqs');
		$this->dbforge->drop_table('faqs_categories');
		
		$tbl_faqs = $this->db->dbprefix('faqs');
		$tbl_cat = $this->db->dbprefix('faqs_categories');
		
		$faqs = "
			CREATE TABLE `{$tbl_faqs}` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `category_id` int(11) DEFAULT '0',
			  `question` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `answer` text COLLATE utf8_unicode_ci DEFAULT NULL,
			  `published` enum('yes', 'no'),
			  `order` int DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$categories = "
			CREATE TABLE `{$tbl_cat}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
				`title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
				`description` tinytext COLLATE utf8_unicode_ci NULL,
				`published` enum('yes', 'no'),
				PRIMARY KEY(`id`),
				UNIQUE(`slug`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$faqs_sql = $this->db->query($faqs);
		$categories_sql = $this->db->query($categories);
		
		return ($faqs_sql && $categories_sql);
	}

	public function uninstall()
	{
		$faqs_sql = $this->dbforge->drop_table('faqs');
		$categories_sql = $this->dbforge->drop_table('faqs_categories');
		
		return ($faqs_sql && $categories_sql);
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}
	
	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "Some Help Stuff";
	}
}
/* End of file details.php */