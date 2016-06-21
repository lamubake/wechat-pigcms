<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_api`;");
E_C("CREATE TABLE `imicms_api` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(100) NOT NULL default '',
  `type` smallint(2) NOT NULL default '1',
  `url` varchar(100) NOT NULL,
  `number` tinyint(1) NOT NULL,
  `order` tinyint(1) NOT NULL,
  `is_colation` smallint(2) NOT NULL default '1',
  `colation_keyword` varchar(100) NOT NULL default '',
  `time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `noanswer` tinyint(1) NOT NULL default '0',
  `apitoken` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>