<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_customer`;");
E_C("CREATE TABLE `imicms_customer` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `userid` bigint(20) unsigned NOT NULL default '0',
  `name` varchar(50) default '',
  `sex` varchar(4) default '',
  `mobile` varchar(200) default '',
  `tel` varchar(200) default '',
  `email` varchar(200) default '',
  `company` varchar(100) default '',
  `job` varchar(20) default '',
  `address` varchar(120) default '',
  `website` varchar(200) default '',
  `qq` varchar(16) default '',
  `weixin` varchar(50) default '',
  `yixin` varchar(50) default '',
  `weibo` varchar(50) default '',
  `laiwang` varchar(50) default '',
  `remark` varchar(255) default '',
  `origin` bigint(20) unsigned NOT NULL default '0',
  `originName` varchar(50) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `createUser` varchar(32) NOT NULL default '',
  `createTime` int(10) unsigned NOT NULL default '0',
  `groupId` varchar(20) NOT NULL default '',
  `groupName` varchar(200) default '',
  `group` varchar(50) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>