<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_use_record`;");
E_C("CREATE TABLE `imicms_member_card_use_record` (
  `id` int(10) NOT NULL auto_increment,
  `itemid` int(10) default '0',
  `token` varchar(30) NOT NULL default '',
  `wecha_id` varchar(50) NOT NULL default '',
  `staffid` int(10) NOT NULL default '0',
  `cat` smallint(4) NOT NULL default '0',
  `expense` mediumint(4) NOT NULL default '0',
  `score` mediumint(4) NOT NULL default '0',
  `usecount` mediumint(4) NOT NULL default '1',
  `time` int(10) NOT NULL default '0',
  `notes` varchar(300) NOT NULL,
  `company_id` int(11) NOT NULL,
  `cardid` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `orderid` char(35) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `itemid` (`itemid`,`cat`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>