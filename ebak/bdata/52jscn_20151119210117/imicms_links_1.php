<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_links`;");
E_C("CREATE TABLE `imicms_links` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `url` char(255) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `agentid` USING BTREE (`agentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>