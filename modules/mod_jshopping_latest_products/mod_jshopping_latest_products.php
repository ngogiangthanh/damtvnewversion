<?php
/**
* @version      4.0.0 12.10.2012
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

    defined('_JEXEC') or die('Restricted access');
    error_reporting(error_reporting() & ~E_NOTICE);
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    } 
    
    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');        
    JSFactory::loadCssFiles();
    JSFactory::loadLanguageFile();
    $jshopConfig = JSFactory::getConfig();
    
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
    $last_prod = $product->getLastProducts($params->get('count_products', 4), $cat_arr);   
    foreach($last_prod as $key=>$value){
        $last_prod[$key]->product_link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$value->category_id.'&product_id='.$value->product_id, 1);
    }
    $noimage = "noimage.gif";
    $show_image = $params->get('show_image',1);
    require(JModuleHelper::getLayoutPath('mod_jshopping_latest_products'));        
?>