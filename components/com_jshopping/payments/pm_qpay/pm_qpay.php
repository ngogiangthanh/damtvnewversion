<?php
defined('_JEXEC') or die('Restricted access');

class pm_qpay extends PaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	function showAdminFormParams($params){
	  $orders = &JModel::getInstance('orders', 'JshoppingModel'); //admin model
      $windowtype = array();
      $windowtype[] = JHTML::_('select.option', '1','Parent Window', 'id','name');
      $windowtype[] = JHTML::_('select.option', '2','Iframe', 'id','name');
      include(dirname(__FILE__)."/adminparamsform.php");	  
	}

	function checkTransaction($pmconfigs, $order, $act){
        $jshopConfig = &JSFactory::getConfig();
        $secret = $pmconfigs['secret'];
        $paymentState = isset($_POST["paymentState"]) ? $_POST["paymentState"] : "";
        $OK = 0;
                
        if ($order->order_total != $_POST['amount']){
            return array(0, 'Error amount. Order ID '.$order->order_id);
        }
        
         if (strcmp($paymentState,"CANCEL") == 0){        
            return array(3, 'Status Cancel. Order ID '.$order->order_id );
        }else if (strcmp($paymentState,"FAILURE") == 0){
            $message = $_POST["message"];
            return array(0, 'Status Error. Order ID '.$order->order_id.". Error: ".$message );            
        }else if (strcmp($paymentState,"SUCCESS") == 0){
            $responseFingerprintOrder = $_POST["responseFingerprintOrder"];
            $responseFingerprint = $_POST["responseFingerprint"];

            $str4responseFingerprint = "";
            $mandatoryFingerPrintFields = 0;
            $secretUsed = 0;

            $orderfield = explode(",",$responseFingerprintOrder);
            for ($i = 0; $i < count($orderfield); $i++){
                $key = $orderfield[$i];
                 
                if ((strcmp($key, "paymentState")) == 0 && (strlen($_POST[$orderfield[$i]]) > 0)){
                    $mandatoryFingerPrintFields++;
                }
                if ((strcmp($key, "orderNumber")) == 0 && (strlen($_POST[$orderfield[$i]]) > 0)){
                    $mandatoryFingerPrintFields++;
                }
                if ((strcmp($key, "paymentType")) == 0 && (strlen($_POST[$orderfield[$i]]) > 0)){
                    $mandatoryFingerPrintFields++;
                }

                if (strcmp($key, "secret") == 0){
                    $str4responseFingerprint .= $secret;
                    $secretUsed = 1;
                }else{
                    $str4responseFingerprint .= $_POST[$orderfield[$i]];
                }
            }

            // recalc the fingerprint
            $responseFingerprintCalc = md5($str4responseFingerprint);

             if ((strcmp($responseFingerprintCalc,$responseFingerprint) == 0) && ($mandatoryFingerPrintFields == 3) && ($secretUsed == 1)){                    
                return array(1, '');                
            }else{                
                return array(0, 'Invalid response. Order ID '.$order->order_id);
            }
        }else{
            return array(0, 'Invalid paymentState. Order ID '.$order->order_id.". paymentState=".$paymentState);
        }    
	}

	function showEndForm($pmconfigs, $order){
        $jshopConfig = &JSFactory::getConfig();	    
        $item_name = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
        $wmiframe = JRequest::getInt("wmiframe");

        $_country = &JTable::getInstance('country', 'jshop');
        $_country->load($order->country);
        $country = $_country->country_code_2;
        
        $lang = &JFactory::getLanguage();
        $language = substr($lang->getTag(), 0, 2);
        
        $requestFingerprintOrder = "";
        $requestFingerprintSeed  = "";
        $qpayURL = "https://secure.wirecard-cee.com/qpay/init.php";
        
        $secret = $pmconfigs['secret'];
        $requestFingerprintOrder .= "secret,";
        $requestFingerprintSeed  .= $secret;

        $customerId = $pmconfigs['customerId'];
        $requestFingerprintOrder .= "customerId,";
        $requestFingerprintSeed  .= $customerId;

        $amount = $order->order_total;
        $requestFingerprintOrder .= "amount,";
        $requestFingerprintSeed  .= $amount;

        $currency   = $order->currency_code_iso;
        $requestFingerprintOrder .= "currency,";
        $requestFingerprintSeed  .= $currency;

        $requestFingerprintOrder .= "language,";
        $requestFingerprintSeed  .= $language;

        $orderDescription = $item_name;
        $requestFingerprintOrder .= "orderDescription,";
        $requestFingerprintSeed  .= $orderDescription;

        $displayText      = $item_name;
        $requestFingerprintOrder .= "displayText,";
        $requestFingerprintSeed  .= $displayText;
        
        $notify_url = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_qpay&no_lang=1";
        $return = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_qpay&wmiframe=".$wmiframe;
        $cancel_return = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_qpay&wmiframe=".$wmiframe;
        $imageURL   = JURI::root()."/components/com_jshopping/payments/pm_qpay/logo.gif";

        $requestFingerprintOrder .= "successURL,";
        $requestFingerprintSeed  .= $return;

        $customField1 = $order->order_id;
        $customField2 = "";
        $requestFingerprintOrder .= "customField1,";
        $requestFingerprintSeed  .= $customField1;

        $requestFingerprintOrder .= "requestfingerprintorder";
        $requestFingerprintSeed  .= $requestFingerprintOrder;
        $requestFingerprint = md5($requestFingerprintSeed);
        ?>
        <html>
        <body>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />            
        </head>
        <form action="<?= $qpayURL ?>" method="post" name="form" id='paymentform'>
        <input type="hidden" name="customerId"          value="<?= $customerId ?>" />
        <!-- <input type="hidden" name="shopId"         value="<?= $shopId ?>" /> -->
        <input type="hidden" name="successURL"          value="<?= $return ?>" />
        <input type="hidden" name="failureURL"          value="<?= $cancel_return ?>" />
        <input type="hidden" name="cancelURL"           value="<?= $cancel_return ?>" />
        <input type="hidden" name="serviceURL"          value="<?= $notify_url ?>" />
        <input type="hidden" name="imageURL"            value="<?= $imageURL ?>" />
        <input type="hidden" name="amount"              value="<?= $amount ?>" />
        <input type="hidden" name="currency"            value="<?= $currency ?>" />
        <input type="hidden" name="language"            value="<?= $language ?>" />
        <input type="hidden" name="orderDescription"    value="<?= $orderDescription ?>" />
        <input type="hidden" name="displayText"         value="<?= $displayText ?>" />
        <input type="hidden" name="customField1"        value="<?= $customField1 ?>" />
        <input type="hidden" name="customField2"        value="<?= $customField2 ?>" />
        <input type="hidden" name="requestfingerprintorder" value="<?= $requestFingerprintOrder ?>" />
        <input type="hidden" name="requestfingerprint"      value="<?= $requestFingerprint ?>" />
        <input type="hidden" name="paymenttype"         value="CCARD" />
        <input type="hidden" name="windowName"          value="" />
        </form><br/>
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
        $params['order_id'] = JRequest::getInt("customField1");
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = 1;
    return $params;
    }
    
}
?>