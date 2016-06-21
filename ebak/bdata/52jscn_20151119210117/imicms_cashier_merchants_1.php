<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_merchants`;");
E_C("CREATE TABLE `imicms_cashier_merchants` (
  `mid` int(11) NOT NULL auto_increment,
  `username` char(50) default NULL,
  `thirduserid` varchar(100) NOT NULL COMMENT '第三方唯一身份ID',
  `password` char(32) default NULL,
  `salt` char(50) NOT NULL,
  `wxname` char(210) NOT NULL,
  `weixin` varchar(150) NOT NULL COMMENT '微信号',
  `email` char(100) default NULL,
  `logo` char(200) NOT NULL,
  `regTime` int(11) default NULL,
  `regIp` char(20) default NULL,
  `lastLoginTime` int(11) default '0',
  `lastLoginIp` char(20) default NULL,
  `source` tinyint(1) unsigned NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '1',
  `mfypwd` tinyint(1) unsigned NOT NULL COMMENT '1修改过密码',
  `aeskey` varchar(50) NOT NULL COMMENT 'EncodingAESKey',
  `wxtoken` varchar(40) NOT NULL COMMENT 'wxToken',
  `encodetype` tinyint(1) unsigned NOT NULL default '0' COMMENT '消息加解密方式',
  PRIMARY KEY  (`mid`),
  KEY `thirduserid` (`thirduserid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>