<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shakelottery_record`;");
E_C("CREATE TABLE `imicms_shakelottery_record` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prizeid` int(11) NOT NULL,
  `prizename` varchar(50) NOT NULL,
  `iswin` tinyint(1) NOT NULL default '0',
  `shaketime` int(11) NOT NULL,
  `isaccept` tinyint(1) NOT NULL default '0',
  `accepttime` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `wecha_name` char(50) NOT NULL,
  `token` char(30) NOT NULL,
  `prize_type` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>