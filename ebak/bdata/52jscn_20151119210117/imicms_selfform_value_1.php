<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_selfform_value`;");
E_C("CREATE TABLE `imicms_selfform_value` (
  `id` int(10) NOT NULL auto_increment,
  `formid` int(10) NOT NULL default '0',
  `wecha_id` varchar(50) NOT NULL default '',
  `values` varchar(2000) NOT NULL default '',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `formid` (`formid`,`wecha_id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>