<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_relation`;");
E_C("CREATE TABLE `imicms_product_relation` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `gid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>