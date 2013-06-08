<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Streams Search Model
 *
 * @package		Streams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Search_m extends CI_Model {

	private $CI;

	// --------------------------------------------------------------------------   

	function __construct()
	{
		$this->CI = get_instance();
	}

	// --------------------------------------------------------------------------   

	/**
	 * Perform a search
	 *
	 * @access	public
	 * @param	string - the search term
	 * @param	string - the search type
	 * @param	string - the stream slug
	 * @param	string - fields to search (sep by |)
	 * @param 	string - stream namespace
	 * @return 	int - cache id
	 */
	function perform_search($search_term, $search_type, $stream_slug, $fields, $namespace)
	{
		// -------------------------------------
		// Separate our fields
		// -------------------------------------
		
		$fields = explode('|', $fields);
		
		// -------------------------------------
		// Get Stream Data
		// -------------------------------------
		
		$stream			= $this->CI->streams_m->get_stream($stream_slug, true, $namespace);
		
		if ( ! $stream) {
			show_error($stream_slug.' '.lang('streams:not_valid_stream'));
		}
	
		// -------------------------------------
		// Query
		// -------------------------------------
		
		$query_string = $this->build_query($fields, $search_term, $stream, $search_type);
		
		// Get total of all the results
		$total = $this->CI->db->query($query_string);

		// -------------------------------------
		// Save Query to DB Cache
		// -------------------------------------
	
		$insert_data = array(
			'search_id' 		=> md5(rand()),
			'search_term'		=> $search_term,
			'ip_address'		=> $this->CI->input->ip_address(),
			'total_results'		=> $total->num_rows(),
			'query_string'		=> base64_encode($query_string),
			'stream_slug'		=> $stream_slug,
			'stream_namespace'	=> $namespace
		);
		
		$this->CI->db->insert('data_stream_searches', $insert_data);
		
		// Return our hash for the URL
		return $insert_data['search_id'];
	}

	public function build_query($fields, $search_term, $stream, $search_type = 'keywords')
	{				
		$keywords 		= $this->CI->security->xss_clean($search_term);
		
		$keywords		= explode(" ", $keywords);
		
		// Break into keywords
		foreach ($keywords as $key => $keyword) {
		
			if (trim($keyword) == '') {
			
				unset($keywords[$key]);
			}
		}

		$likes = array();
		
		if ($search_type == 'keywords') {

			$keyword_build = '';
		
			// Go through each keyword/field individually
			foreach ($keywords as $keyword) {
			
				$keyword_build .= $keyword.' ';
			
				foreach ($fields as $field) {
				
					$likes[] = "$field LIKE '%".mysql_real_escape_string($keyword)."%'";
					
					// We also search cumulative keywords
					$likes[] = "$field LIKE '%".mysql_real_escape_string($keyword_build)."%'";
				}
			}
		}
		
		if ($search_type == 'full_phrase') {
		
			$search_for = implode(' ', $keywords);

			foreach ($fields as $field) 
			{
				$likes[] = "$field LIKE '%".mysql_real_escape_string($search_for)."%'";
			}
		}

		// Build query sans limit/offset
		$query_string = 'SELECT * FROM '.$this->CI->db->dbprefix($stream->stream_prefix.$stream->stream_slug).' WHERE ('.implode(' OR ', $likes).')';

		return $query_string;
	}

	// --------------------------------------------------------------------------   
	
	public function get_cache($cache_id)
	{
		$this->CI->db->limit(1)->where('search_id', $cache_id);
		$query = $this->CI->db->get('data_stream_searches');
		
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$cache = $query->row();
		
			// Decode
			$cache->query_string = base64_decode($cache->query_string);
			
			return $cache;
		}
	}

}

/* End of file search_m.php */