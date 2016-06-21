<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_forum_topics`;");
E_C("CREATE TABLE `imicms_forum_topics` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(300) NOT NULL,
  `content` varchar(1500) NOT NULL,
  `likeid` varchar(3000) default NULL,
  `commentid` varchar(3000) default NULL,
  `favourid` varchar(3000) default NULL,
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) default NULL,
  `uid` varchar(50) default NULL,
  `uname` varchar(50) default NULL,
  `token` varchar(50) default NULL,
  `photos` varchar(300) default NULL,
  `status` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>