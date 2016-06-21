<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_invites`;");
E_C("CREATE TABLE `imicms_invites` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `picurl` varchar(120) NOT NULL,
  `type` int(3) NOT NULL,
  `brief` varchar(200) NOT NULL,
  `content` varchar(300) NOT NULL,
  `statdate` varchar(30) NOT NULL,
  `address` varchar(120) NOT NULL,
  `lng` double NOT NULL,
  `lat` double NOT NULL,
  `qr_code` varchar(120) NOT NULL,
  `copyrite` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>