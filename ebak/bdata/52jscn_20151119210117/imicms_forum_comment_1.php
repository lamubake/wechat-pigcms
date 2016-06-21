<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_forum_comment`;");
E_C("CREATE TABLE `imicms_forum_comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `uid` varchar(50) default NULL,
  `uname` varchar(50) default NULL,
  `content` varchar(3000) NOT NULL,
  `createtime` int(11) NOT NULL,
  `favourid` varchar(3000) default NULL,
  `replyid` varchar(3000) default NULL,
  `status` tinyint(4) NOT NULL default '1',
  `token` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>