<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_unitary_order`;");
E_C("CREATE TABLE `imicms_unitary_order` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) default NULL,
  `wecha_id` varchar(100) default NULL,
  `price` int(11) default NULL COMMENT '总价',
  `addtime` int(11) default NULL COMMENT '添加时间',
  `paytype` varchar(50) default NULL COMMENT '来自于何种支付(英文格式)',
  `paid` tinyint(1) NOT NULL default '0' COMMENT '是否支付，1代表已支付',
  `third_id` varchar(100) default NULL COMMENT '第三方支付平台的订单ID，用于对帐。',
  `orderid` varchar(255) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>