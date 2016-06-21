<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_diningtable`;");
E_C("CREATE TABLE `imicms_product_diningtable` (
  `id` mediumint(4) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL default '',
  `name` varchar(60) NOT NULL default '',
  `intro` varchar(500) NOT NULL default '',
  `taxis` mediumint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>