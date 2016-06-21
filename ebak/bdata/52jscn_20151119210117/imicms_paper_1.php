<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_paper`;");
E_C("CREATE TABLE `imicms_paper` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(40) NOT NULL,
  `keyword` varchar(100) default NULL,
  `title` varchar(100) default NULL,
  `pic` varchar(100) default NULL,
  `message` text NOT NULL,
  `time` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>