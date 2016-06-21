<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_coupon_pay_record`;");
E_C("CREATE TABLE `imicms_coupon_pay_record` (
  `imicms_id` int(10) unsigned NOT NULL auto_increment,
  `orderid` varchar(60) NOT NULL COMMENT '订单ID',
  `coupon_id` int(10) unsigned NOT NULL COMMENT '优惠券ID',
  `wechat_id` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `from` varchar(50) NOT NULL,
  `least_cost` decimal(10,2) NOT NULL COMMENT '订单至少要满足的金额',
  `reduce_cost` decimal(10,2) NOT NULL COMMENT '订单优惠的金额',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`imicms_id`),
  KEY `orderid` (`orderid`,`coupon_id`),
  KEY `wechat_id` (`wechat_id`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券用于支付的记录'");

require("../../inc/footer.php");
?>