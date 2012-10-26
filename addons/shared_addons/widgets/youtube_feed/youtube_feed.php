<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Show YouTube videos in your site
 * 
 * @author		Edward Meehan
 * @author		Wetumka Interactive, LLC
 * @package		PyroCMS\Core\Widgets
 */
class Widget_Youtube_feed extends Widgets
{

	/**
	 * The translations for the widget title
	 *
	 * @var array
	 */
	public $title = array(
		'en' => 'YouTube Feed'
	);

	/**
	 * The translations for the widget description
	 *
	 * @var array
	 */
	public $description = array(
		'en' => 'Display YouTube feeds on your website'
	);

	/**
	 * The author of the widget
	 *
	 * @var string
	 */
	public $author = 'Edward Meehan';

	/**
	 * The author's website.
	 * 
	 * @var string 
	 */
	public $website = 'http://wetumka.net/';

	/**
	 * The version of the widget
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * The fields for customizing the options of the widget.
	 *
	 * @var array 
	 */
	public $fields = array(
		array(
			'field' => 'username',
			'label' => 'Username',
			'rules' => 'required'
		),
		array(
			'field' => 'number',
			'label' => 'Number of videos',
			'rules' => 'numeric'
		),
		array(
			'field' => 'cache',
			'label' => 'Cache time',
			'rules' => 'numeric'
		)
	);

	/**
	 * The URL used to get user feed from the YouTube API
	 * https://gdata.youtube.com/feeds/api/users/userId/uploads
	 * @var string
	 */
	private $feed_url = 'https://gdata.youtube.com/feeds/api/users/';

	/**
	 * The main function of the widget.
	 *
	 * @param array $options The options for the youtube username and the number of videos to display
	 * @return array 
	 */
	public function run($options)
	{
		if (!$youtubefeed = $this->pyrocache->get('youtubefeed-'.$options['username'].'-'.$options['number'].'-'.$options['cache']))
		{
			/*
			$xmlFeed = new SimpleXMLElement(@file_get_contents($this->feed_url.$options['username'].'/uploads'));
			
			$entries = array();
			
			// Get the user channel link
			foreach ($xmlFeed->link as $link)
			{
				if( (string) $link['rel'] == 'alternate')
				{
					$linkURL = (string) $link['href'];
				}
			}
			
			// Get videos in the feed
			foreach ($xmlFeed->entry as $entry)
			{
					
				// get thumbnails loop
				$thumbnails = array();
				
				foreach ($entry->children('media',true)->group->thumbnail as $thumbnail)
				{
					array_push($thumbnails,(string) $thumbnail->attributes()->{'url'});
				}
				
				// build entry array
				array_push($entries,
					array(
						'published' => (string) $entry->published,
						'title' => (string) $entry->title,
						'content' => (string) $entry->content,
						'link' => (string) $entry->children('media',true)->group->player->attributes()->{'url'},
						'duration' => (string) $entry->children('media',true)->group->children('yt',true)->duration->attributes()->{'seconds'},
						'thumbnails' => $thumbnails,
						'statistics' => array(
							'favoriteCount' => (string) $entry->children('yt',true)->statistics->attributes()->{'favoriteCount'},
							'viewCount' => (string) $entry->children('yt',true)->statistics->attributes()->{'viewCount'},
						),
					)
				);
			}
			
			$youtubefeed = array(
				'author' => (string) $xmlFeed->author->name,
				'totalResults' => (string) $xmlFeed->children('openSearch',true)->totalResults,
				'link' => $linkURL,
				'entry' => $entries,
				
			);
*/
			$youtubefeed = @file_get_contents($this->feed_url.$options['username'].'/uploads');
			$this->pyrocache->write($youtubefeed, 'youtubefeed-'.$options['username'].'-'.$options['number'].'-'.$options['cache'], $options['cache']);
		}
		
		// Store the feed items
		return array(
			'username' => $options['username'],
			'youtubefeed' => $youtubefeed ? $youtubefeed : array(),
		);
	}

}