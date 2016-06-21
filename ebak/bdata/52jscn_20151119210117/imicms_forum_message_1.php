<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_forum_message`;");
E_C("CREATE TABLE `imicms_forum_message` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `content` varchar(3000) NOT NULL,
  `createtime` int(11) NOT NULL,
  `fromuid` varchar(50) NOT NULL,
  `touid` varchar(40) NOT NULL,
  `fromuname` varchar(60) default NULL,
  `touname` varchar(60) default NULL,
  `tid` int(11) default NULL,
  `cid` int(11) default NULL,
  `token` varchar(50) default NULL,
  `status` tinyint(4) NOT NULL default '1',
  `isread` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>