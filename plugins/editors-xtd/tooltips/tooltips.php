<?php
/**
 * Main Plugin File
 * Does all the magic!
 *
 * @package         Tooltips
 * @version         3.4.0
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2013 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 ** Plugin that places the button
 */
class plgButtonTooltips extends JPlugin
{
	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function onDisplay($name)
	{
		jimport('joomla.filesystem.file');

		// return if system plugin is not installed
		if (!JFile::exists(JPATH_PLUGINS . '/system/' . $this->_name . '/' . $this->_name . '.php')) {
			return;
		}

		// return if NoNumber Framework plugin is not installed
		if (!JFile::exists(JPATH_PLUGINS . '/system/nnframework/nnframework.php')) {
			return;
		}

		// load the admin language file
		$lang = JFactory::getLanguage();
		if ($lang->getTag() != 'en-GB') {
			// Loads English language file as fallback (for undefined stuff in other language file)
			$lang->load('plg_' . $this->_type . '_' . $this->_name, JPATH_ADMINISTRATOR, 'en-GB');
		}
		$lang->load('plg_' . $this->_type . '_' . $this->_name, JPATH_ADMINISTRATOR, null, 1);

		// Load system plugin parameters
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/parameters.php';
		$parameters = NNParameters::getInstance();
		$params = $parameters->getPluginParams($this->_name);


		// Include the Helper
		require_once JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/helper.php';
		$class = get_class($this) . 'Helper';
		$helper = new $class($params);

		return $helper->render($name);
	}
}
