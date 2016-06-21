<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker_item`;");
E_C("CREATE TABLE `imicms_broker_item` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `bid` int(10) unsigned NOT NULL COMMENT 'broker表id',
  `xmname` varchar(100) NOT NULL,
  `xmtype` tinyint(1) unsigned NOT NULL default '0' COMMENT '1现金2百分比',
  `xmnum` varchar(20) NOT NULL COMMENT '佣金额度',
  `xmimg` varchar(300) NOT NULL COMMENT '图片url',
  `tourl` varchar(300) character set utf8 collate utf8_estonian_ci NOT NULL COMMENT '跳转地址url',
  PRIMARY KEY  (`id`),
  KEY `bid` USING BTREE (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>