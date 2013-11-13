<?php

define('_JSHOP_BAOKIM_TESTMODE_DESCRIPTION','Using BaoKim sandbox to action order');

define('_JSHOP_BAOKIM_EMAIL','Business Email :');
define('_JSHOP_BAOKIM_EMAIL_DESCRIPTION','Your business email address for BaoKim payments. Also used as receiver_email.');

define('_JSHOP_BAOKIM_MERCHANT_ID','Merchant ID :');
define('_JSHOP_BAOKIM_MERCHANT_ID_DESCRIPTION','Your website code for BaoKim payment. You can get it on BaoKim site when register integrate payment gateway');

define('_JSHOP_BAOKIM_SECURITY_CODE','Security Code :');
define('_JSHOP_BAOKIM_SECURITY_CODE_DESCRIPTION','Your security code for BaoKim payment. You can get it on BaoKim site when register integrate payment gateway');

define('_JSHOP_BAOKIM_BPN_DESCRIPTION','Configuration for integrating BPN site (BaoKim Payment Notification)');
define('_JSHOP_BAOKIM_BPN_FILE','BPN File :');
define('_JSHOP_BAOKIM_BPN_FILE_DESCRIPTION','Log BaoKim events, such as BPN requests, inside /logs/bpn-'.date("d-m").".log");

define('_JSHOP_BAOKIM_TRANSACTION_END_DESCRIPTION','Select the order status to which the actual order is set, if the BAOKIM BPN was successful.');
define('_JSHOP_BAOKIM_TRANSACTION_PENDING_DESCRIPTION','The order Status to which Orders are set, which have no completed Payment Transaction.');
define('_JSHOP_BAOKIM_TRANSACTION_FAILED_DESCRIPTION','Select an order status for failed BAOKIM transactions.');
?>