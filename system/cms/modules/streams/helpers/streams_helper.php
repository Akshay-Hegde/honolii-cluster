<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Streams Helper
 *
 * @author      Adam Fairholm
 */

/**
 * Check that the user has access to a  stream. Redirects
 * if this is not the case. This is like the streams
 * version of role_or_die.
 *
 * @access 	private
 * @return 	mixed
 */
function check_stream_permission($stream)
{
	$CI = get_instance();

	if ( ! isset($CI->current_user->group) or $CI->current_user->group == 'admin') return;

	if ( ! isset($stream->permissions)) return;

	$perms = @unserialize($stream->permissions);
	if ( ! is_array($perms)) return;

	if (in_array($CI->current_user->group_id, $perms)) return;

	$CI->session->set_flashdata('error', lang('cp_access_denied'));
	redirect('admin/streams');
}