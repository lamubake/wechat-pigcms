<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_punish_item`;");
E_C("CREATE TABLE `imicms_punish_item` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(25) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>