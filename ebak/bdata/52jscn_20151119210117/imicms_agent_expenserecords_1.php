<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_agent_expenserecords`;");
E_C("CREATE TABLE `imicms_agent_expenserecords` (
  `id` int(10) NOT NULL auto_increment,
  `agentid` int(10) NOT NULL default '0',
  `amount` int(10) NOT NULL default '0',
  `orderid` varchar(60) NOT NULL default '',
  `des` varchar(200) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `agentid` (`agentid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>