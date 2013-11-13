<?php
/**
 * BAOKIM PAYMENT NOTIFICATION
 * Script này có chức năng như sau:
 * - Nhận thông báo giao dịch từ Bảo Kim (BPN)
 * - Gọi ngược thông tin nhận được trên BPN về Bảo Kim để xác minh thông tin
 * - Ghi log BPN nhận được
 * - Nếu xác minh thông tin trên BPN thành công, cập nhật (hoàn thành) đơn hàng
 *
 * Copy Right by BaoKim, Jsc 2013
 * @author hieunn
 */

/**
 * CẤU HÌNH HỆ THỐNG
 * @const DIR_LOG   Đường dẫn file log. Thư mục mặc định là baokim_listener
 * @const FILE_NAME Tên file log mặc định.
 *
 */
define('DIR_LOG', 'logs/');
define('FILE_NAME', 'bpn'); //Phần mở rộng của file là .log

//trạng thái giao dịch trên bảo kim: hoàn thành
define('BAOKIM_TRANSACTION_STATUS_COMPLETED', 4);

//trạng thái giao dịch trên bảo kim: đang tạm giữ
define('BAOKIM_TRANSACTION_STATUS_TEMP_HOLDING', 13);

class BaoKimListener
{
	private $file_log_name = FILE_NAME;
	private $test_bpn = 'http://sandbox.baokim.vn/bpn/verify';
	private $live_bpn = 'https://www.baokim.vn/bpn/verify';
	private $myFile;

	public function __construct($bpn_file)
	{
		$bpn_file_log = $this->getBPNFileLog($bpn_file);
		$this->myFile = DIR_LOG . $bpn_file_log . "-" . date("d-m") . ".log";
		$this->isFileORDirExist(DIR_LOG, $this->myFile);
	}

	/**
	 * Hàm thực hiện nhận và kiểm tra dữ liệu từ Bảo Kim
	 *
	 * @param $testmode
	 * @param $order
	 * @return bool
	 */
	function check_bpn_request_is_valid($testmode,$order)
	{
		$req = '';

		//Lấy url verify BPN
		if ($testmode){
			$baokim_url = $this->test_bpn;
		}else{
			$baokim_url = $this->live_bpn;
		}

		//Kiểm tra thư viện cURL
		if ($this->_isCurl()) {
			foreach ($_POST as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}
			$this->writeLog('Gui du lieu den '.$baokim_url.'. Thuc hien kiem tra tinh hop le cua BPN...', true);
		} else {
			$this->writeLog('Kiem tra cURL tren may chu');
			return array(0, 'Error cURL library. Please check in server');
		}
		$this->writeLog('BPN Data: ' . print_r($_POST, true));

		/**
		 * Gửi dữ liệu về Bảo Kim. Kiểm tra tính chính xác của dữ liệu
		 *
		 * @param $result Kết quả xác thực thông tin trả về.
		 * @paran $status Mã trạng thái trả về.
		 * @error $error  Lỗi được ghi vào file bpn.log
		 */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $baokim_url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);

		if ($result != '' && strstr($result, 'VERIFIED') && $status == 200) {
			$this->writeLog(' => VERIFIED');
			return array();
		} else {
			$this->writeLog(' => INVALID');
			return array(0, 'Invalid response. Order ID '.$order->order_id);
		}
		if ($error){
			$this->writeLog(' => | ERROR: ' . $error);
			return array(0, 'Error response : .'.$error.' Order ID '.$order->order_id);
		}

	}

	/**
	 * Thực hiện cập nhập trạng thái đon hàng sau khi hoàn thiện kiểm tra thông tin thanh toán
	 */
	private function successful_request($transaction_status,$order_id)
	{
		//TODO: trường hợp đối soát thông tin BPN thành công => hoàn thành đơn hàng (website merchant có thể edit lại phần này theo yêu cầu)

		switch ($transaction_status) {
			case 4: $order_status = 'complete';
					$comments = 'Bao Kim xac nhan don hang [' . $order_status . ']';
					$this->writeLog($comments);
					return array(1, '');
					break;
			case 13:
				$order_status = 'pending';
				$comments = 'Bao Kim xac nhan don hang [' . $order_status . ']';
				$comment_status = 'Nhận BPN : Thực hiện thanh toán thành công với đơn hàng ' . $order_id . '.Giao dịch đang tạm giữ. Cập nhật trạng thái cho đơn hàng thành công';
				$this->writeLog($comments);
				return array(2, $comment_status );
				break;
		}
	}

	/**
	 * Kiểm tra thông tin đơn hàng và đối soát với thông tin trên BPN gồm:
	 *          - Trạng thái giao dịch.
	 *          - Mã đơn hàng.
	 *          - Số tiền giao dịch.
	 *
	 * @param $pmconfigs
	 * @param $order
	 * @internal param  $transaction_status ạng thái giao dịch từ BaoKim
	 *                           4 : giao dịch hoàn thành
	 *                          13 : Giao dịch tạm giữ
	 *
	 * @internal param  $total_amount ố tiền thanh toán ở BaoKim
	 * @internal param  $order_id đơn hàng thanh toán từ BaoKim
	 * @return bool     True : Không xảy ra lỗi trong quá trình kiểm thông tin.
	 *                  False : Có lỗi trong quá trình kiểm tra thông tin. Tiến hành ghi log.
	 */
	function isValidOrderInfo($pmconfigs, $order)
	{
		$transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
		$transaction_status = isset($_POST['transaction_status']) ? $_POST['transaction_status'] : '';
		$total_amount = isset($_POST['total_amount']) ? $_POST['total_amount'] : '';
		$currency_rate = isset($_POST['usd_vnd_exchange_rate']) ? $_POST['usd_vnd_exchange_rate'] : '';

		if ($order->currency_code_iso == 'USD') {
			$total_amount = number_format(($total_amount / $currency_rate),2);
		}
		$confirm = '';

		//Danh sách các trạng thái giao dịch có thể coi là thành công (có thể giao hàng)
		$success_transaction_status = array(BAOKIM_TRANSACTION_STATUS_COMPLETED, BAOKIM_TRANSACTION_STATUS_TEMP_HOLDING);

		//Kiểm tra trạng thái giao dịch
		if (in_array($transaction_status, $success_transaction_status)) {

			//Kiểm tra số tiền đã thanh toán phải >= giá trị đơn hàng
			if ($total_amount < $order->order_total) {
				$confirm .= "\r\n" . ' So tien thanh toan: ' . $total_amount .' '. $order->currency_code_iso . ' nho hon gia tri don hang ung voi ma don hang: ' . $order->order_id;
				$this->writeLog($confirm);
				return array(0, $confirm .'. Order ID '.$order->order_id);
			}

			return $this->successful_request($transaction_status,$order->order_id);

		} else {
			$confirm .= "\r\n" . ' Trang thai giao dich:' . $transaction_status . ' chua thanh cong ung voi ma don hang : ' . $order->order_id;
			$this->writeLog($confirm);
			return array(0, $confirm .'. Order ID '.$order->order_id);
		}
	}

	/**
	 * Hàm thực hiện việc ghi log vào file log
	 *
	 * @param $mess        Nội dung thông báo log
	 * @param bool $begin  Bắt đầu của một file log
	 */
	private function writeLog($mess, $begin = false)
	{
		$file_log = $this->myFile;
		$fh = fopen($file_log, 'a') or die("can't open file");
		if ($begin) {
			fwrite($fh, "\r\n" . "---------------------------------------------------");
			fwrite($fh, "\r\n" . date("Y-m-d H:i:s") . " --- | --- " . $mess);
		} else {
			fwrite($fh, "\r\n" . $mess);
		}
	}

	/**
	 * Hàm lấy lấy và kiểm tra tên file log do người dùng cấu hình trong trang quản trị.
	 * Loại bỏ ký tự đặc biệt, nếu rỗng hoặc có dấu cách tên file mặc định là bpn
	 *
	 * @param $bpn_file
	 * @return mixed
	 */
	private function getBPNFileLog($bpn_file)
	{
		$bpn_file_log = preg_replace('/[^a-zA-Z0-9\_-]/', '', $bpn_file);
		if (!empty($bpn_file_log)) {
			$this->file_log_name = $bpn_file_log;
		}
		return $this->file_log_name;
	}

	/**
	 * Hàm kiểm tra sự tồn tại của file log. Thực hiện tạo mới nếu file không tồn tại
	 *
	 * @param $dir      Tên thư mục
	 * @param $fileName Tên file
	 */
	private function isFileORDirExist($dir, $fileName)
	{
		if ($dir != '') {
			if (!is_dir($dir)) {

				mkdir($dir);
			}
		}
		if ($fileName != '') {
			if (!file_exists($fileName)) {
				$ourFileHandle = fopen($fileName, 'w') or die("can't open file");
				fclose($ourFileHandle);
			}
		} else {
			die;
		}
	}

	/**
	 * Kiểm tra thư viện cURL chắc chắn được cài đặt trên máy chủ
	 *
	 * @return bool
	 */
	private function _isCurl()
	{
		return function_exists('curl_version');
	}

}