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
		'en' => 'Display videos from YouTube and Vimeo Channel'
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
        array(
            'field' => 'utsuggested',
            'label' => 'Ending',
        ),
        array(
            'field' => 'uthtml5',
            'label' => 'HTML5',
        ),
        array(
            'field' => 'vtitle',
            'label' => 'Title',
        ),
        array(
            'field' => 'vbyline',
            'label' => 'Byline',
        ),
        array(
            'field' => 'vportrait',
            'label' => 'Portrait',
        ),
        array(
            'field' => 'hosting',
            'label' => 'Hosting',
            'rules' => 'required'
        ),
	);

	/**
	 * The main function of the widget.
	 *
	 * @param array $options The options for the youtube username and the number of videos to display
	 * @return array 
	 */
	public function run($options)
	{
		if($options['hosting'] === 'youtube')
        {
            
    		if (!$videoFeed = $this->pyrocache->get('widgetvideo-youtube-'.$options['username'].$options['number'].$options['cache']))
    		{
    			
    			$videoFeed = @file_get_contents('https://gdata.youtube.com/feeds/api/users/'.$options['username'].'/uploads');
    			
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
                
                if(!array_key_exists('suggested', $options)){
                    $options['suggested'] = 0;
                };
                
                if(!array_key_exists('html5', $options)){
                    $options['html5'] = 0;
                }
        	}
    	}
    	elseif($options['hosting'] === 'vimeo' || $options['hosting'] === 'vimeo_channel')
        {
            if($options['hosting'] === 'vimeo')
            {
            	if (!$videoFeed = $this->pyrocache->get('widgetvideo-vimeo-'.$options['username'].$options['number'].$options['cache']))
	            {
	                
	                $videoFeed = @file_get_contents('http://vimeo.com/api/v2/'.$options['username'].'/videos.xml');
	                
	                $this->pyrocache->write($videoFeed, 'widgetvideo-vimeo-'.$options['username'].$options['number'].$options['cache'], $options['cache']);
	                
	            }
            }
            elseif($options['hosting'] === 'vimeo_channel')
			{
				if (!$videoFeed = $this->pyrocache->get('widgetvideo-vimeo-channel-'.$options['username'].$options['number'].$options['cache']))
	            {
	                
	                $videoFeed = @file_get_contents('http://vimeo.com/api/v2/channel/'.$options['username'].'/videos.xml');
	                
	                $this->pyrocache->write($videoFeed, 'widgetvideo-vimeo-channel-'.$options['username'].$options['number'].$options['cache'], $options['cache']);
	                
	            }
			}
            
                
            
            if($videoFeed)
            {
                    
                $xmlFeed = new SimpleXMLElement($videoFeed);
                
                $entries = array();
                
                if($options['number'] > count($xmlFeed->video))
                {
                    $options['number'] = count($xmlFeed->video);
                }
                
                // Get videos in the feed
                for ($i=0; $i < $options['number']; $i++)
                {
                        
                    $videoID = $xmlFeed->video[$i]->id;
                        
                    array_push($entries,$videoID);
                }
                
                $videoFeed = (object) array(
                    'totalResults'  => (string) count($xmlFeed->video),
                    'entry'         => $entries
                );
                
                
            }
        };
        
        if(!array_key_exists('utsuggested', $options)){
            $options['utsuggested'] = 0;
        };
                
        if(!array_key_exists('uthtml5', $options)){
            $options['uthtml5'] = 0;
        };
        
        if(!array_key_exists('vbyline', $options)){
            $options['vbyline'] = 0;
        };
        
        if(!array_key_exists('vportrait', $options)){
            $options['vportrait'] = 0;
        };
        
        if(!array_key_exists('vtitle', $options)){
            $options['vtitle'] = 0;
        };
        
        
		// Store the feed items
		return array(
			'username' => $options['username'],
			'width' => $options['width'],
			'height' => $options['height'],
			'utsuggested' => $options['utsuggested'],
			'uthtml5' => $options['uthtml5'],
			'vtitle' => $options['vtitle'],
			'vportrait' => $options['vportrait'],
			'vbyline' => $options['vbyline'],
			'videohost' => $options['hosting'], 
			'videoFeed' => $videoFeed ? $videoFeed : array(),
		);
	}

}