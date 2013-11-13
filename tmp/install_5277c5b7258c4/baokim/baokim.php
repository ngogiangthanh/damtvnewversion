<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');

class plgVMPaymentBaokim extends vmPSPlugin {

    // instance of class
    public static $_this = false;

    function __construct(& $subject, $config) {
    	
		parent::__construct($subject, $config);
	
		$this->_loggable = true;
		$this->tableFields = array_keys($this->getTableSQLFields());
	
		$varsToPush = array(
			'baokim_merchant_email' => array('', 'char'),
			'baokim_merchant_id' => array('', 'char'),
			'baokim_secure_pass' => array('', 'char'),
		    'payment_currency' => array(0, 'int'),
		    'debug' => array(0, 'int'),
		    'status_pending' => array('', 'char'),
		    'status_success' => array('', 'char'),
		    'status_canceled' => array('', 'char'),
			'min_amount' => array(0, 'int'),
			'max_amount' => array(0, 'int')
		);
	
		$this->setConfigParameterable($this->_configTableFieldName, $varsToPush);
	}

    protected function getVmPluginCreateTableSQL() {
		return $this->createTableSQL('Payment BaoKim Table');
    }

    function getTableSQLFields() {
		$SQLfields = array(
		    'id' => ' tinyint(1) unsigned NOT NULL AUTO_INCREMENT ',
		    'virtuemart_order_id' => ' int(11) UNSIGNED DEFAULT NULL',
		    'order_number' => ' char(32) DEFAULT NULL',
		    'virtuemart_paymentmethod_id' => ' mediumint(1) UNSIGNED DEFAULT NULL',
		    'payment_name' => ' char(255) NOT NULL DEFAULT \'\' ',
		    'payment_order_total' => 'decimal(15,5) NOT NULL DEFAULT \'0.00000\' ',
		    'payment_currency' => 'char(3) '
		);
		return $SQLfields;
    }

    function plgVmConfirmedOrder($cart, $order) {
		

    	
		if (!($method = $this->getVmPluginMethod($order['details']['BT']->virtuemart_paymentmethod_id))) {
		    return null; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement($method->payment_element)) {
		    return false;
		}
		$session = JFactory::getSession();
		$return_context = $session->getId();
		$this->_debug = $method->debug;
		$this->logInfo('plgVmConfirmedOrder order number: ' . $order['details']['BT']->order_number, 'message');
	
		if (!class_exists('VirtueMartModelOrders'))
		    require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );
		if (!class_exists('VirtueMartModelCurrency'))
		    require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'currency.php');
	
		//$usr = & JFactory::getUser();
		$new_status = '';
	
		$usrBT = $order['details']['BT'];
		$address = ((isset($order['details']['ST'])) ? $order['details']['ST'] : $order['details']['BT']);
	
		$vendorModel = new VirtueMartModelVendor();
		$vendorModel->setId(1);
		$vendor = $vendorModel->getVendor();
		$this->getPaymentCurrency($method);
		$q = 'SELECT `currency_code_3` FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id`="' . $method->payment_currency . '" ';
		$db = &JFactory::getDBO();
		$db->setQuery($q);
		$currency_code_3 = $db->loadResult();
	
		$paymentCurrency = CurrencyDisplay::getInstance($method->payment_currency);
		$totalInPaymentCurrency = round($paymentCurrency->convertCurrencyTo($method->payment_currency, $order['details']['BT']->order_total,false), 2);
		$cd = CurrencyDisplay::getInstance($cart->pricesCurrency);
	
		$merchant_email = $this->_getMerchantEmail($method);

		
		if (empty($merchant_email)) {
		    vmInfo(JText::_('Tài khản Bảo Kim chưa được cài đặt'));
		    return false;
		}

		$post_variables = Array(
			'business' => $merchant_email,
			'merchant_id' => $method->baokim_merchant_id,
			'order_description' => $order['details']['BT']->customer_note,
			'order_id' => $cart->virtuemart_order_id,
			'total_amount' => ceil($totalInPaymentCurrency),
		);
		
		$str_combined = $method->baokim_secure_pass.implode('', $post_variables);
		$post_variables['checksum'] = strtoupper(md5($str_combined));
		
		
		$dbValues['order_number'] = $order['details']['BT']->order_number;
		$dbValues['payment_name'] = $this->renderPluginName($method, $order);
		$dbValues['virtuemart_paymentmethod_id'] = $cart->virtuemart_paymentmethod_id;
		$dbValues['baokim_custom'] = $return_context;
		$dbValues['cost_per_transaction'] = $method->cost_per_transaction;
		$dbValues['cost_percent_total'] = $method->cost_percent_total;
		$dbValues['payment_currency'] = $method->payment_currency;
		$dbValues['payment_order_total'] = $totalInPaymentCurrency;
		$dbValues['tax_id'] = $method->tax_id;
		$this->storePSPluginInternalData($dbValues);

		
		
		$url = "https://www.baokim.vn/payment/customize_payment/order?";
		$html = '<form action="' . $url . '" method="post" name="vm_baokim_form" >';
		foreach ($post_variables as $name => $value) {
		    $html.= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
		}
		$html.= '</form>';
	
		
		$html.= ' <script type="text/javascript">';
		$html.= ' document.vm_baokim_form.submit();';
		$html.= ' </script>';

		// 	2 = don't delete the cart, don't send email and don't redirect
		return $this->processConfirmedOrderPaymentResponse(2, $cart, $order, $html, $new_status);
		
    }

    function plgVmgetPaymentCurrency($virtuemart_paymentmethod_id, &$paymentCurrencyId) {
		if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
		    return null; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement($method->payment_element)) {
		    return false;
		}
		 $this->getPaymentCurrency($method);
		$paymentCurrencyId = $method->payment_currency;
    }

    function plgVmOnPaymentResponseReceived(  &$html) {

	// the payment itself should send the parameter needed.
		$virtuemart_paymentmethod_id = JRequest::getInt('pm', 0);
	
		$vendorId = 0;
		if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
		    return null; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement($method->payment_element)) {
		    return false;
		}
	
		$payment_data = JRequest::get('post');
		vmdebug('plgVmOnPaymentResponseReceived', $payment_data);
		$order_number = $payment_data['invoice'];
		$return_context = $payment_data['custom'];
		if (!class_exists('VirtueMartModelOrders'))
		    require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );
	
		$virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($order_number);
		$payment_name = $this->renderPluginName($method);
		$html = $this->_getPaymentResponseHtml($payment_data, $payment_name);
	
			    if ($virtuemart_order_id) {
				if (!class_exists('VirtueMartCart'))
				    require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');
				// get the correct cart / session
				$cart = VirtueMartCart::getCart();
	
				// send the email ONLY if payment has been accepted
				if (!class_exists('VirtueMartModelOrders'))
				    require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );
				$order = new VirtueMartModelOrders();
				$orderitems = $order->getOrder($virtuemart_order_id);
				//vmdebug('PaymentResponseReceived CART', $orderitems);
				$cart->sentOrderConfirmedEmail($orderitems);
				//We delete the old stuff
	
				$cart->emptyCart();
			    }
	
		return true;
    }

    function plgVmOnUserPaymentCancel() {

	if (!class_exists('VirtueMartModelOrders'))
	    require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );

	$order_number = JRequest::getVar('on');
	if (!$order_number)
	    return false;
	$db = JFactory::getDBO();
	$query = 'SELECT ' . $this->_tablename . '.`virtuemart_order_id` FROM ' . $this->_tablename. " WHERE  `order_number`= '" . $order_number . "'";

	$db->setQuery($query);
	$virtuemart_order_id = $db->loadResult();

	if (!$virtuemart_order_id) {
	    return null;
	}
	$this->handlePaymentUserCancel($virtuemart_order_id);

	//JRequest::setVar('paymentResponse', $returnValue);
	return true;
    }

  
    function plgVmOnShowOrderBEPayment($virtuemart_order_id, $payment_method_id) {

	if (!$this->selectedThisByMethodId($payment_method_id)) {
	    return null; // Another method was selected, do nothing
	}

	$db = JFactory::getDBO();
	$q = 'SELECT * FROM `' . $this->_tablename . '` '
		. 'WHERE `virtuemart_order_id` = ' . $virtuemart_order_id;
	$db->setQuery($q);
	if (!($paymentTable = $db->loadObject())) {
	   // JError::raiseWarning(500, $db->getErrorMsg());
	    return '';
	}
	$this->getPaymentCurrency($paymentTable);
	$q = 'SELECT `currency_code_3` FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id`="' . $paymentTable->payment_currency . '" ';
	$db = &JFactory::getDBO();
	$db->setQuery($q);
	$currency_code_3 = $db->loadResult();
	$html = '<table class="adminlist">' . "\n";
	$html .=$this->getHtmlHeaderBE();
	$html .= $this->getHtmlRowBE('BAOKIM_PAYMENT_NAME', $paymentTable->payment_name);
	//$html .= $this->getHtmlRowBE('PAYPAL_PAYMENT_TOTAL_CURRENCY', $paymentTable->payment_order_total.' '.$currency_code_3);
	$code = "baokim_response_";
	foreach ($paymentTable as $key => $value) {
	    if (substr($key, 0, strlen($code)) == $code) {
		$html .= $this->getHtmlRowBE($key, $value);
	    }
	}
	$html .= '</table>' . "\n";
	return $html;
    }

    function getCosts(VirtueMartCart $cart, $method, $cart_prices) {
		if (preg_match('/%$/', $method->cost_percent_total)) {
		    $cost_percent_total = substr($method->cost_percent_total, 0, -1);
		} else {
		    $cost_percent_total = $method->cost_percent_total;
		}

		return ($method->cost_per_transaction + ($cart_prices['salesPrice'] * $cost_percent_total * 0.01));
		return 500000;
    }

   
    protected function checkConditions($cart, $method, $cart_prices) {


	$address = (($cart->ST == 0) ? $cart->BT : $cart->ST);

	$amount = $cart_prices['salesPrice'];
	$amount_cond = ($amount >= $method->min_amount AND $amount <= $method->max_amount
		OR
		($method->min_amount <= $amount AND ($method->max_amount == 0) ));

	$countries = array();
	if (!empty($method->countries)) {
	    if (!is_array($method->countries)) {
		$countries[0] = $method->countries;
	    } else {
		$countries = $method->countries;
	    }
	}
	// probably did not gave his BT:ST address
	if (!is_array($address)) {
	    $address = array();
	    $address['virtuemart_country_id'] = 0;
	}

	if (!isset($address['virtuemart_country_id']))
	    $address['virtuemart_country_id'] = 0;
	if (in_array($address['virtuemart_country_id'], $countries) || count($countries) == 0) {
	    if ($amount_cond) {
		return true;
	    }
	}

	return false;
    }

   
    function plgVmOnStoreInstallPaymentPluginTable($jplugin_id) {

	return $this->onStoreInstallPluginTable($jplugin_id);
    }

    public function plgVmOnSelectCheckPayment(VirtueMartCart $cart) {
	return $this->OnSelectCheck($cart);
    }

    
    public function plgVmDisplayListFEPayment(VirtueMartCart $cart, $selected = 0, &$htmlIn) {
	return $this->displayListFE($cart, $selected, $htmlIn);
    }

    public function plgVmonSelectedCalculatePricePayment(VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name) {
	return $this->onSelectedCalculatePrice($cart, $cart_prices, $cart_prices_name);
    }

   
    function plgVmOnCheckAutomaticSelectedPayment(VirtueMartCart $cart, array $cart_prices = array()) {
	return $this->onCheckAutomaticSelected($cart, $cart_prices);
    }

   
    public function plgVmOnShowOrderFEPayment($virtuemart_order_id, $virtuemart_paymentmethod_id, &$payment_name) {
		$this->onShowOrderFE($virtuemart_order_id, $virtuemart_paymentmethod_id, $payment_name);
    }

   
    function plgVmonShowOrderPrintPayment($order_number, $method_id) {
		return $this->onShowOrderPrint($order_number, $method_id);
    }

    function plgVmDeclarePluginParamsPayment($name, $id, &$data) {
		return $this->declarePluginParams('payment', $name, $id, $data);
    }

    function plgVmSetOnTablePluginParamsPayment($name, $id, &$table) {
		return $this->setOnTablePluginParams($name, $id, $table);
    }
	
	function _getMerchantEmail($method) {
		return $method->baokim_merchant_email;
    }

}

// No closing tag
