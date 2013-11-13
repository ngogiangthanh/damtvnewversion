<?php
/*****************************************************************************
 *                                                                           *
 *            Module tích hợp thanh toán Bảo Kim                             *
 * Phiên bản : 1.1                                                           *
 * Module được phát triển bởi IT Bảo Kim                                     *
 * Chức năng :                                                               *
 * - Tích hợp thanh toán qua baokim.vn cho các merchant site có đăng ký API. *
 * - Gửi thông tin thanh toán tới baokim.vn để xử lý việc thanh toán.        *
 * - Xác thực tính chính xác của thông tin được gửi về từ baokim.vn          *
 * @author hieunn                                                            *
 *****************************************************************************
 * Xin hãy đọc kĩ tài liệu tích hợp trên trang                               *
 * http://developer.baokim.vn/                                               *
 *                                                                           *
 *****************************************************************************/
defined('_JEXEC') or die('Restricted access');

class pm_baokim extends PaymentRoot
{

	/**
	 *
	 * @param array $params
	 * @param array $pmconfigs
	 */
	function showPaymentForm($params, $pmconfigs)
	{
		include(dirname(__FILE__) . "/paymentform.php");
	}

	/**
	 * function call in admin
	 *
	 * @param $params
	 */
	function showAdminFormParams($params)
	{
		$array_params = array(
			'testmode'=>'baokim.png',
			'email_received'=>'thanhthanh1516@gmail.com',
			'merchant_id'=>'11233',
			'security_code'=>'162628705cd91ee5',
			'bpn_file',
			'transaction_end_status'=>'complete',
			'transaction_pending_status'=>'pendding',
			'transaction_failed_status'=>'pendding',
		);

		foreach ($array_params as $key) {
			if (!isset($params[$key])) $params[$key] = '';
		}

		$this->updatePaymentType();
		//$orders = & JModel::getInstance('orders', 'JoomshoppingModel'); //admin model
		include(dirname(__FILE__) . "/adminparamsform.php");
	}

	function checkTransaction($pmconfigs, $order, $act)
	{
		if ($act == "notify") {
			include(dirname(__FILE__) . "/baokim_listener.php");
			$bpn = new BaoKimListener($pmconfigs['bpn_file']);
			$rescode = $bpn->check_bpn_request_is_valid($pmconfigs['testmode'], $order);

			//Return error. If check BPN request from BaoKim false
			if (!empty($rescode)) {
				return $rescode;
			}

			//Check order info and confirm with information from Baokim
			$rescode = $bpn->isValidOrderInfo($pmconfigs, $order);
			return $rescode;
		}
		if ($act == "return") {
			unset($_GET["act"]);
			unset($_GET["amp;js_paymentclass"]);
			unset($_GET["js_paymentclass"]);
			unset($_GET["Itemid"]);
			unset($_GET["option"]);
			unset($_GET["controller"]);
			unset($_GET["task"]);
			if ($this->verifyResponseUrl($_GET, $pmconfigs['security_code'])) {
				$comment_status = 'Giao dịch đang tạm giữ với đơn hàng ' . $order->order_id . '.Giao dịch đang tạm giữ';

				return array(2, $comment_status);
			} else {
				JError::raiseWarning("", "Invalid checksum from BaoKim");
			}
		}
	}

	/**
	 *
	 * @param $pmconfigs
	 * @param $order
	 */
	function showEndForm($pmconfigs, $order)
	{
		$jshopConfig = JSFactory::getConfig();
		$item_name = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
		$baokim_args = array();
		$liveurl = 'https://www.baokim.vn/payment/order/version11';
		$testurl = 'http://sandbox.baokim.vn/payment/order/version11';

		$uri = JURI::getInstance();
		$liveurlhost = $uri->toString(array("scheme", 'host', 'port'));
		$notify_url = $liveurlhost . SEFLink("index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_baokim&no_lang=1");
		$return = $liveurlhost . SEFLink("index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_baokim");
		$cancel_return = $liveurlhost . SEFLink("index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_baokim");

		if ($pmconfigs['testmode']) {
			$host = $testurl;
		} else {
			$host = $liveurl;
		}

		//Payment params config
		$baokim_args['business'] = $pmconfigs['email_received'];
		$baokim_args['merchant_id'] = $pmconfigs['merchant_id'];
		//$baokim_args['security_code'] = $pmconfigs['security_code'];

		//Action before payment
		$baokim_args['url_success'] = $return;
		$baokim_args['url_cancel'] = $cancel_return;
		$baokim_args['url_detail'] = '';

		//Order info
		$baokim_args['order_id'] = strval(time() . "-" . $order->order_id);
		$baokim_args['total_amount'] = strval($order->order_total);
		$baokim_args['shipping_fee'] = strval($order->order_shipping);
		$baokim_args['tax_fee'] = strval($order->order_tax);
		$baokim_args['order_description'] = strval($order->order_add_info);
		$baokim_args['currency'] = strval($order->currency_code_iso);

		//Payer_info
		if ($order->lang == "en-GB") {
			$baokim_args['payer_name'] = strval($order->f_name . " " . $order->l_name);
		} else {
			$baokim_args['payer_name'] = strval($order->l_name . " " . $order->f_name);
		}
		$baokim_args['payer_email'] = strval($order->email);
		$baokim_args['payer_phone_no'] = strval($order->phone);
		$baokim_args['shipping_address'] = strval($order->street);

		//Redirect
		$baokim_url = $this->createRequestUrl($baokim_args, $pmconfigs['security_code'], $host);
		$app = JFactory::getApplication();
		print _JSHOP_REDIRECT_TO_PAYMENT_PAGE;
		$app->redirect($baokim_url);
	}

	function getUrlParams($pmconfigs)
	{
		$act = JRequest::getVar("act");
		if ($act == "notify") {
			$params = array();
			$order_id = 0;
			if (isset($_POST['order_id'])) {
				$str_id = explode("-", $_POST['order_id']);
				$order_id = $str_id[1];
			}
			$params['order_id'] = $order_id;
			$params['hash'] = "";
			$params['checkHash'] = 0;
			$params['checkReturnParams'] = 0;
			return $params;
		}
		if ($act == "return") {
			$params = array();
			$str_id = explode("-", $_GET['order_id']);
			$order_id = $str_id[1];
			$params['order_id'] = $order_id;
			$params['hash'] = "";
			$params['checkHash'] = 0;
			$params['checkReturnParams'] = 1;
			return $params;
		}
	}

	/**
	 * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
	 * @param $baokim_args
	 * @param $secure_pass
	 * @param $baokim_server
	 * @internal param  /order_id đơn hàng
	 * @internal param  /business tài khoản người bán
	 * @internal param  /total_amount trị đơn hàng
	 * @internal param  /shipping_fee vận chuyển
	 * @internal param  /tax_fee ế
	 * @internal param  /order_description tả đơn hàng
	 * @internal param  /url_success trả về khi thanh toán thành công
	 * @internal param  /url_cancel trả về khi hủy thanh toán
	 * @internal param  /url_detail chi tiết đơn hàng
	 * @return url cần tạo
	 */
	public function createRequestUrl($baokim_args, $secure_pass, $baokim_server)
	{
		// Mảng các tham số chuyển tới baokim.vn
		$params = $baokim_args;
		ksort($params);

		$params['checksum'] = hash_hmac('SHA1', implode('', $params), $secure_pass);

		//Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
		$redirect_url = $baokim_server;
		if (strpos($redirect_url, '?') === false) {
			$redirect_url .= '?';
		} else if (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false) {
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';
		}

		// Tạo đoạn url chứa tham số
		$url_params = '';
		foreach ($params as $key => $value) {
			if ($url_params == '')
				$url_params .= $key . '=' . urlencode($value);
			else
				$url_params .= '&' . $key . '=' . urlencode($value);
		}
		return $redirect_url . $url_params;
	}

	/**
	 * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
	 * @param array          $url_params chứa tham số trả về trên url
	 * @param $secure_pass
	 * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
	 */
	public function verifyResponseUrl($url_params = array(), $secure_pass)
	{
		if (empty($url_params['checksum'])) {
			echo "invalid parameters: checksum is missing";
			return FALSE;
		}

		$checksum = $url_params['checksum'];
		unset($url_params['checksum']);

		ksort($url_params);
		if (strcasecmp($checksum, hash_hmac('SHA1', implode('', $url_params), $secure_pass)) === 0)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Update Payment Type in table __jshopping_payment_method
	 *
	 */
	private function updatePaymentType()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$obj = new jshopPaymentMethod($db);
		$obj->class = 'pm_baokim';
		$payment_id = $obj->getId();
		if (isset($payment_id)) {
			if ($this->getPaymentTypeByID($payment_id) != 2) {
				$fields = array(
					$db->quoteName('payment_type') . '=2'
				);
				$conditions = array(
					$db->quoteName('payment_id') . '=' . $obj->getId(),
				);
				$query->update($db->quoteName('#__jshopping_payment_method'))->set($fields)->where($conditions);
				$db->setQuery($query);
				$result = $db->query();
			}
		}
	}

	/**
	 *Get payment_type by payment_id
	 *
	 * @param $payment_id
	 * @return mixed
	 */
	private function getPaymentTypeByID($payment_id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT payment_type FROM `#__jshopping_payment_method` WHERE payment_id = '" . $db->escape($payment_id) . "'";
		$db->setQuery($query);
		return $db->loadResult();
	}

}

?>