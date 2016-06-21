<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg_bottom`;");
E_C("CREATE TABLE `imicms_voteimg_bottom` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `bottom_name` char(50) NOT NULL COMMENT '导航名称',
  `bottom_link` varchar(255) NOT NULL,
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  `bottom_icon` varchar(255) NOT NULL COMMENT '导航图标',
  `bottom_rank` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL default '1' COMMENT '是否隐藏',
  `type` tinyint(1) NOT NULL default '1' COMMENT '是否是内置导航',
  PRIMARY KEY  (`id`),
  KEY `vote_id` USING BTREE (`vote_id`),
  KEY `bottom_index` USING BTREE (`vote_id`,`token`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>