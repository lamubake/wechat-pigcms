<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_notice`;");
E_C("CREATE TABLE `imicms_member_card_notice` (
  `id` int(10) NOT NULL auto_increment,
  `cardid` int(10) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `content` text NOT NULL,
  `endtime` int(10) NOT NULL default '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>