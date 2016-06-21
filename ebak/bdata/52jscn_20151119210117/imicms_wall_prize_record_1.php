<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wall_prize_record`;");
E_C("CREATE TABLE `imicms_wall_prize_record` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `wallid` int(11) NOT NULL default '0',
  `prize` mediumint(4) NOT NULL default '0',
  `time` int(11) NOT NULL,
  `sceneid` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `wallid` (`wallid`,`prize`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>