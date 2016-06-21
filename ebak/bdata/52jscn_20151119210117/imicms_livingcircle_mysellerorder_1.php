<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle_mysellerorder`;");
E_C("CREATE TABLE `imicms_livingcircle_mysellerorder` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) default NULL,
  `phone` varchar(100) default NULL,
  `address` varchar(200) default NULL,
  `sellerid` int(11) NOT NULL,
  `cid` int(11) default NULL,
  `price` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  `paytype` varchar(50) default NULL,
  `paid` tinyint(1) NOT NULL default '0',
  `third_id` varchar(100) default NULL,
  `state` int(11) NOT NULL default '0',
  `orderid` varchar(255) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>