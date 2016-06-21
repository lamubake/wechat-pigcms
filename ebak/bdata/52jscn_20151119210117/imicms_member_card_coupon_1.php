<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_coupon`;");
E_C("CREATE TABLE `imicms_member_card_coupon` (
  `id` int(11) NOT NULL auto_increment,
  `cardid` int(10) NOT NULL default '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `group` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `price` int(11) NOT NULL,
  `people` int(3) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `usetime` int(10) NOT NULL default '0',
  `create_time` int(11) NOT NULL,
  `pic` char(200) NOT NULL,
  `attr` enum('0','1') NOT NULL,
  `total` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `card_id` char(50) NOT NULL,
  `is_weixin` tinyint(4) NOT NULL,
  `color` char(10) NOT NULL,
  `is_check` tinyint(4) NOT NULL,
  `least_cost` decimal(10,2) NOT NULL,
  `reduce_cost` decimal(10,2) NOT NULL,
  `gift_name` char(30) NOT NULL,
  `integral` int(11) NOT NULL,
  `brand_name` char(20) NOT NULL,
  `logourl` char(150) NOT NULL,
  `is_delete` tinyint(4) NOT NULL,
  `is_huodong` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>