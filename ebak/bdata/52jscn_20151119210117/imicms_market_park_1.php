<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_market_park`;");
E_C("CREATE TABLE `imicms_market_park` (
  `park_id` int(10) unsigned NOT NULL auto_increment,
  `park_name` char(35) NOT NULL,
  `park_num` tinyint(4) NOT NULL,
  `park_intro` text NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  `add_time` char(10) NOT NULL,
  `market_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY  (`park_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>