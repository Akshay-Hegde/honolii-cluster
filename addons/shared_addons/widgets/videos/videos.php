<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Show videos in your site
 * 
 * @author		Edward Meehan
 * @author		Wetumka Interactive, LLC
 * @package		PyroCMS\Core\Widgets
 */
class Widget_Videos extends Widgets
{

	/**
	 * The translations for the widget title
	 *
	 * @var array
	 */
	public $title = array(
		'en' => 'Videos'
	);

	/**
	 * The translations for the widget description
	 *
	 * @var array
	 */
	public $description = array(
		'en' => 'Display videos from YouTube Channel'
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
		),
		array(
            'field' => 'width',
            'label' => 'Width',
            'rules' => 'required'
        ),
        array(
            'field' => 'height',
            'label' => 'Height',
            'rules' => 'required'
        ),
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
		if (!$videoFeed = $this->pyrocache->get('widgetvideo-youtube-'.$options['username'].$options['number'].$options['cache']))
		{
			
			$videoFeed = @file_get_contents($this->feed_url.$options['username'].'/uploads');
			
            $this->pyrocache->write($videoFeed, 'widgetvideo-youtube-'.$options['username'].$options['number'].$options['cache'], $options['cache']);
			
        }
            
        
        if($videoFeed)
        {
                
            $xmlFeed = new SimpleXMLElement($videoFeed);
            
            $entries = array();
            
            if($options['number'] > count($xmlFeed->entry))
            {
                $options['number'] = count($xmlFeed->entry);
            }
            
    		// Get videos in the feed
    		for ($i=0; $i < $options['number']; $i++)
    		{
    			    
                $videoID = substr( strrchr( $xmlFeed->entry[$i]->id, '/' ), 1 );
    				
    			array_push($entries,$videoID);
    		}
    		
            $videoFeed = (object) array(
                'author'        => (string) $xmlFeed->author->name,
                'totalResults'  => (string) $xmlFeed->children('openSearch',true)->totalResults,
                'entry'         => $entries
            );	
    	}

		
		// Store the feed items
		return array(
			'username' => $options['username'],
			'width' => $options['width'],
			'height' => $options['height'],
			'videoFeed' => $videoFeed ? $videoFeed : array(),
		);
	}

}