<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yeepay_tmp`;");
E_C("CREATE TABLE `imicms_yeepay_tmp` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` varchar(50) NOT NULL,
  `token` varchar(60) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `from` varchar(30) NOT NULL,
  `time` int(11) NOT NULL,
  `platform` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>