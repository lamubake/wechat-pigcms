<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_school_cat`;");
E_C("CREATE TABLE `imicms_school_cat` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(25) NOT NULL,
  `name` char(50) NOT NULL,
  `icon` char(150) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `is_show` enum('1','0') NOT NULL,
  `url` varchar(300) NOT NULL,
  `system` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>