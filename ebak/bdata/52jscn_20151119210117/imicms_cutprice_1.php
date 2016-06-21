<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cutprice`;");
E_C("CREATE TABLE `imicms_cutprice` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` varchar(500) default NULL,
  `starttime` int(11) NOT NULL,
  `original` varchar(100) NOT NULL,
  `startprice` varchar(100) NOT NULL,
  `stopprice` varchar(100) NOT NULL,
  `cuttime` int(11) NOT NULL,
  `cutprice` varchar(100) NOT NULL,
  `inventory` int(11) NOT NULL,
  `logoimg1` varchar(100) NOT NULL,
  `logourl1` varchar(200) default NULL,
  `logoimg2` varchar(100) default NULL,
  `logourl2` varchar(200) default NULL,
  `logoimg3` varchar(100) default NULL,
  `logourl3` varchar(200) default NULL,
  `info` text,
  `guize` text,
  `state` int(11) NOT NULL default '0',
  `state_subscribe` int(11) NOT NULL default '0',
  `state_userinfo` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL,
  `onebuynum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>