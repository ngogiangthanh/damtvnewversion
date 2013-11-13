<?php

/*------------------------------------------------------------------------
 # Yt Sobi2 Slideshow  - Version 1.2
 # Copyright (C) 2009-2010 The YouTech Company. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: The YouTech Company
 # Websites: http://www.ytcvn.com
 -------------------------------------------------------------------------*/



 
// no direct access

defined('_JEXEC') or die ('Restricted access');





class JElementSobi2category extends JElement

{

    /**

     * @access private

     */

	var	$_name = 'sobi2category';



	function fetchElement($name, $value, &$node, $control_name) {

		$db = &JFactory::getDBO();
		
		$query = "SELECT `#__sobi2_cats_relations`.catid as id, parentid as parent , name,  ordering " .
				 "FROM `#__sobi2_cats_relations` " .
				 "LEFT JOIN `#__sobi2_categories` ON `#__sobi2_categories`.catid = `#__sobi2_cats_relations`.catid " .
				 "WHERE published = 1 ORDER BY parent  ASC, ordering";
		
		$db->setQuery( $query );

		$mitems = $db->loadObjectList();		
	
		
		$children = array();
		
		
		
		if ( $mitems )

		{

			foreach ( $mitems as $v )

			{

				$pt 	= $v->parent ;

				$list 	= @$children[$pt] ? $children[$pt] : array();

				array_push( $list, $v );

				$children[$pt] = $list;

			}

		}
		
		$list = JHTML::_('menu.treerecurse', 1, '', array(), $children, 9999, 0, 0 );
		
		
			
		$mitems = array();

		$mitems[] = JHTML::_('select.option', '0', '-- '.JText::_('All Categories'));
		
		
		
		foreach ( $list as $item ) {

			$mitems[] = JHTML::_('select.option',  $item->id, '---| '.$item->treename );

		}

		

		$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', 

						'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );

		return $output;

	}

	

}

