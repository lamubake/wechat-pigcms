<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker_optionlog`;");
E_C("CREATE TABLE `imicms_broker_optionlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `bid` int(10) unsigned NOT NULL,
  `tjuid` int(11) NOT NULL COMMENT '推荐人',
  `logstr` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL default '0.00',
  PRIMARY KEY  (`id`),
  KEY `bid` USING BTREE (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>