<?php
/**
* @version      1.0.0 18.05.2012
* @author       MAXXmarketing GmbH
* @package      pm_saferpay
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      MAXXmarketing GmbH
*/
defined('_JEXEC') or die('Restricted access');

class pm_saferpay extends PaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	function showAdminFormParams($params){
		JSFactory::loadExtLanguageFile("pm_saferpay");        
		$orders = &JModel::getInstance('orders', 'JshoppingModel'); //admin model
		include(dirname(__FILE__)."/adminparamsform.php");
	}

	function checkTransaction($params, $order, $act){
		$saferpay_verifypayconfirm_url = "https://www.saferpay.com/hosting/VerifyPayConfirm.asp";
		$saferpay_paycomplete_url = "https://www.saferpay.com/hosting/PayCompleteV2.asp";
		
		$data = JRequest::getVar('DATA', null, 'default', 'none', JREQUEST_ALLOWRAW);
		$signature = JRequest::getVar('SIGNATURE', null, 'default', 'none', JREQUEST_ALLOWRAW);
		
		if (!isset($data) || !isset($signature) || !strlen($data) || !strlen($signature))
			return array(0, 'DATA or SIGNATURE not found. Order number '.$order->order_id);
			
		$xml_DATA = new DOMDocument();
		if (!$xml_DATA->loadXML($data))
			return array(0, 'Can\'t load DATA as XML. Order number '.$order->order_id);

		if (!$xml_DATA->documentElement->hasAttribute("ACCOUNTID")) return array(0, 'Attribute ACCOUNTID not found. Order number '.$order->order_id);
		if (!$xml_DATA->documentElement->hasAttribute("AMOUNT")) return array(0, 'Attribute AMOUNT not found. Order number '.$order->order_id);
		if (!$xml_DATA->documentElement->hasAttribute("CURRENCY")) return array(0, 'Attribute CURRENCY not found. Order number '.$order->order_id);
		if (!$xml_DATA->documentElement->hasAttribute("ORDERID")) return array(0, 'Attribute ORDERID not found. Order number '.$order->order_id);
		
		$saferpay_verifypayconfirm_attributes = array();
		$saferpay_verifypayconfirm_attributes["ACCOUNTID"] = $params['accountid'];
		$saferpay_verifypayconfirm_attributes["DATA"] = $data;
		$saferpay_verifypayconfirm_attributes["SIGNATURE"] = $signature;
	
		try {
			$postdata = http_build_query( $saferpay_verifypayconfirm_attributes );
			$options = array('http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata));
			$context  = stream_context_create($options);
			$saferpay_verifypayconfirm_msg = file_get_contents($saferpay_verifypayconfirm_url, false, $context);
		}
		catch (Exception $ex) {
			return array(0, $ex->getMessage().'. Order number '.$order->order_id);
		}
		
		$saferpay_verifypayconfirm_data = array();
		if (substr($saferpay_verifypayconfirm_msg, 0, 3) != "OK:")
			return array(0, htmlentities($saferpay_verifypayconfirm_msg).'. Order number '.$order->order_id);
		else
			parse_str(substr($saferpay_verifypayconfirm_msg, 3), $saferpay_verifypayconfirm_data);
		
		if (!isset($saferpay_verifypayconfirm_data["ID"]))
			return array(0, 'Saferpay transaction identifier (ID) not found. Order number '.$order->order_id);
		
		if ($xml_DATA->documentElement->getAttribute("ACCOUNTID") != $params['accountid']) return array(0, 'Attribute mismatch: ACCOUNTID. Order number '.$order->order_id);
		if ($xml_DATA->documentElement->getAttribute("AMOUNT") != 100 * $order->order_total) return array(0, 'Attribute mismatch: AMOUNT. Order number '.$order->order_id);
		if ($xml_DATA->documentElement->getAttribute("CURRENCY") != $order->currency_code_iso) return array(0, 'Attribute mismatch: CURRENCY. Order number '.$order->order_id);
		if ($xml_DATA->documentElement->getAttribute("ORDERID") != $order->order_id) return array(0, 'Attribute mismatch: ORDERID. Order number '.$order->order_id);
		
		$captured = false;
		$capturetry = 0;
		
		while (!$captured && $capturetry < 3 && $capturetry !=-1){
			$capturetry++;
			
			$saferpay_paycomplete_attributes = array();
			$saferpay_paycomplete_attributes["ACCOUNTID"] = $params['accountid'];
			$saferpay_paycomplete_attributes["ID"] = $saferpay_verifypayconfirm_data["ID"];
			if (substr($params['accountid'],0,6) == "99867-") {
				$saferpay_paycomplete_attributes["spPassword"] = "XAjc3Kna";
			} else {
				if ($params['accountid']) {
					$saferpay_paycomplete_attributes["spPassword"] = $params['sppassword'];
				}
			}
			
			$saferpay_paycomplete_msg = "";
			try {
				$postdata = http_build_query( $saferpay_paycomplete_attributes );
				$options = array('http' => array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $postdata));
				$context  = stream_context_create($options);
				$saferpay_paycomplete_msg = file_get_contents($saferpay_paycomplete_url, false, $context);		
			}
			catch (Exception $ex) {
				return array(0, $ex->getMessage().'. Order number '.$order->order_id);
			}
		
			if (substr($saferpay_paycomplete_msg, 0, 3) == "OK:") {
				$xml_Paycomplete = new DOMDocument();
				if ($xml_Paycomplete->loadXml(substr($saferpay_paycomplete_msg,3))) {
					if ($xml_Paycomplete->documentElement->tagName == "IDP"	&& $xml_Paycomplete->documentElement->hasAttribute("RESULT") && $xml_Paycomplete->documentElement->getAttribute("RESULT") == "0") {
						$captured = true;
					}
					else {
						$capturetry = -1;
					}
				}
			}
			else { 
				sleep(2);
			}
		}
		if ($captured) return array(1, '');
		return array(0, "Error response. Order ID ".$order->order_id);
	}

	function showEndForm($params, &$order){
		$action = "https://www.saferpay.com/hosting/CreatePayInit.asp";
		
		$data = array();
		$data['accountid'] = $params['accountid'];
		$data['amount'] = 100 * $order->order_total;
		$data['currency'] = $order->currency_code_iso;
		$data['description'] = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
		$data['orderid'] = $order->order_id; // $data['orderid'] = $order->order_number;
		$data['successlink'] = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_saferpay".$link;
		$data['faillink'] = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_saferpay".$link;
		$data['backlink'] = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_saferpay".$link;
		$data['notifyurl'] = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_saferpay&no_lang=1".$link;
		
		$data['address'] = 'DELIVERY';
		if ($order->d_firma_name != "") {
			$data['company'] = $order->d_firma_name;
			$data['gender'] = 'c';
		} else {
			if ($order->d_title == 1) $data['gender'] = 'm';
			if ($order->d_title == 2) $data['gender'] = 'f';
		}
		$data['firstname'] = $order->d_f_name;
		$data['lastname'] = $order->d_l_name;
		$data['street'] = $order->d_street;
		$data['zip'] = $order->d_zip;
		$data['city'] = $order->d_city;
		
		$db =& JFactory::getDBO();
		$query = "SELECT `country_code_2` FROM `#__jshopping_countries` WHERE `country_id` = '".$order->d_country."' LIMIT 1";
        $db->setQuery($query);
		$data['country'] = $db->LoadResult();
		
		$data['email'] = $order->d_email;
		$data['phone'] = $order->d_phone;
		
		?>
		<?php print _JSHOP_REDIRECT_TO_PAYMENT_PAGE?>
        <br/>
		<?php
		
		$return_link = "";
		try {
			$postdata = http_build_query($data);
			$options = array('http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata));
			$context  = stream_context_create($options);
			$return_link = file_get_contents($action, false, $context);
		} catch (Exception $ex) {
			die ($ex->getMessage()); 
		} ?>

		<script type="text/javascript">
			location.replace("<?php echo $return_link;?>");
		</script>
		
		<?php die();
	}
    
    function getUrlParams($pmconfigs){
		$data = JRequest::getVar('DATA', null, 'default', 'none', JREQUEST_ALLOWRAW);
		$params = array();
		$xml = new DOMDocument();
		if ($xml->loadXML($data)) {
			if ($xml->documentElement->hasAttribute("ORDERID")) {
				$params['order_id'] = $xml->documentElement->getAttribute("ORDERID");
			}
		} else {
			$params['order_id'] = 0;
		}
		$params['checkHash'] = 0;
        $params['checkReturnParams'] = 1;
		return $params;
    }
	
	function checkPaymentInfo($params, $pmconfigs){
        return 1;
	} 
	
	function getParams(){
        return array();
    }
	
	function nofityFinish($params, $order, $rescode){
		$post_data = JRequest::getVar('DATA', null, 'default', 'none', JREQUEST_ALLOWRAW);
		$post_signature = JRequest::getVar('SIGNATURE', null, 'default', 'none', JREQUEST_ALLOWRAW);
		
		if(!isset($post_data) or !isset($post_signature))
			die ("Error:P");
		
		if((strlen($post_data)>8191) or (strlen($post_signature)>512))
			die ("Error:L");
		
		$data = new DOMDocument('1.0', 'utf-8');
		try {
			$data->loadXML($post_data);
		}
		catch (Exception $ex){
			die ("Error:X");
		}
		
		if (!$data->documentElement->hasAttribute("ID"))
			die ("Error:A");
		echo "OK";
	}
}
?>