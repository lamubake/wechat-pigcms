<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation_set`;");
E_C("CREATE TABLE `imicms_donation_set` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `did` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `circle_name` varchar(10) NOT NULL COMMENT '圈子名称',
  `money` decimal(8,2) NOT NULL COMMENT '积分获得基数',
  `one` decimal(8,2) NOT NULL COMMENT '获得1个奖牌的条件',
  `two` decimal(8,2) NOT NULL COMMENT '获得2个奖牌的条件',
  `three` decimal(8,2) NOT NULL COMMENT '获得3个奖牌的条件',
  `four` decimal(8,2) NOT NULL COMMENT '获得4个奖牌的条件',
  `five` decimal(8,2) NOT NULL COMMENT '获得5个奖牌的条件',
  `dateline` int(10) unsigned NOT NULL,
  `agreement` text NOT NULL COMMENT '协议',
  `tip` varchar(15) NOT NULL COMMENT '感谢语',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>