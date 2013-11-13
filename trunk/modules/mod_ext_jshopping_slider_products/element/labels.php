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
class JFormFieldLabels extends JFormField {

  public $type ='Labels';
  
  protected function getInput(){
        require_once (JPATH_SITE.'/modules/mod_ext_jshopping_slider_products/helper.php'); 
        $tmp = new stdClass();
        $tmp->id = "";
        $tmp->name = JText::_('JALL');
        $element_1  = array($tmp);
        $productLabel = JTable::getInstance('productLabel', 'jshop');
        $listLabels = $productLabel->getListLabels();
        $elementes_select =array_merge($element_1 , $listLabels); 
        $ctrl  =  $this->name ;  
        $value        = empty($this->value) ? '' : $this->value; 
        
        return JHTML::_('select.genericlist', $elementes_select, $ctrl,'class="inputbox"','id', 'name', $value );
  }
}

?>
