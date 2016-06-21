<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_news`;");
E_C("CREATE TABLE `imicms_news` (
  `id` int(11) NOT NULL auto_increment,
  `wxname` varchar(200) NOT NULL,
  `token` char(150) NOT NULL,
  `class1` int(11) NOT NULL,
  `class2` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `agentid` int(11) NOT NULL default '0',
  `agentind` int(11) NOT NULL default '0',
  `class3` int(11) NOT NULL,
  `name1` varchar(200) NOT NULL,
  `name2` varchar(200) NOT NULL,
  `name3` varchar(200) NOT NULL,
  `title` varchar(90) NOT NULL,
  `key` varchar(120) NOT NULL,
  `description` char(60) NOT NULL,
  `category` int(1) NOT NULL COMMENT '1:动态，2：公告，3：其他',
  `content` text NOT NULL,
  `imgs` char(120) NOT NULL,
  `showtime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>