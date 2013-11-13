INSERT INTO `#__jshopping_payment_method` ( `name_en-GB`,`name_de-DE`, `payment_code`, `payment_class`, `payment_params`, `payment_type`, `show_descr_in_email`) 
VALUES( 'IDEAL', 'IDEAL', 'IDEAL01', 'pm_ideal', 'partnerid=\ntransaction_end_status=6\ntransaction_pending_status=1\ntransaction_failed_status=3', 2, 0);

CREATE TABLE IF NOT EXISTS `#__jshopping_payment_mollie`(
  `id` int(11) NOT NULL auto_increment,
  `tid` varchar(100) NOT NULL default '',
  `status` varchar(100) NOT NULL default '',
  `paid` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);