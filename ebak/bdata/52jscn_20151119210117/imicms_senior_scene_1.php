<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_senior_scene`;");
E_C("CREATE TABLE `imicms_senior_scene` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(50) NOT NULL,
  `keyword` char(40) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `pic` varchar(120) NOT NULL,
  `url` varchar(200) NOT NULL,
  `token` char(25) NOT NULL,
  `add_time` char(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>