<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Show videos in your site
 * 
 * @author		Edward Meehan
 * @author		Wetumka Interactive, LLC
 * @package		PyroCMS\Core\Widgets
 */
class Widget_Instagram_feed extends Widgets
{

	/**
	 * The translations for the widget title
	 *
	 * @var array
	 */
	public $title = array(
		'en' => 'Instagram Feed'
	);

	/**
	 * The translations for the widget description
	 *
	 * @var array
	 */
	public $description = array(
		'en' => 'Display images by hashtag and filter by username'
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
			'field' => 'hashtag',
			'label' => 'Hash tag',
			'rules' => 'required'
		),
		array(
			'field' => 'username',
			'label' => 'Username',
		),
		array(
            'field' => 'number',
            'label' => 'Number of images',
            'rules' => 'numeric'
        ),
		array(
			'field' => 'cache',
			'label' => 'Cache time',
			'rules' => 'numeric'
		)
	);

	/**
	 * The URL used to get user feed from the Instagram RSS feed
	 * http://instagram.com/tags/HASHTAG/feed/recent.rss
	 */


	/**
	 * The main function of the widget.
	 *
	 * @param array $options The options to view images by hashtag and filter by user name.
	 * @return array 
	 */
	public function run($options)
	{
		if (!$instagramFeed = $this->pyrocache->get('widgetinstagram-feed-'.$options['hashtag'].$options['username'].$options['cache']))
		{
			
			$instagramFeed = @file_get_contents('http://instagram.com/tags/'.$options['hashtag'].'/feed/recent.rss');
			
            $this->pyrocache->write($instagramFeed, 'widgetinstagram-feed-'.$options['hashtag'].$options['username'].$options['cache'], $options['cache']);
			
        }
            
        
        if($instagramFeed)
        {
                
            $xmlFeed = new SimpleXMLElement($instagramFeed);
            
            $entries = array();
            
            // Loop the items
            foreach ($xmlFeed->channel->item as $item)
            {
                if($options['username'] != NULL)
                {
                    $value = $options['username'];
                    $value2 = (string) $item->children('media',true)->credit;
                    if($value === $value2)
                    {
                        array_push($entries,$item);
                    }
                }
                else
                {
                    array_push($entries,$item);
                }
            }
            
            // reduce the list
            if($options['number'] != NULL && count($entries) > $options['number'])
            {
                $entries = array_slice($entries, 0, (int) $options['number']);
            }
    		
            $instagramFeed = (object) array(
                'entry'  => $entries
            );

    	}
		
		// Store the feed items
		return array(
			'username' => $options['username'],
			'instagramFeed' => $instagramFeed ? $instagramFeed : array(),
		);
	}

}