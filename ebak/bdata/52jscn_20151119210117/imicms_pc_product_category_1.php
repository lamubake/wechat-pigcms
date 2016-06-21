<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_product_category`;");
E_C("CREATE TABLE `imicms_pc_product_category` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(50) NOT NULL,
  `cat_key` varchar(50) NOT NULL,
  `cat_sort` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `cat_key` (`cat_key`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>