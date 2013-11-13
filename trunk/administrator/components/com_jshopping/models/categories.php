<?php
/**
* @version      4.1.0 03.01.2011
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelCategories extends JModelLegacy{
    
    function getAllList($display=0){
        $db = JFactory::getDBO();        
        $lang = JSFactory::getLang();
        if (isset($order) && $order=="id") $orderby = "`category_id`";
        if (isset($order) && $order=="name") $orderby = "`".$lang->get('name')."`";
        if (isset($order) && $order=="ordering") $orderby = "ordering";
        if (isset($orderby) && !$orderby) $orderby = "ordering";
        $query = "SELECT `".$lang->get('name')."` as name, category_id FROM `#__jshopping_categories` ORDER BY ordering";
        $db->setQuery($query);        
        $list = $db->loadObjectList();
        if ($display==1){
            $rows = array();
            foreach($list as $k=>$v){
                $rows[$v->category_id] = $v->name;    
            }
            unset($list);
            $list = $rows;
        }
        return $list;
    }
    
    function getSubCategories($parentId, $order = 'id', $ordering = 'asc') {
        $db = JFactory::getDBO();        
        $lang = JSFactory::getLang();
        if ($order=="id") $orderby = "`category_id`";
        if ($order=="name") $orderby = "`".$lang->get('name')."`";
        if ($order=="ordering") $orderby = "ordering";
        if (!$orderby) $orderby = "ordering";
        $query = "SELECT `".$lang->get('name')."` as name,`".$lang->get('short_description')."` as short_description, category_id, category_publish, ordering, category_image FROM `#__jshopping_categories`
                   WHERE category_parent_id = '".$db->escape($parentId)."'
                   ORDER BY ".$orderby." ".$ordering;
        $db->setQuery($query);        
        return $db->loadObjectList();
    }
    
    function getAllCatCountSubCat() {
        $db = JFactory::getDBO();        
        $query = "SELECT C.category_id, count(C.category_id) as k FROM `#__jshopping_categories` as C
                   inner join  `#__jshopping_categories` as SC on C.category_id=SC.category_parent_id
                   group by C.category_id";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $rows = array();
        foreach($list as $row){
            $rows[$row->category_id] = $row->k;
        }        
        return $rows;
    }
    
    function getAllCatCountProducts(){
        $db = JFactory::getDBO();    
        $query = "SELECT category_id, count(product_id) as k FROM `#__jshopping_products_to_categories` group by category_id";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $rows = array();
        foreach($list as $row){
            $rows[$row->category_id] = $row->k;
        }        
        return $rows;
    }
    
    function deleteCategory($category_id){
        $db = JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_categories` WHERE `category_id` = '" . $db->escape($category_id) . "'";
        $db->setQuery($query);
        $db->query();
    }
    
    function getTreeAllCategories($order = null, $orderDir = null) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();

        $query = "SELECT ordering, category_id, category_parent_id, `".$lang->get('name')."` as name, `".$lang->get('short_description')."` as short_description, category_publish, category_image FROM `#__jshopping_categories`
                  ORDER BY category_parent_id, ". $this->_allCategoriesOrder($order, $orderDir);
        $db->setQuery($query);
        $all_cats = $db->loadObjectList();

        $categories = array();
        if (count($all_cats)){
            foreach($all_cats as $key=>$category){
                $category->isPrev = 0; $category->isNext = 0;
                if (isset($all_cats[$key-1]) && $category->category_parent_id == $all_cats[$key-1]->category_parent_id){
                    $category->isPrev = 1;
                }
                if (isset($all_cats[$key+1]) && $category->category_parent_id == $all_cats[$key+1]->category_parent_id){
                    $category->isNext = 1;
                }
                
                if (!$category->category_parent_id){
                    recurseTree($category, 0, $all_cats, $categories, 0);
                }
            }
        }

        if (count($categories)){
            foreach($categories as $key=>$category){
                $category->space = ''; 
                for ($i = 0; $i < $category->level; $i++){
                    $category->space .= '<span class = "gi">|—</span>';
                }
            }
        }
        return $categories;
    }
   
    function _allCategoriesOrder($order = null, $orderDir = null){
        $lang = JSFactory::getLang();
        if ($order && $orderDir){
            $fields = array("name" => "`".$lang->get('name')."`", "id" => "`category_id`", "description" => "`".$lang->get('description')."`", "ordering" => "`ordering`");
            if (strtolower($orderDir) != "asc") $orderDir = "desc";
            if (!$fields[$order]) return "`ordering` ".$orderDir;
            return $fields[$order]." ".$orderDir;
        }else{
            return "`ordering` asc";
        }
    }
    
}
?>