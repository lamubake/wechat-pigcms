<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_msg`;");
E_C("CREATE TABLE `imicms_msg` (
  `id` int(5) NOT NULL auto_increment,
  `username` varchar(60) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `qq` int(11) NOT NULL,
  `domain` varchar(60) NOT NULL,
  `time` int(11) NOT NULL,
  `price` int(5) NOT NULL,
  `info` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>