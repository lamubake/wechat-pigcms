<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_auction_toprice`;");
E_C("CREATE TABLE `imicms_auction_toprice` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `price` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL,
  `orderid` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `pid` (`pid`),
  KEY `wecha_id` (`wecha_id`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>