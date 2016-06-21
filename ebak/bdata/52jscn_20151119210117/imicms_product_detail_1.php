<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_detail`;");
E_C("CREATE TABLE `imicms_product_detail` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `format` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `price` float NOT NULL,
  `vprice` float NOT NULL,
  `logo` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>