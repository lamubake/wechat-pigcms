<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_send_message`;");
E_C("CREATE TABLE `imicms_send_message` (
  `id` int(10) NOT NULL auto_increment,
  `msg_id` varchar(20) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `token` varchar(30) NOT NULL default '',
  `msgtype` varchar(30) NOT NULL default '',
  `text` varchar(800) NOT NULL default '',
  `imgids` varchar(200) NOT NULL default '',
  `mediasrc` varchar(200) NOT NULL default '',
  `mediaid` varchar(100) NOT NULL default '',
  `reachcount` int(10) NOT NULL default '0',
  `groupid` int(10) NOT NULL default '0',
  `time` int(10) NOT NULL default '0',
  `openid` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `send_type` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`,`time`),
  KEY `msg_id` (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>