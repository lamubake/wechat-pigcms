<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_integral`;");
E_C("CREATE TABLE `imicms_member_card_integral` (
  `id` int(11) NOT NULL auto_increment,
  `cardid` int(10) NOT NULL default '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `integral` int(8) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `usetime` int(10) NOT NULL default '0',
  `create_time` int(11) NOT NULL,
  `pic` char(200) NOT NULL,
  `people` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>