<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_group`;");
E_C("CREATE TABLE `imicms_product_group` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>