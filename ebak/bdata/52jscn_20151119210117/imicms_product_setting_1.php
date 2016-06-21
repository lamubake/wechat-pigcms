<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_setting`;");
E_C("CREATE TABLE `imicms_product_setting` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `score` float NOT NULL,
  `paymode` tinyint(1) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `tpid` int(10) unsigned NOT NULL,
  `footerid` int(10) unsigned NOT NULL,
  `headerbackgroud` text NOT NULL,
  `headerid` int(10) unsigned NOT NULL,
  `isgroup` tinyint(1) unsigned NOT NULL,
  `email` varchar(64) NOT NULL default '',
  `email_status` char(1) NOT NULL default '0',
  `shop_send_sms` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>