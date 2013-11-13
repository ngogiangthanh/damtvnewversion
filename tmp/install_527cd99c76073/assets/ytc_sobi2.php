<?php
/*------------------------------------------------------------------------
 # Yt Sobi2 Slideshow  - Version 1.2
 # Copyright (C) 2009-2010 The YouTech Company. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: The YouTech Company
 # Websites: http://www.ytcvn.com
 -------------------------------------------------------------------------*/


if (! class_exists("YtcSobi2Slideshow") ) {
class YtcSobi2Slideshow {
	var $items = array();
	var $featured = 0;	// 0 - without frontpage, 1 - only frontpage - 2 both
	var $type = 0;
	var $category = array();
	var $limit = 5;
	var $listIDs = array();
    var $customUrl = array();      
	var $arrCustomUrl = array();
	var $showtype = 1;		
	var $sort_order_field = 'created';
	var $type_order = 'DESC';	
	var $thumb_width = '40';
	var $thumb_height = '40';
	var $small_thumb_width = '0';
	var $small_thumb_height = '0';
	var $web_url = '';	
	var $cropresizeimage = 0;
	var $max_title = 0;
	var $max_main_description = 0;
    var $max_normal_description = 0;
	var $resize_folder = '';
	var $url_to_resize = '';
	var $url_to_module = '';	
	
	function Content() {
		
	}
		
	function getList() {
			global $mainframe;			
			
			$arrCustomUrl = YtcSobi2Slideshow::getArrURL();
		
			class_exists( 'sobi2Config' ) || require_once( _SOBI_CMSROOT.DS.'components'.DS.'com_sobi2'.DS.'config.class.php' );

			
			$user = &JFactory::getUser();
			$aid = $user->get('aid');
			$db = &JFactory::getDBO();
			
			$jnow = &JFactory::getDate();
			$now = $jnow->toMySQL();
			$nullDate = $db->getNullDate();
			
			$where = YtcSobi2Slideshow::getCondition();
			
			$config =& sobi2Config::getInstance();
			$database =& $config->getDb();
			$S_Itemid = $config->sobi2Itemid;
			$catId = sobi2Config::request( $_REQUEST,'catid',0 );
			
			$now = $config->getTimeAndDate();

			
			
			$query = "SELECT si.title, si.itemid, si.icon, si.image,si.last_update, sfd.data_txt  
FROM `#__sobi2_item` si 
LEFT JOIN  #__sobi2_fields_data sfd ON sfd.itemid = si.itemid 
LEFT JOIN #__sobi2_language sl ON (sl.fieldid = sfd.fieldid AND sl.langKey = 'field_description') 
WHERE  `published` = 1 AND (`publish_down` > '{$now}' OR `publish_down` = '{$config->nullDate}') AND sl.fieldid <> ''";
			
			
			//$this->sort_order_field = "RAND()";
			if ($this->sort_order_field == 'random'){
			     $query .=  $where . ' GROUP BY si.itemid ORDER BY rand()';
            }elseif($this->sort_order_field == 'title'){
                $this->type_order = 'ASC';
                $query .=  $where . ' GROUP BY si.itemid  ORDER BY ' . $this->sort_order_field . ' ' . $this->type_order;
            }
            else {
                $query .=  $where . ' GROUP BY si.itemid  ORDER BY ' . $this->sort_order_field . ' ' . $this->type_order;			
            }	
			$query .=  $this->limit ? ' LIMIT ' . $this->limit : '';

			$db->setQuery($query);
			$items = $db->loadObjectlist();
			
			if( empty($items) ) return array();
			
			foreach( $items as $key => &$item ){	                          
                if(array_key_exists($item->itemid,$arrCustomUrl)){
                    $item->link = $arrCustomUrl[$item->itemid]; //echo '<pre>'.print_r($item->link);die;       
                } else {
				    $item->link = "index.php?option=com_sobi2&amp;sobi2Task=sobi2Details&amp;sobi2Id={$item->itemid}&amp;Itemid={$S_Itemid}";
                }
				$myTitle = $config->getSobiStr($item->title);
				$item->date = JHtml::_('date', $item->last_update, JText::_('DATE_FORMAT_LC2')); 
				if($item->image && file_exists("{$config->absolutePath}/images/com_sobi2/clients/{$item->image}")) {
					$item->main_image = "images/com_sobi2/clients/{$item->image}";
				} else {
					$item->main_image = '';
				}
				$item->title = $myTitle;
			}

			$k2items = array();

			foreach ($items as $key => $article){
				$sobi2 = array();
				$sobi2['itemid'] = $article->itemid;
				$sobi2['title'] = $article->title;
				$sobi2['img'] = $article->main_image;
				$sobi2['content'] = $article->data_txt;
				$sobi2['link'] = $article->link;
				$sobi2items[] = $sobi2;
			}
			
		

			$items = $this->update($sobi2items);
			
			
			
			return $items;	
			
		}		
	
	
	
		
		
		function unhtmlentities($string) 
		{
			$trans_tbl = array("&lt;" => "<", "&gt;" => ">", "&amp;" => "&");
			return strtr($string, $trans_tbl);
		}
		
		
		function getFile($name, $modPath, $tmplPath) {
			if (file_exists(JPATH_SITE.DS.$tmplPath.$name)) {
				return $tmplPath.$name;
			}
			return $modPath.$name;
		}
		
		function getCondition() {	
			
			$sql = '';
			switch ($this->showtype) {
				case 0:
					if ($this->category == 0) {
						$sql = '';
					} else {
						$catids = !is_array($this->category) ? $this->category : '"'.implode('","',$this->category).'"';
						$sql = ' AND  (si.itemid IN (SELECT itemid FROM #__sobi2_cat_items_relations WHERE catid IN( '.$catids.' )))';
					}
					
					break;
				case 1:
					$ids = explode(',', $this->listIDs);	
					$tmp = array();
										
					foreach( $ids as $id ){
						$tmp[] = (int) trim($id);
					}
					$sql = " AND si.itemid IN('". implode( "','", $tmp ) ."')";
					
					break;
				default:
					$sql = '';
				
			}
			
			return $sql;
		}
		
	function getArrURL() {     
        $arrUrl = array();
        $tmp = explode("\n", trim($this->customUrl));            
        foreach( $tmp as $strTmp){
            $pos = strpos($strTmp, ":");
            if($pos >=0){
                $tmpKey = substr($strTmp, 0, $pos);
                $key = trim($tmpKey);
                $tmpLink = substr($strTmp, $pos+1, strlen($strTmp)-$pos);
                $haveHttp =  strpos(trim($tmpLink), "http://");
                //var_dump($haveHttp);die;                   
                if($haveHttp<0 || ($haveHttp !== false) ){
                    $link = trim($tmpLink);
                }else{
                    $link = "http://" . trim($tmpLink);
                }
                $arrUrl[$key] = $link;
            }  
        }            
        return $arrUrl;
    }	
	
	function update($items) {		
		$tmp = array();
		
		
		foreach ($items as $key => $item) {
			if (!isset($item['sub_title'])) {
				$item['sub_title'] = $this->cutStr($item['title'], $this->max_title);
			}
			if (!isset($item['sub_content'])) {
				$item['sub_content'] = $this->cutStr($item['content'], $this->max_main_description);
			}
            if (!isset($item['sub_normal_content'])) {
				$item['sub_normal_content'] = $this->cutStr($item['content'], $this->max_normal_description);
			}            
			
			if (!isset($item['thumb']) && $item['img'] != '') {
				$item['thumb'] = $this->processImage($item['img'], $this->thumb_width, $this->thumb_height, $item['itemid']);
			} else {
				$item['thumb'] = '';
			}	
			
			
			if (!isset($item['small_thumb']) && $item['img'] != '' && $this->small_thumb_width > 0 && $this->small_thumb_height > 0 ) {
				$item['small_thumb'] = $this->processImage($item['img'], $this->small_thumb_width, $this->small_thumb_height, $item['itemid']);
			} else {
				$item['small_thumb'] = '';
			}	
				
			
			//if ($item['thumb'] != '') {			
			$tmp[] = $item;
			//}
		}
		
		return $tmp;				
	}
	
	function processImage($img, $width, $height, $id) {
				
		$imagSource = JPATH_SITE.DS. str_replace( '/', DS,  $img );
		if(file_exists($imagSource) && is_file($imagSource)){	
			if ($this->cropresizeimage == 0){
				return $this->resizeImage($img, $width, $height, $id);
			} else {
				return $this->cropImage($img, $width, $height, $id);
			}
		
		} else{
		
			return '';
		}
	}
	
	function resizeImage($imagePath, $width, $height, $id) {
		global $module;
				
		$folderPath = $this->resize_folder;
		 
		 if(!JFolder::exists($folderPath)){
		 		JFolder::create($folderPath);	 
		 }
		 
		 $nameImg = str_replace('/','',strrchr($imagePath,"/"));
			
		 $ext = substr($nameImg, strrpos($nameImg, '.'));
		
		 $file_name = substr($nameImg, 0,  strrpos($nameImg, '.'));
		
		 $nameImg = $file_name . "_" . $id . "_" . $width . "_" . $height .  $ext;
		 
		 
		 if(!JFile::exists($folderPath.DS.$nameImg)){
			 $image = new SimpleImage();
	  		 $image->load($imagePath);
	  		 $image->resize($width,$height);
	   		 $image->save($folderPath.DS.$nameImg);
		 }else{
		 		 list($info_width, $info_height) = @getimagesize($folderPath.DS.$nameImg);
		 		 if($width!=$info_width||$height!=$info_height){
		 		 	 $image = new SimpleImage();
	  				 $image->load($imagePath);
	  				 $image->resize($width,$height);
	   				 $image->save($folderPath.DS.$nameImg);
		 		 }
		 }
   		 return $this->url_to_resize . $nameImg;
	}
	
	function cropImage($imagePath, $width, $height, $id) {
		global $module;
		
		$folderPath = $this->resize_folder;
		 
		if(!JFolder::exists($folderPath)){
		 		JFolder::create($folderPath);	 
		}
		 
		$nameImg = "crop" . "_" . $id . "_" . $width. '__'. $height. str_replace('/','',strrchr($imagePath,"/"));		 
		 
		 if(!JFile::exists($folderPath.DS.$nameImg)){
			 $image = new SimpleImage();
	  		 $image->load($imagePath);
	  		 $image->crop($width,$height);
	   		 $image->save($folderPath.DS.$nameImg);
		 }else{
		 		 list($info_width, $info_height) = @getimagesize($folderPath.DS.$nameImg);
		 		 if($width!=$info_width||$height!=$info_height){
		 		 	 $image = new SimpleImage();
	  				 $image->load($imagePath);
	  				 $image->crop($width,$height);
	   				 $image->save($folderPath.DS.$nameImg);
		 		 }
		 }
		 
   		 return $this->url_to_resize . $nameImg;
	}
	
	/*Cut string*/
	function cutStr( $str, $number){
		//If length of string less than $number
		$str = strip_tags($str);
		if(strlen($str) <= $number){
			return $str;
		}
		$str = substr($str,0,$number);
	
		$pos = strrpos($str,' ');
	
		return substr($str,0,$pos).'...';
	}
	
}
}
if (! class_exists("SimpleImage") ) {
class SimpleImage {
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
     	 
		 
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
   			
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
   	  $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image	, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image; 
   }    
   function getbeginWidth($width){
   $k = $this->getWidth();
   $x1 = ($k - $width)/2;
   return $x1;
   }
   function getbeginHeight($height){
   $k = $this->getHeight();
   $y1 = ($k - $height)/2;
   return $y1;
   }
   function crop($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, $this->getbeginWidth($width), $this->getbeginHeight($height),  $width, $height, $width, $height);
      $this->image = $new_image;   
   }   
}
}
?>
