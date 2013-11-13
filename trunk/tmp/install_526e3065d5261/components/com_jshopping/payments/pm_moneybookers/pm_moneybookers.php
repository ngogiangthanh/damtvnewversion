<?php
defined('_JEXEC') or die('Restricted access');

class pm_moneybookers extends PaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	function showAdminFormParams($params){
        JSFactory::loadExtLanguageFile("pm_moneybookers");
        $array_params = array('email_received', 'transaction_end_status', 'transaction_pending_status', 'transaction_failed_status');
        foreach ($array_params as $key){
            if (!isset($params[$key])) $params[$key] = '';
        }
        $orders = &JModel::getInstance('orders', 'JshoppingModel'); //admin model
        include(dirname(__FILE__)."/adminparamsform.php");	  
	}

	function checkTransaction($pmconfigs, $order, $act){
        $jshopConfig = &JSFactory::getConfig();
                
        if ($order->order_total != $_POST['amount']){
            return array(0, 'Error amount. Order ID '.$order->order_id);
        }
        if (strtolower($pmconfigs['email_received']) != strtolower($_POST['pay_to_email'])){
            return array(0, 'Error pay_to_email. Order ID '.$order->order_id);            
        }
        if ($order->currency_code_iso != $_POST['currency']){
            return array(0, 'Error currency. Order ID '.$order->order_id);
        }
        
        $status  = trim(stripslashes($_POST['status']));
        $merchant_id = $pmconfigs['merchant_id'];
        $transaction_id = trim(stripslashes($_POST['transaction_id']));
        $mb_amount =  trim(stripslashes($_POST['mb_amount']));
        $mb_currency = trim(stripslashes($_POST['mb_currency']));        
        $secretword = $pmconfigs['secretword'];
        
        if ($secretword==""){
            $string = $merchant_id.$transaction_id.$mb_amount.$mb_currency.$status;
        }else{
            $string = $merchant_id.$transaction_id.strtoupper(md5($secretword)).$mb_amount.$mb_currency.$status;
        }
        $check_md5 = strtoupper(md5($string));
        
        if ($check_md5!=$_POST['md5sig']){
            return array(0, 'Error md5sig '.$_POST['md5sig'].'. Order ID '.$order->order_id);
        }
        
        if ($status==2){
            return array(1, '');
        }elseif ($status==0){
            saveToLog("payment.log", "Status pending. Order ID ".$order->order_id.".");
            return array(2, "Status pending");
        }elseif ($status==-1){
            saveToLog("payment.log", "Status cancelled. Order ID ".$order->order_id.".");
            return array(3, "Status cancelled");
        }elseif ($status==-2){
            saveToLog("payment.log", "Status failed. Order ID ".$order->order_id.".");
            return array(3, "Status failed");
        }elseif ($status==-3){
            saveToLog("payment.log", "Status Chargeback. Order ID ".$order->order_id.".");
            return array(3, "Status Chargeback");
        }else{
            saveToLog("payment.log", "Status ".$status.". Order ID ".$order->order_id.".");
            return array(0, "Status ".$status);
        }
	}

	function showEndForm($pmconfigs, $order){
        
        $jshopConfig = &JSFactory::getConfig();
        $item_name = sprintf(_JSHOP_PAYMENT_NUMBER, "");
        
        $email = $pmconfigs['email_received'];
        $notify_url = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_moneybookers&no_lang=1";
        $return = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_moneybookers";
        $cancel_return = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_moneybookers";
        
        $_country = &JTable::getInstance('country', 'jshop');
        $_country->load($order->country);
        $country = $_country->country_code_2;
        
        $_lang = &JFactory::getLanguage();
        $language = substr($_lang->getTag(), 0, 2);
        ?>
        <html>
        <body>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />            
        </head>
        <form id="paymentform" action="https://www.moneybookers.com/app/payment.pl" name = "paymentform" method = "post">
        <input type="hidden" name="pay_to_email" value="<?php print $email;?>">
        <input type="hidden" name="status_url" value="<?php print $notify_url?>">
        <input type='hidden' name='return_url' value='<?php print $return?>'>
        <input type='hidden' name='cancel_url' value='<?php print $cancel_return?>'>
        <input type="hidden" name="language" value="<?php print $language;?>">
        <input type="hidden" name="amount" value="<?php print $order->order_total?>">
        <input type="hidden" name="currency" value="<?php print $order->currency_code_iso?>">
        <input type="hidden" name="detail1_description" value="<?php print $item_name;?>">
        <input type="hidden" name="detail1_text" value="<?php print $order->order_number;?>">
        <input type="hidden" name="merchant_fields" value="order_id">
        <input type="hidden" name="order_id" value="<?php print $order->order_id?>">
        </form>
        
        <?php print _JSHOP_REDIRECT_TO_PAYMENT_PAGE ?>
        <br>
        <script type="text/javascript">document.getElementById('paymentform').submit();</script>
        </body>
        </html>
        <?php
        die();
	}
    
    function getUrlParams($pmconfigs){                        
        $params = array(); 
        $params['order_id'] = JRequest::getInt("order_id");
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = 0;
    return $params;
    }
    
}

?>