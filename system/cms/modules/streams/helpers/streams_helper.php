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
 * @return 	mixed
 */
function check_stream_permission($stream, $redirect = true)
{
	$CI = get_instance();

	if ( ! isset($CI->current_user->group) or $CI->current_user->group == 'admin') return true;

	if ( ! isset($stream->permissions)) return true;

	$perms = @unserialize($stream->permissions);
	if ( ! is_array($perms)) return true;

	if (in_array($CI->current_user->group_id, $perms)) return true;

	if ($redirect)
	{
		$CI->session->set_flashdata('error', lang('cp:access_denied'));
		redirect('admin/streams');
	}
	else
	{
		return false;
	}
}