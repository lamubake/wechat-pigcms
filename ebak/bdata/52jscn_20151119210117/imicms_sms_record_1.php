<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sms_record`;");
E_C("CREATE TABLE `imicms_sms_record` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `token` varchar(20) NOT NULL,
  `time` int(10) NOT NULL,
  `mp` varchar(11) NOT NULL default '',
  `text` varchar(400) NOT NULL default '',
  `status` mediumint(4) NOT NULL default '0',
  `price` mediumint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`token`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>