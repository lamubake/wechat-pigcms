<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbaoqiye_record`;");
E_C("CREATE TABLE `imicms_hongbaoqiye_record` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `wecha_id` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `money` float(255,2) NOT NULL,
  `pid` varchar(255) NOT NULL,
  `head_pic` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ly` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>