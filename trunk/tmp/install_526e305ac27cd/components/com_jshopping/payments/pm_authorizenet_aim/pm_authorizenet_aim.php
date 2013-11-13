<?php
defined('_JEXEC') or die('Restricted access');

class pm_authorizenet_aim extends PaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        JSFactory::loadExtLanguageFile("pm_authorizenet_aim");
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	function showAdminFormParams($params){
      JSFactory::loadExtLanguageFile("pm_authorizenet_aim");
	  $orders = &JModel::getInstance('orders', 'JshoppingModel'); //admin model
      include(dirname(__FILE__)."/adminparamsform.php");	  
	}

	function checkTransaction($pmconfigs, $order, $act){
        $jshopConfig = &JSFactory::getConfig();
        
        $item_name = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
        
        $pymentparamsdata = $order->getPaymentParamsData();        
        
        $lang = &JSFactory::getLang();
        $field_country_name = $lang->get("name");
        $_country = &JTable::getInstance('country', 'jshop');
        $_country->load($order->country);
        $country = $_country->$field_country_name;
        
        if ($pmconfigs['testserver']){
            $post_url = "https://test.authorize.net/gateway/transact.dll";
        }else{
            $post_url = "https://secure.authorize.net/gateway/transact.dll";
        }
        
        $post_values = array(    
            "x_login"           => $pmconfigs['login'],
            "x_tran_key"        => $pmconfigs['tran_key'],

            "x_version"         => "3.1",
            "x_delim_data"      => "TRUE",
            "x_delim_char"      => "|",
            "x_relay_response"  => "FALSE",

            "x_type"            => "AUTH_CAPTURE",
            "x_method"          => "CC",
            "x_card_num"        => $pymentparamsdata['card_number'],
            "x_exp_date"        => $pymentparamsdata['month'].$pymentparamsdata['year'],

            "x_amount"          => $order->order_total,
            "x_description"     => $item_name,

            "x_first_name"      => $order->f_name,
            "x_last_name"       => $order->l_name,
            "x_address"         => $order->street,
            "x_state"           => $order->state,
            "x_zip"             => $order->zip,
            "x_city"            => $order->city,
            "x_country"         => $country,
            
            "x_invoice_num"     => $order->order_number,            
        );
        
        if ($pmconfigs['testmode']){
            $post_values['x_test_request'] = "TRUE";   
        }        
        
        $post_string = "";
        foreach( $post_values as $key => $value )
            { $post_string .= "$key=" . urlencode( $value ) . "&"; }
        $post_string = rtrim( $post_string, "& " );
        
        $request = curl_init($post_url); 
            curl_setopt($request, CURLOPT_HEADER, 0); 
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); 
            $post_response = curl_exec($request);             
        curl_close ($request);
        
        if ($jshopConfig->savelog && $jshopConfig->savelogpaymentdata){
            saveToLog("paymentdata.log", $post_response);
        }
        
        $response_array = explode($post_values["x_delim_char"],$post_response);
        
        if ($response_array[0]==1){
            return array(1, '');
        }else{
            saveToLog("payment.log", "Error. Order ID ".$order->order_id.". ".$response_array[3]);
            return array(3, $response_array[3]);
        }        
	}

    function showEndForm($params, $order){
        $db =& JFactory::getDBO();
        $hash = md5("authorizenetcc1".mktime());
        
        $query = "update `#__jshopping_orders` set `order_hash`='".$db->getEscaped($hash)."' WHERE `order_id` = '".$order->order_id."'";
        $db->setQuery($query);
        $db->query();
                
        $mainframe =& JFactory::getApplication();
        $mainframe->redirect(SEFLink('index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_authorizenet_aim&oid='.$order->order_id."&h=".$hash ,0,1));
    }
    
    function getUrlParams(){
        $params = array(); 
        $params['order_id'] = JRequest::getInt("oid");
        $params['hash'] = JRequest::getVar("h");;
        $params['checkHash'] = 1;
        $params['checkReturnParams'] = 1;        
    return $params;
    }
    
}

?>