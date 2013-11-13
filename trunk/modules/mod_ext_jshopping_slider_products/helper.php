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

require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');        
   
$db = JFactory::getDBO();
$jshopConfig = JSFactory::getConfig();
$jshopConfig->cur_lang = $jshopConfig->frontend_lang;
?>