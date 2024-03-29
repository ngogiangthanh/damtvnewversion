<?php
/**
 * @version     $Id$
 * @package     JSNExtension
 * @subpackage  JSNTPLFramework
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import necessary Joomla libraries
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Helper class handling template editions
 *
 * @package     JSNTPLFramework
 * @subpackage  Template
 * @since       1.0.0
 */
class JSNTplTemplateEdition
{
	/**
	 * JSNTplTemplateEdition instance
	 * @var JSNTplTemplateEdition
	 */
	private static $_instance;

	/**
	 * Template data
	 * @var JObject
	 */
	protected $data;

	/**
	 * Current edition of the template
	 * @var string
	 */
	protected $edition;

	/**
	 * All supported editions for current template
	 * @var array
	 */
	protected $editions;

	/**
	 * Return instance of JSNTplTemplateEdition object
	 *
	 * @param   JObject  $data  Template data object
	 * @return  JSNTplTemplateEdition
	 */
	public static function getInstance ($data)
	{
		if (!(self::$_instance instanceOf JSNTplTemplateEdition))
			self::$_instance = new JSNTplTemplateEdition($data);

		return self::$_instance;
	}

	/**
	 * Check if current template is Pro edition
	 *
	 * @return  booles
	 */
	public function isPro()
	{
		return $this->edition == 'FREE' ? false : true;
	}

	/**
	 * Retrieve current edition of the template
	 *
	 * @return  string
	 */
	public function getEdition ()
	{
		return $this->edition;
	}

	/**
	 * Return next edition can be upgraded
	 *
	 * @return string
	 */
	public function getNextEdition ()
	{
		sort($this->editions);

		$editionCount = count($this->editions);
		$currentIndex = array_search($this->edition, $this->editions);

		// Backward compatible
		if ( ! $currentIndex)
		{
			$edition = strpos($this->edition, 'PRO ') === 0 ? str_replace('PRO ', '', $this->edition) : "PRO {$this->edition}";
			$currentIndex = array_search($edition, $this->editions);
		}

		$nextIndex = $currentIndex + 1;

		// Return false if current edition is highest
		if ($editionCount == 1 OR ! isset($this->editions[$nextIndex]))
		{
			return false;
		}

		return $this->editions[$nextIndex];
	}

	/**
	 * Constructor method
	 *
	 * @param   JObject  $data  Template data object
	 */
	private function __construct ($data)
	{
		$this->data = $data;
		$this->edition = (string) $this->data->xml->edition;

		$this->loadEditions();

		if (empty($this->edition))
		{
			$this->edition = 'FREE';
		}
	}

	/**
	 * Return list of editions for current template
	 * that supported
	 *
	 * @return  array
	 */
	protected function loadEditions ()
	{
		$cacheFile = JPATH_SITE . '/templates/' . $this->data->template . '/editions.json';

		// Retrieve template editions from cache file
		if (is_file($cacheFile) AND is_readable($cacheFile))
		{
			$editions = json_decode(JFile::read($cacheFile), true);

			if ( ! empty($editions))
			{
				$this->editions = $editions;

				return $editions;
			}
		}

		try
		{
			$response	= JSNTplHttpRequest::get(JSN_TPLFRAMEWORK_VERSIONING_URL . '?category=cat_template');
			$json		= json_decode(trim($response['body']), true);
		}
		catch (Exception $e)
		{
			// Do nothing
		}

		// Return only free edition if cannot parse data returned from server
		if (empty($json) OR ! is_array($json) OR ! isset($json['items']))
		{
			$this->editions = array('FREE');
		}
		// Retrieve template edition from server
		else
		{
			$templateId = JSNTplHelper::getTemplateId($this->data->template);
			$this->editions = array('FREE');

			foreach ($json['items'] AS $item)
			{
				if ($templateId == $item['identified_name'])
				{
					if (isset($item['editions']) AND is_array($item['editions']))
					{
						foreach ($item['editions'] AS $edition)
						{
							$e = strtoupper(trim($edition['edition']));

							in_array($e, $this->editions) OR $this->editions[] = $e;
						}
					}
					elseif (isset($item['edition']) AND ! empty($item['edition']))
					{
						$e = strtoupper(trim($item['edition']));

						in_array($e, $this->editions) OR $this->editions[] = $e;
					}
				}
			}
		}

		// Cache edition data
		$buffer = json_encode($this->editions);

		JFile::write($cacheFile, $buffer);
	}
}
