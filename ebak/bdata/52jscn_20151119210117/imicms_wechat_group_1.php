<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wechat_group`;");
E_C("CREATE TABLE `imicms_wechat_group` (
  `id` int(10) NOT NULL auto_increment,
  `wechatgroupid` varchar(20) NOT NULL default '',
  `name` varchar(60) NOT NULL default '',
  `intro` varchar(200) NOT NULL default '',
  `token` varchar(30) NOT NULL default '',
  `fanscount` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `wechatgroupid` (`wechatgroupid`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>