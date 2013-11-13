<?php
defined('_JEXEC') or die('Restricted access');
include_once(dirname(__FILE__)."/ideal.class.php");
class pm_ideal extends PaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        JSFactory::loadExtLanguageFile("pm_ideal");
        $iDEAL = new iDEAL_Payment($pmconfigs['partnerid']);
        if ($pmconfigs['testmode']){
            $iDEAL->setTestMode();
        }        
        $bank_array = $iDEAL->getBanks();
        if ($bank_array == false){
            JError::raiseWarning("", $iDEAL->getErrorMessage());
        }
        include(dirname(__FILE__)."/paymentform.php");
    }

    //function call in admin
    function showAdminFormParams($params){
        JSFactory::loadExtLanguageFile("pm_ideal");
        $array_params = array('testmode', 'partnerid', 'transaction_end_status', 'transaction_pending_status', 'transaction_failed_status');
        foreach ($array_params as $key){
            if (!isset($params[$key])) $params[$key] = '';
        }
        $orders = &JModel::getInstance('orders', 'JshoppingModel'); //admin model
        include(dirname(__FILE__)."/adminparamsform.php");      
    }
    
    function saveTrSatus($transaction_id, $paidstatus, $bankstatus){
        $db = &JFactory::getDBO();
        $query = "delete from #__jshopping_payment_mollie where `tid`='".$db->getEscaped($transaction_id)."'";
        $db->setQuery($query);
        $db->query();
        
        $query = "insert into #__jshopping_payment_mollie set 
                  `tid`='".$db->getEscaped($transaction_id)."',
                  `paid`='".intval($paidstatus)."',
                  `status`='".$db->getEscaped($bankstatus)."'                  
                  ";
        $db->setQuery($query);
        $db->query();
    }
    
    function getTrSatus($transaction_id){
        $db = &JFactory::getDBO();
        $query = "select * from #__jshopping_payment_mollie where `tid`='".$db->getEscaped($transaction_id)."'";
        $db->setQuery($query);
    return $db->loadObject();
    }

    function checkTransaction($pmconfigs, $order, $act){
        $jshopConfig = &JSFactory::getConfig();
        
        
        $iDEAL = new iDEAL_Payment($pmconfigs['partnerid']);    
        if ($pmconfigs['testmode']){
            $iDEAL->setTestMode();
        }        
        $iDEAL->checkPayment($_GET['transaction_id']);
        
        $paidstatus = $iDEAL->getPaidStatus();
        $bankstatus = $iDEAL->getBankStatus();
        
        if ($bankstatus!="CheckedBefore"){
            pm_ideal::saveTrSatus($_GET['transaction_id'], $paidstatus, $bankstatus);
        }else{
            $data = pm_ideal::getTrSatus($_GET['transaction_id']);
            $paidstatus = $data->paid;
            $bankstatus = $data->status;
        }

        if ($paidstatus){
            saveToLog("paymentdata.log", "OK. Order ID ".$order->order_id.". ".$iDEAL->getAmount().", ".$iDEAL->getBankStatus());
            return array(1, '');
        }else{
            if ($bankstatus=="Cancelled"){                
                return array(3, "Status cancelled. Order ID ".$order->order_id);
            }elseif ($bankstatus=="Failure"){                
                return array(3, "Status Failure. Order ID ".$order->order_id);
            }else{
                saveToLog("paymentdata.log", "Order ID ".$order->order_id.". ".$iDEAL->getAmount().", ".$bankstatus.", ".$iDEAL->getErrorMessage());
                return array(0, "");
            }
        }
    }

    function showEndForm($pmconfigs, $order){
        $mainframe =& JFactory::getApplication();
        $jshopConfig = &JSFactory::getConfig();
        $item_name = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
                
        $notify_url = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_ideal&no_lang=1";
        $return = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_ideal";
        $cancel_return = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_ideal";
        
        $params_data = $order->getPaymentParamsData();
        $bank_id = $params_data['bank_id'];
        $amount = intval($order->order_total * 100);
        
        $iDEAL = new iDEAL_Payment($pmconfigs['partnerid']);
        if ($pmconfigs['testmode']){
            $iDEAL->setTestMode();
        }
        
        if ($iDEAL->createPayment($bank_id, $amount, $item_name, $return, $notify_url)){
            $order1 = &JTable::getInstance('order', 'jshop');
            $order1->load($order->order_id);
            $order1->transaction = $iDEAL->getTransactionId();
            $order1->store();
            header("Location: " . $iDEAL->getBankURL());
            exit;
        } else {
            saveToLog("payment.log", "Error: Order ID ".$order->order_id.". CODE: ".$iDEAL->getErrorCode().". MSG: ".$iDEAL->getErrorMessage());
            JError::raiseWarning("", $iDEAL->getErrorMessage());            
            $mainframe->redirect(SEFLink('index.php?option=com_jshopping&controller=checkout&task=step5', 0, 1, $jshopConfig->use_ssl));
            exit;
        }
    }
    
    function getUrlParams($pmconfigs){
        $params = array(); 
        $transaction_id = JRequest::getVar("transaction_id");
        $db = &JFactory::getDBO();
        $query = "select order_id from #__jshopping_orders where `transaction`='".$db->getEscaped($transaction_id)."'";
        $db->setQuery($query);
        $params['order_id'] = $db->loadResult();
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = 1;
    return $params;
    }
    
}

?>