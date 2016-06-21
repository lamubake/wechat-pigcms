<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker_commission`;");
E_C("CREATE TABLE `imicms_broker_commission` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `bid` int(11) unsigned NOT NULL,
  `tjuid` int(11) unsigned NOT NULL,
  `tjname` varchar(100) NOT NULL,
  `clientid` int(11) unsigned NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_tel` varchar(20) NOT NULL,
  `client_status` tinyint(1) unsigned NOT NULL COMMENT '客户目前状态',
  `proid` int(11) unsigned NOT NULL,
  `proname` varchar(255) NOT NULL,
  `verifyname` varchar(100) NOT NULL COMMENT '置业顾问名字',
  `verifytel` varchar(20) NOT NULL COMMENT '置业顾问电话',
  `money` decimal(10,2) unsigned NOT NULL COMMENT '金额',
  `status` tinyint(1) unsigned NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `bid` USING BTREE (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>