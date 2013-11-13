<?php 
/*------------------------------------------------------------------------
 # Yt Sobi2 Slideshow  - Version 1.2
 # Copyright (C) 2009-2010 The YouTech Company. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: The YouTech Company
 # Websites: http://www.ytcvn.com
 -------------------------------------------------------------------------*/
 
 
defined('_JEXEC') or die('Restricted access');
if (! class_exists("modSobi2SlideshowHelper") ) { 
require_once (dirname(__FILE__) .DS. 'assets' .DS.'ytc_sobi2.php');

class modSobi2SlideshowHelper {
	
	function process($params, $module) {
		
		$enable_cache 		=   $params->get('cache',1);
		$cachetime			=   $params->get('cache_time',0);		
		
		if($enable_cache==1) {			
			$conf =& JFactory::getConfig();
			$cache = &JFactory::getCache($module->module);
			$cache->setLifeTime( $params->get( 'cache_time', $conf->getValue( 'config.cachetime' ) * 60 ) );
			$cache->setCaching(true);
			$cache->setCacheValidation(true);
			$items =  $cache->get( array('modSobi2SlideshowHelper', 'getList'), array($params, $module));
		} else {
			$items = modSobi2SlideshowHelper::getList($params, $module);
		}
		
		return $items;		
		
	}
	
	
	function getList ($params, $module) {

		$content = new YtcSobi2Slideshow();
		$content->featured = $params->get('featured', 2);
		$content->showtype = $params->get('showtype', 1);
		$content->category = $params->get('category', 0);
		$content->listIDs = $params->get('itemIds', '');
		$content->limit = $params->get('total', 5);
		$content->sort_order_field = $params->get('sort_order_field', "created");
		$content->type_order = $params->get('sort_order', "DESC");
		$content->thumb_height = $params->get('thumb_height', "150px");
		$content->thumb_width = $params->get('thumb_width', "120px");
		$content->customUrl = $params->get('customUrl', '');
		$content->small_thumb_height = $params->get('small_thumb_height', "0");
		$content->small_thumb_width = $params->get('small_thumb_width', "0");
		
		$content->web_url = JURI::base();
		$content->max_title		=   $params->get('limittitle',25);
		$content->max_main_description		=   $params->get('limit_description',25);
		
		$content->resize_folder = JPATH_CACHE.DS. $module->module .DS."images";
		$content->url_to_resize = $content->web_url . "cache/". $module->module ."/images/";
		$content->cropresizeimage = $params->get('cropresizeimage', 1);
		
		$items = $content->getList();
		
		
		return $items;
	}
}
			
} 
if(!class_exists('Browser')){
	class Browser
	{
		var $props    = array("Version" => "0.0.0",
									"Name" => "unknown",
									"Agent" => "unknown") ;
	
		function __Construct()
		{
			$browsers = array("firefox", "msie", "opera", "chrome", "safari",
								"mozilla", "seamonkey",    "konqueror", "netscape",
								"gecko", "navigator", "mosaic", "lynx", "amaya",
								"omniweb", "avant", "camino", "flock", "aol");
	
			$this->Agent = strtolower($_SERVER['HTTP_USER_AGENT']);
			foreach($browsers as $browser)
			{
				if (preg_match("#($browser)[/ ]?([0-9.]*)#", $this->Agent, $match))
				{
					$this->Name = $match[1] ;
					$this->Version = $match[2] ;
					break ;
				}
			}
		}
	
		function __Get($name)
		{
			if (!array_key_exists($name, $this->props))
			{
				die("No such property or function {$name}");
			}
			return $this->props[$name] ;
		}
	
		function __Set($name, $val)
		{
			if (!array_key_exists($name, $this->props))
			{
				SimpleError("No such property or function.", "Failed to set $name", $this->props);
				die;
			}
			$this->props[$name] = $val ;
		}
	
	} 
}	
?>

