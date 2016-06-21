<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_user_address`;");
E_C("CREATE TABLE `imicms_user_address` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL default '0' COMMENT '?û?id',
  `name` varchar(50) NOT NULL default '' COMMENT '?ջ???',
  `tel` varchar(50) NOT NULL default '' COMMENT '??ϵ?绰',
  `address` varchar(300) NOT NULL default '' COMMENT '?ջ???ַ',
  `postcode` varchar(10) NOT NULL default '' COMMENT '?ʱ?',
  `default_address` char(1) NOT NULL default '0' COMMENT 'Ĭ???ջ???ַ',
  PRIMARY KEY  (`id`),
  KEY `uid` USING BTREE (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>