<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_weizhaohuan`;");
E_C("CREATE TABLE `imicms_weizhaohuan` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(30) NOT NULL,
  `keyword` char(35) NOT NULL,
  `title` char(45) NOT NULL,
  `pic` char(200) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `addr` varchar(150) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  `info` text NOT NULL,
  `intro` text NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  `add_time` char(15) NOT NULL,
  `show_num` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `min` varchar(11) NOT NULL default '',
  `max` varchar(11) NOT NULL default '',
  `bgpic` varchar(200) NOT NULL default '',
  `bgcolor` varchar(20) NOT NULL default '#ca1a48',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>