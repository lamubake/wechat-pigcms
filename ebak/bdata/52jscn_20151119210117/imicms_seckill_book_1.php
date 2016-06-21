<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_seckill_book`;");
E_C("CREATE TABLE `imicms_seckill_book` (
  `book_id` int(11) NOT NULL auto_increment,
  `orderid` varchar(32) NOT NULL COMMENT '订单号',
  `price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `wecha_id` varchar(100) NOT NULL COMMENT '公众号的标识',
  `token` varchar(50) NOT NULL COMMENT '公众号的标识',
  `paytype` varchar(50) NOT NULL default '' COMMENT '来自于何种支付(英文格式)',
  `paid` tinyint(4) NOT NULL default '0' COMMENT '是否支付，1代表已支付',
  `third_id` varchar(100) NOT NULL default '' COMMENT '第三方支付平台的订单ID，用于对帐',
  `book_aid` int(11) NOT NULL COMMENT '活动id',
  `book_sid` int(11) NOT NULL COMMENT '商品id',
  `book_time` int(11) NOT NULL COMMENT '订单时间',
  `book_uid` int(11) NOT NULL COMMENT '订单用户',
  `deli_addr` varchar(100) default '' COMMENT '收货地址',
  `true_name` varchar(30) default '' COMMENT '收件人姓名',
  `tel` varchar(20) default '' COMMENT '固话',
  `cel` varchar(20) default '' COMMENT '手机',
  PRIMARY KEY  (`book_id`),
  KEY `orderid` USING BTREE (`orderid`),
  KEY `book_aid` USING BTREE (`book_aid`),
  KEY `book_sid` USING BTREE (`book_sid`),
  KEY `paid` USING BTREE (`paid`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>