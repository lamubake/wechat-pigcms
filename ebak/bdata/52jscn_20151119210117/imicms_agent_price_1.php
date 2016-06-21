<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_agent_price`;");
E_C("CREATE TABLE `imicms_agent_price` (
  `id` int(10) NOT NULL auto_increment,
  `agentid` int(10) NOT NULL default '0',
  `minaccount` int(10) NOT NULL default '0',
  `maxaccount` int(10) NOT NULL default '0',
  `price` int(10) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `agentid` (`agentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>