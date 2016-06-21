<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_research_result`;");
E_C("CREATE TABLE `imicms_research_result` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `rid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `qid` int(10) unsigned NOT NULL,
  `aids` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rid` (`rid`),
  KEY `wecha_id` (`wecha_id`,`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>