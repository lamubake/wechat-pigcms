<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_popularity_prize`;");
E_C("CREATE TABLE `imicms_popularity_prize` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` char(45) NOT NULL,
  `img` char(200) NOT NULL,
  `num` int(11) NOT NULL,
  `use_num` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `gt` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>