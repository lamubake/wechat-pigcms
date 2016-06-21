<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_invite`;");
E_C("CREATE TABLE `imicms_invite` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` varchar(360) NOT NULL,
  `replypic` varchar(200) NOT NULL,
  `cover` varchar(150) NOT NULL,
  `meetpic` varchar(150) NOT NULL,
  `photo` varchar(20) NOT NULL,
  `linkman` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `site` varchar(100) NOT NULL,
  `twopic` varchar(150) NOT NULL,
  `theme` varchar(50) NOT NULL,
  `themeurl` varchar(150) NOT NULL,
  `warn` varchar(100) NOT NULL,
  `inback` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>