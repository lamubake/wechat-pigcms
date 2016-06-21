<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_forum_config`;");
E_C("CREATE TABLE `imicms_forum_config` (
  `id` int(11) NOT NULL auto_increment,
  `bgurl` varchar(200) NOT NULL default '',
  `picurl` varchar(200) NOT NULL default '',
  `comcheck` varchar(4) NOT NULL default '',
  `intro` varchar(600) NOT NULL default '',
  `ischeck` tinyint(4) NOT NULL default '0',
  `pv` float NOT NULL default '0',
  `forumname` char(60) default NULL,
  `logo` varchar(200) default NULL,
  `token` varchar(50) NOT NULL,
  `isopen` tinyint(4) default '1',
  `notice_type` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>