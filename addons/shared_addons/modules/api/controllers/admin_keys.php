<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller for the api module
 * 
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS\Core\Modules\API\Controllers
 */
class Admin_keys extends Admin_Controller
{
	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'keys';

	public function __construct()
	{
		parent::__construct();
		
		$this->load->language('api');

		$this->load->driver('Streams');
	}
	
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->streams->cp->entries_table('keys', 'api', Settings::get('records_per_page'), 'admin/api/keys/index', true, array(
			'buttons' => array(
				array(
			        'label'     => lang('global:edit'),
			        'url'       => 'admin/api/keys/edit/-entry_id-',
			    ),
			    array(
			        'label'     => lang('global:delete'),
			        'url'       => 'admin/api/keys/delete/-entry_id-',
			        'confirm'   => true,
			    ),
			),
		));
	}

	/**
	 * Create a new API Key
	 *
	 * This uses the Streams API CP driver which
	 * is designed to handle repetitive CP tasks
	 * down to even loading the page.
	 *
	 * @return	void
	 */
	public function create()
	{
		$this->template->title(lang('global:add'));

		$this->streams->cp->entry_form('keys', 'api', 'new', null, true, array(
			'return'			=> 'admin/api/keys',
			// 'success_message'	=> lang('faq:submit_success'),
			// 'failure_message'	=> lang('faq:submit_failure'),
			'title'				=> lang('global:add')
		));
	}

	/**
	 * Edit existing API Key
	 *
	 * This uses the Streams API CP driver which
	 * is designed to handle repetitive CP tasks
	 * down to even loading the page.
	 *
	 * @return	void
	 */
	public function edit($id)
	{
		$this->template->title(lang('global:edit'));

		$this->streams->cp->form('keys', 'api', 'edit', $id, true, array(
			'return'			=> 'admin/api/keys',
			// 'success_message'	=> lang('faq:submit_success'),
			// 'failure_message'	=> lang('faq:submit_failure'),
			'title'				=> lang('global:add')
		));
	}
}

/* End of file admin.php */