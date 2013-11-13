<?php
/**
 * @package Sj Categories Slider for JoomShopping
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

include_once dirname(__FILE__).'/helper_base.php';

class JsSliderHelper extends JsSliderBaseHelper{

	public static function getList(&$params)
	{
		$db = JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$jshopConfig->cur_lang = $jshopConfig->frontend_lang;
		JSFactory::loadCssFiles();
		JSFactory::loadLanguageFile();
		$lang = JSFactory::getLang();
		$jshopConfig = JSFactory::getConfig();
		$field_sort = $params->get('sort', 'id');
		$ordering = $params->get('ordering', 'asc');
		$_limit= (int)$params->get('count_cat',10);
		$catids = $params->get('catids',0);
		$_catids = array();
		$list = array();
		settype($catids,'array');
		if(!empty($catids)){
			$catid = implode(", ", $catids);
			$list = self::getCategory($catid,$field_sort,$ordering,$_limit,1);
		}
		if(!empty($list)){
			foreach($list as $i=> $item){
				$item->_short_desc  = self::_cleanText($item->short_description); 
				$item->_description = self::_cleanText($item->description);
				$item->_description = ($item->_description !='')?$item->_description:$item->_short_desc;
				self::getJSCImages($item, $params,'imgcfgcat');
			}
		}
		return $list;	
	}
	
	public static   function getCategory($catid, $order = 'id', $ordering = 'asc', $limit, $publish = 0) {
		$_db = JFactory::getDBO();
		$lang = JSFactory::getLang();
        $user = JFactory::getUser();
        $add_where = ($publish)?(" AND category_publish = '1' "):("");
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $add_where .=' AND access IN ('.$groups.')';
        if ($order=="id") $orderby = "category_id";
        if ($order=="name") $orderby = "`".$lang->get('name')."`";
        if ($order=="ordering") $orderby = "ordering";
        if (!$orderby) $orderby = "ordering";
		if($limit > 0){
			$_limit = " LIMIT 0 , ".$limit;
		}else{
			$_limit = "";
		}
        $query = "SELECT `".$lang->get('name')."` as name,`".$lang->get('description')."` as description,`".$lang->get('short_description')."` as short_description, category_id, category_publish, ordering, category_image FROM `#__jshopping_categories`
                   WHERE category_id IN (".$catid.") ".$add_where."
                   ORDER BY ".$orderby." ".$ordering." ".$_limit ;
        $_db->setQuery($query);
        $categories = $_db->loadObjectList();
        foreach($categories as $key=>$value){
            $categories[$key]->link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$categories[$key]->category_id, 1);
        }        
        return $categories;
    }
	
	

}
