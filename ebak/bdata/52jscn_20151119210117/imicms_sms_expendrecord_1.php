<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sms_expendrecord`;");
E_C("CREATE TABLE `imicms_sms_expendrecord` (
  `id` int(11) default NULL,
  `uid` int(11) NOT NULL default '0',
  `price` int(6) NOT NULL default '0',
  `count` int(10) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  KEY `uid` (`uid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>