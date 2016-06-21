<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_order`;");
E_C("CREATE TABLE `imicms_cashier_order` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` char(32) NOT NULL,
  `mid` int(11) NOT NULL,
  `pay_way` char(50) NOT NULL,
  `pay_type` char(50) NOT NULL,
  `goods_type` char(50) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_name` char(200) NOT NULL,
  `goods_describe` varchar(500) NOT NULL,
  `goods_price` decimal(12,2) NOT NULL default '0.00',
  `add_time` int(11) NOT NULL,
  `paytime` int(10) unsigned NOT NULL default '0' COMMENT '支付时间',
  `state` tinyint(1) NOT NULL default '0',
  `ispay` tinyint(1) unsigned NOT NULL default '0' COMMENT '1已支付',
  `truename` varchar(250) NOT NULL,
  `openid` varchar(250) NOT NULL,
  `transaction_id` varchar(250) NOT NULL COMMENT '第三方支付订单号',
  `refund` tinyint(1) unsigned NOT NULL default '0' COMMENT '1退款中2已退款3失败',
  `refundtext` text NOT NULL COMMENT '退款结果数据',
  `comefrom` tinyint(1) unsigned NOT NULL default '0' COMMENT '0本地1微信营销 2微店 3 o2o系统',
  PRIMARY KEY  (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>