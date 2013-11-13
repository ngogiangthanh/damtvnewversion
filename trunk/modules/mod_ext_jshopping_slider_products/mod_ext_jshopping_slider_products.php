<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2013 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 16/09/2013 - 13:00
# ------------------------------------------------------------------------
*/


// No direct access.
defined('_JEXEC') or die;
    error_reporting(E_ALL & ~E_NOTICE);
	//error_reporting(error_reporting() & ~E_NOTICE);
    
   if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    } 
	
	require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');       

         
    JSFactory::loadCssFiles();
    JSFactory::loadLanguageFile();
    $jshopConfig = JSFactory::getConfig();
	$document 				= JFactory::getDocument();
	$document->addStyleSheet(JURI::base() . 'modules/mod_ext_jshopping_slider_products/assets/css/style.css');
    
    $product = JTable::getInstance('product', 'jshop');
    $cat_str = $params->get('catids',NULL); 
    if (is_array($cat_str)) {    
        $cat_arr = array();
        foreach($cat_str as $key=>$curr){
           if (intval($curr)) $cat_arr[$key] = intval($curr);
        }  
    } else {
        $cat_arr = array();
        if (intval($cat_str)) $cat_arr[] = intval($cat_str);
    }
	$count 					= $params->get('count_products', 4);
	$label_id 				= $params->get('label_id');
	$ext_products 			= $params->get('ext_products');
	
    if( $ext_products == 0 ) { $last_prod = $product->getLastProducts($count, $cat_arr); }
	if( $ext_products == 1 ) { $last_prod = $product->getTopRatingProducts($count, $cat_arr); }
	if( $ext_products == 2 ) { $last_prod = $product->getBestSellers($count, $cat_arr); }
	if( $ext_products == 3 ) { $last_prod = $product->getProductLabel($label_id, $count); }
	
    foreach($last_prod as $key=>$value){
        $last_prod[$key]->product_link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$value->category_id.'&product_id='.$value->product_id, 1);
    }
	
    $noimage = "noimage.gif";
	$ext_generate_id		=(int)$params->get('ext_generate_id', 1);
    $show_image 			= (int)$params->get('show_image',1);	
	$jquery   				= (int)$params->get('jquery', 1);
	$ext_script_bx   		= (int)$params->get('ext_script_bx', 1);
	$ext_jquery_ver			= $params->get('ext_jquery_ver', '1.6.4');
	$class_sfx				= htmlspecialchars($params->get('class_sfx'));
	
	$ext_short_desc			= $params->get('ext_short_desc');
	$ext_review_mark		= $params->get('ext_review_mark');
	$ext_count_commentar	= $params->get('ext_count_commentar');
	$ext_item_detal			= $params->get('ext_item_detal');
	$ext_buttom_text		= $params->get('ext_buttom_text');
	$ext_width				= $params->get('ext_width');
	$ext_height				= $params->get('ext_height');
	$ext_label_prod			= $params->get('ext_label_prod');
	$ext_mode				= $params->get('ext_mode');
	$ext_speed				= $params->get('ext_speed');
	$ext_controls			= $params->get('ext_controls');
	$ext_auto				= $params->get('ext_auto');
	$ext_autohover			= $params->get('ext_autohover');
	$ext_pause				= $params->get('ext_pause');
	$ext_auto_delay			= $params->get('ext_auto_delay');
	$ext_pager				= $params->get('ext_pager');
	$ext_pager_type 		= $params->get('ext_pager_type');
	$ext_pager_location		= $params->get('ext_pager_location');
	$ext_pager_saparator	= $params->get('ext_pager_saparator');
	$ext_display_slide_qty	= $params->get('ext_display_slide_qty');
	$ext_move_slide_qty		= $params->get('ext_move_slide_qty');
	
	$id_sfx					= $params->get('id_sfx', '1');
	if ($ext_generate_id == 1) {
		$rand1 = rand(1,100);
		$rand2 = rand(1,100);
		$id_sfx = $rand1.$rand2;
	}	
	
$ext_script = <<<SCRIPT


var jQ = false;
function initJQ() {
	if (typeof(jQuery) == 'undefined') {
		if (!jQ) {
			jQ = true;
			document.write('<scr' + 'ipt type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/$ext_jquery_ver/jquery.min.js"></scr' + 'ipt>');
		}
		setTimeout('initJQ()', 50);
	}
}
initJQ(); 


SCRIPT;

	if ($jquery == 1) {
		$document->addScriptDeclaration($ext_script);
	}
	if ($ext_script_bx == 1 ) {
		$document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().'modules/mod_ext_jshopping_slider_products/assets/js/jquery.bxSlider.min.js"></script>');	
	}
	$document->addCustomTag('<script type = "text/javascript">if (jQuery) jQuery.noConflict();</script>');	
	
	require JModuleHelper::getLayoutPath('mod_ext_jshopping_slider_products', $params->get('layout', 'default'));
	echo JText::_(COP_JOOMLA);
?>