<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wxwall_message`;");
E_C("CREATE TABLE `imicms_wxwall_message` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `wxq_id` int(10) unsigned NOT NULL COMMENT '规则ID',
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一ID',
  `content` varchar(1000) NOT NULL default '' COMMENT '用户发表的内容',
  `type` varchar(10) NOT NULL COMMENT '发表内容类型',
  `isshow` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否显示',
  `createtime` int(10) unsigned NOT NULL,
  `isshowed` tinyint(1) NOT NULL default '0' COMMENT '是否显示过了 1 是 0否',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>