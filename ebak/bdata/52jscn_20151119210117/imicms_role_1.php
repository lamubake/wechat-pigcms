<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_role`;");
E_C("CREATE TABLE `imicms_role` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL COMMENT '后台组名',
  `pid` smallint(6) unsigned NOT NULL default '0' COMMENT '父ID',
  `status` tinyint(1) unsigned default '0' COMMENT '是否激活 1：是 0：否',
  `sort` smallint(6) unsigned NOT NULL default '0' COMMENT '排序权重',
  `remark` varchar(255) default NULL COMMENT '备注说明',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_role` values('5','超级管理员','0','1','0','');");
E_D("replace into `imicms_role` values('6','演示组','0','1','0','');");
E_D("replace into `imicms_role` values('9','普通会员','0','1','0','');");
E_D("replace into `imicms_role` values('10','代理商','0','0','0','代理商用户组');");

require("../../inc/footer.php");
?>