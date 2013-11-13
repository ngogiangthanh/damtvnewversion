<?php
/**
* @version      4.3.1 13.08.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewProduct_edit extends JViewLegacy{

    function display($tpl=null){
        JToolBarHelper::title( ($this->edit) ? (_JSHOP_EDIT_PRODUCT.' "'.$this->product->name.'"') : (_JSHOP_NEW_PRODUCT), 'generic.png' );
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();
        parent::display($tpl);
	}

    function editGroup($tpl=null){
        JToolBarHelper::title(_JSHOP_EDIT_PRODUCT, 'generic.png');
        JToolBarHelper::save("savegroup");
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
}
?>