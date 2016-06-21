<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_invite_user`;");
E_C("CREATE TABLE `imicms_invite_user` (
  `id` int(10) NOT NULL auto_increment,
  `token` char(60) NOT NULL,
  `yid` int(1) NOT NULL,
  `headpic` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `synopsis` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>