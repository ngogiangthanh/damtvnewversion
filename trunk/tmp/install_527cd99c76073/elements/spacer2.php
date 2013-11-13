<?php

/*------------------------------------------------------------------------
 # Yt Sobi2 Slideshow  - Version 1.2
 # Copyright (C) 2009-2010 The YouTech Company. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: The YouTech Company
 # Websites: http://www.ytcvn.com
 -------------------------------------------------------------------------*/





// Check to ensure this file is within the rest of the framework

defined('JPATH_BASE') or die();



/**

 * Renders the TC logo

 *

 * @package 	Joomla.Framework

 * @subpackage		Parameter

 * @since		1.5

 */



class JElementSpacer2 extends JElement

{

	/**

	* Element name

	*

	* @access	protected

	* @var		string

	*/

	var	$_name = 'Spacer2';



	function fetchTooltip($label, $description, &$node, $control_name, $name) {

		return '<img border="0" src="../modules/mod_ytc_contentslider/elements/blank.gif" width="190" height="12" title="Ytc Content Slider Module" alt="Ytc Content Slider Module">';

	}



	function fetchElement($name, $value, &$node, $control_name)

	{

		if ($value) {

			return JText::_($value);

		} else {

			return '';

		}

	}

}

