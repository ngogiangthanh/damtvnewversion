<?php
/*------------------------------------------------------------------------
 # Yt Sobi2 Slideshow  - Version 1.2
 # Copyright (C) 2009-2010 The YouTech Company. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: The YouTech Company
 # Websites: http://www.ytcvn.com
 -------------------------------------------------------------------------*/


(defined( '_VALID_MOS' ) || defined( '_JEXEC' ) ) || ( trigger_error( 'Restricted access', E_USER_ERROR ) && exit() );

defined( "DS" )	|| define( "DS",DIRECTORY_SEPARATOR);
$add = 	defined( 'JPATH_SITE' ) ?  DS.'mod_yt_sobi2slideshow' : null;
defined( '_SOBI_CMSROOT' ) || define( '_SOBI_CMSROOT', str_replace( DS.'modules'.$add, null, dirname( __FILE__ ) ) );


require_once (dirname(__FILE__).DS.'helper.php');

jimport("joomla.filesystem.folder");
jimport("joomla.filesystem.file");

/*-- Process---*/
$note 							= $params->get("note", 0);
$target 						= $params->get("target", '');
$jquery 						= $params->get("jquery", 0);
$total                          = $params->get("total", 1);
$play 							= $params->get("play", 'true');
$theme 							= $params->get("theme", 'default');
$effect 						= $params->get("effect", 'fade');
$slideshow_speed 				= $params->get("slideshow_speed", 800);
$timer_speed 					= $params->get("timer_speed", 4000);
$start_clock_on_mouseOut 		= $params->get("start_clock_on_mouseOut", 'true');
$start_clock_on_mouseOutAfter 	= $params->get("start_clock_on_mouseOutAfter", 3000);
$caption_animation_speed 		= $params->get("caption_animation_speed", 800);
$background 					= $params->get("background", '#FFFFFF');
$title_color 					= $params->get("title_color", '#FFFFFF');
$start                          = $params->get("start", 0);
$prenext_show 					= $params->get("prenext_show", 1);
$caption_show 					= $params->get("caption_show", 'true');
$thumb_height 					= $params->get('thumb_height', "940");
$thumb_width 					= $params->get('thumb_width', "450");		
$show_readmore 					= $params->get('show_readmore', "0");
$show_description 				= $params->get('show_description', "0");
$description_color 				= $params->get('description_color', "#FFFFFF");
$link_caption					= $params->get('link_caption', 1);
$link_image						= $params->get('link_image', 1);
$auto_play						= $params->get('auto_play', 1);

$pre_img = '<img src="'.JURI::root().'modules/'.$module->module.'/assets/1.png" />';
$nex_img = '<img src="'.JURI::root().'/modules/'.$module->module.'/assets/2.png" />';
$readmore_img = JText::_('read more ').'&nbsp;&nbsp;<img src="'.JURI::root().'modules/'.$module->module.'/assets/readmore.gif" />';

$center = round($thumb_height/2);
$bottom = 220;
if($center>$bottom)
	$botoom = $center; 
if (!defined ('K2SLIDESHOW')) {
	define ('K2SLIDESHOW', 1);
	
	if (!defined ('YTCJQUERY')){
		define('YTCJQUERY', 1);
		JHTML::script('ytc.jquery-1.5.min.js', JURI::base() . '/modules/'.$module->module.'/assets/js/');				
	}
	JHTML::script('jquery.cycle.all.js',JURI::base() . '/modules/'.$module->module.'/assets/js/');	

	/* Add css*/	
	$browser = new Browser();
	
	if($browser->Name=='msie' && floor($browser->Version)==6)
	{
		JHTML::stylesheet('ie6.css', JURI::base() . '/modules/'.$module->module.'/assets/');		
	}
	else if($browser->Name=='msie' && floor($browser->Version)==7)	
	{
		JHTML::stylesheet('style.css', JURI::base() . '/modules/'.$module->module.'/assets/');
		JHTML::stylesheet('ie7.css', JURI::base() . '/modules/'.$module->module.'/assets/');
	}
	else
	{
		JHTML::stylesheet('style.css', JURI::base() . '/modules/'.$module->module.'/assets/');	
	}	

}


$items = modSobi2SlideshowHelper::process($params, $module);
$count_items = count($items);
 if($total>$count_items){
    $total = $count_items;
}
$width_buttom_middle =  ($total*21)+63;

$path = JModuleHelper::getLayoutPath( 'mod_yt_sobi2slideshow', $theme );
if (file_exists($path)) {
	require($path);
}
?>
