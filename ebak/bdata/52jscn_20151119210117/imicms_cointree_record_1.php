<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cointree_record`;");
E_C("CREATE TABLE `imicms_cointree_record` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `serialnumber` varchar(15) NOT NULL default '',
  `prize` int(11) NOT NULL,
  `iswin` tinyint(1) NOT NULL default '0',
  `shaketime` int(11) NOT NULL,
  `sendstutas` tinyint(1) NOT NULL default '0',
  `sendtime` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `wecha_name` char(50) NOT NULL,
  `wecha_pic` varchar(255) NOT NULL,
  `token` char(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>