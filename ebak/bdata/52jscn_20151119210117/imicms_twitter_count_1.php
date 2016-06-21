<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_twitter_count`;");
E_C("CREATE TABLE `imicms_twitter_count` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `twid` varchar(20) NOT NULL,
  `token` varchar(60) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `total` float NOT NULL COMMENT '总金额',
  `remove` float NOT NULL COMMENT '提出的金额',
  PRIMARY KEY  (`id`),
  KEY `twid` (`twid`),
  KEY `token` (`token`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分佣统计'");

require("../../inc/footer.php");
?>