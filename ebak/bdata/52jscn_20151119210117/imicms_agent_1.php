<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_agent`;");
E_C("CREATE TABLE `imicms_agent` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `intro` varchar(800) NOT NULL default '',
  `mp` varchar(11) NOT NULL default '',
  `usercount` int(10) NOT NULL default '0',
  `wxusercount` int(10) NOT NULL default '0',
  `sitename` varchar(50) NOT NULL default '',
  `sitelogo` varchar(200) NOT NULL default '',
  `qrcode` varchar(100) NOT NULL default '',
  `sitetitle` varchar(60) NOT NULL default '',
  `siteurl` varchar(100) NOT NULL default '',
  `robotname` varchar(40) NOT NULL default '',
  `connectouttip` varchar(50) NOT NULL default '',
  `needcheckuser` tinyint(1) NOT NULL default '0',
  `regneedmp` tinyint(1) NOT NULL default '1',
  `reggid` int(10) NOT NULL default '0',
  `regvaliddays` mediumint(4) NOT NULL default '30',
  `qq` varchar(12) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `metades` varchar(300) NOT NULL default '',
  `metakeywords` varchar(200) NOT NULL default '',
  `statisticcode` varchar(300) NOT NULL default '',
  `copyright` varchar(100) NOT NULL default '',
  `alipayaccount` varchar(50) NOT NULL default '',
  `alipaypid` varchar(100) NOT NULL default '',
  `alipaykey` varchar(100) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `salt` varchar(6) NOT NULL default '',
  `money` int(10) NOT NULL default '0',
  `moneybalance` int(10) NOT NULL default '0',
  `time` int(10) NOT NULL default '0',
  `endtime` int(11) NOT NULL default '0',
  `lastloginip` varchar(26) NOT NULL default '',
  `lastlogintime` int(11) NOT NULL default '0',
  `wxacountprice` mediumint(4) NOT NULL default '0',
  `monthprice` mediumint(4) NOT NULL default '0',
  `appid` varchar(50) NOT NULL default '',
  `appsecret` varchar(100) NOT NULL default '',
  `title` varchar(40) NOT NULL default '',
  `content` text NOT NULL,
  `level` int(11) NOT NULL default '0',
  `isnav` int(11) NOT NULL default '0',
  `agenturl` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>