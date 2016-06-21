<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sign_conf`;");
E_C("CREATE TABLE `imicms_sign_conf` (
  `conf_id` int(3) unsigned NOT NULL auto_increment,
  `use` enum('0','1') NOT NULL,
  `integral` tinyint(4) NOT NULL,
  `stair` tinyint(4) NOT NULL,
  `token` char(25) NOT NULL,
  PRIMARY KEY  (`conf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>