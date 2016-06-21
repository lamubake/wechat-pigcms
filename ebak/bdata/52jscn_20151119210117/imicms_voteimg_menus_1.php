<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg_menus`;");
E_C("CREATE TABLE `imicms_voteimg_menus` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  `menu_name` varchar(50) NOT NULL default '',
  `menu_icon` varchar(255) NOT NULL default '' COMMENT '菜单图标',
  `menu_link` varchar(255) NOT NULL default '' COMMENT '菜单链接',
  `hide` tinyint(1) NOT NULL default '1' COMMENT '是否隐藏',
  `type` tinyint(4) NOT NULL default '1' COMMENT '是否是内置菜单',
  PRIMARY KEY  (`id`),
  KEY `vote_id` USING BTREE (`vote_id`),
  KEY `menus_index` USING BTREE (`vote_id`,`token`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>