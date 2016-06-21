<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation_medal`;");
E_C("CREATE TABLE `imicms_donation_medal` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(80) NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `num` smallint(3) unsigned NOT NULL COMMENT '奖牌数',
  `money` decimal(8,2) NOT NULL COMMENT '捐款的金额',
  `pic` varchar(200) NOT NULL COMMENT '奖牌图片',
  `dateline` int(10) unsigned NOT NULL,
  `note` varchar(15) NOT NULL COMMENT '奖牌说明',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `did` (`did`),
  KEY `money` (`money`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>