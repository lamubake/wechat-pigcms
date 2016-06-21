<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_micrstore`;");
E_C("CREATE TABLE `imicms_micrstore` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `paid` tinyint(4) NOT NULL,
  `third_id` varchar(50) default NULL,
  `orderid` varchar(50) NOT NULL,
  `price` float unsigned NOT NULL,
  `token` char(50) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `trade_no` char(50) default NULL,
  `paytype` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>