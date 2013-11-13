<?php
/**
* @version      2.9.0 31.07.2010
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelStaticText extends JModelLegacy{ 

    function getList(){
        $lang = JSFactory::getLang();
        $db = JFactory::getDBO();         
        $query = "SELECT id, alias FROM `#__jshopping_config_statictext` ORDER BY id";
        $db->setQuery($query);
        return $db->loadObjectList();
    }      
}
?>