<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wewalllog`;");
E_C("CREATE TABLE `imicms_wewalllog` (
  `id` int(11) NOT NULL auto_increment,
  `openid` varchar(30) NOT NULL,
  `content` varchar(200) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `token` varchar(30) NOT NULL,
  `uid` int(11) default NULL,
  `sncode` varchar(20) default NULL,
  `ifcheck` int(1) default '0',
  `ifsent` int(1) default '0',
  `ifscheck` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>