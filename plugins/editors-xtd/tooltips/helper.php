<?php
/**
 * Plugin Helper File
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
class plgButtonTooltipsHelper
{
	function __construct(&$params)
	{
		$this->params = $params;
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function render($name)
	{
		$button = new JObject;

		if (JFactory::getApplication()->isSite() && !$this->params->enable_frontend) {
			return $button;
		}

		JHtml::stylesheet('nnframework/style.min.css', false, true);

		$this->params->tag = preg_replace('#[^a-z0-9-_]#s', '', $this->params->tag);

		if ($this->params->button_use_custom_code && $this->params->button_custom_code) {
			$text = trim($this->params->button_custom_code);
			$text = str_replace(array("\r", "\n"), array('', '</p>\n<p>'), trim($text)) . '</p>';
			$text = preg_replace('#^(.*?)</p>#', '\1', $text);
		} else {
			$text = '{' . $this->params->tag . ' ' . JText::_('TT_TITLE') . '::' . JText::_('TT_TEXT') . '}' . JText::_('TT_LINK') . '{/' . $this->params->tag . '}';
		}
		$text = str_replace('\\\\n', '\\n', addslashes($text));
		$text = str_replace('{', '{\'+\'', $text);

		$js = "
			function insertTooltips(editor) {
				jInsertEditorText('" . $text . "', editor);
			}
		";
		JFactory::getDocument()->addScriptDeclaration($js);

		$class = 'nonumber icon-tooltips';

		$text_ini = strtoupper(str_replace(' ', '_', $this->params->button_text));
		$text = JText::_($text_ini);
		if ($text == $text_ini) {
			$text = JText::_($this->params->button_text);
		}

		$button->modal = false;
		$button->class = 'btn';
		$button->link = '#';
		$button->onclick = 'insertTooltips(\'' . $name . '\');return false;';
		$button->text = trim($text);
		$button->name = $class;

		return $button;
	}
}
