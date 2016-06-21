<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_user`;");
E_C("CREATE TABLE `imicms_moopha_user` (
  `uid` int(10) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `realname` varchar(50) default NULL,
  `email` varchar(60) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(6) NOT NULL,
  `mp` char(11) default NULL,
  `qq` varchar(15) default '',
  `isadmin` tinyint(1) NOT NULL default '0',
  `regip` varchar(30) default NULL,
  `regtime` int(10) default NULL,
  `lastloginip` varchar(30) default NULL,
  `lastlogintime` int(10) default NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>