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
class JFormFieldCategories extends JFormField {

  public $type = 'categories';
  
  protected function getInput(){
        require_once (JPATH_SITE.'/modules/mod_ext_jshopping_slider_products/helper.php'); 
        $tmp = new stdClass();  
        $tmp->category_id = "";
        $tmp->name = JText::_('JALL');
        $categories_1  = array($tmp);
        $categories_select =array_merge($categories_1 , buildTreeCategory(0)); 
        $ctrl  =  $this->name ;   
        $ctrl .= '[]'; 
        $value        = empty($this->value) ? '' : $this->value;    

        return JHTML::_('select.genericlist', $categories_select,$ctrl,'class="inputbox" id = "category_ordering" multiple="multiple"','category_id','name', $value );
  }
}
?>