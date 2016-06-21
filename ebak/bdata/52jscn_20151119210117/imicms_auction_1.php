<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_auction`;");
E_C("CREATE TABLE `imicms_auction` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wxpic` varchar(200) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxinfo` text,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `info` text NOT NULL,
  `logo` varchar(200) NOT NULL,
  `startprice` int(11) NOT NULL default '0',
  `addprice` int(11) NOT NULL default '0',
  `fixedprice` int(11) NOT NULL default '0',
  `referenceprice` int(11) NOT NULL default '0',
  `is_attention` int(11) NOT NULL default '0',
  `is_reg` int(11) NOT NULL default '0',
  `is_open` int(11) NOT NULL default '0',
  `is_del` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL,
  `state` int(11) NOT NULL default '0',
  `pv` int(11) NOT NULL default '0',
  `like_num` int(11) NOT NULL default '0',
  `share_num` int(11) NOT NULL default '0',
  `postage` int(11) NOT NULL default '0',
  `settime` int(11) NOT NULL,
  `nobuytime` int(11) NOT NULL default '48',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `keyword` (`keyword`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>