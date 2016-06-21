<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg_stat`;");
E_C("CREATE TABLE `imicms_voteimg_stat` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  `stat_name` varchar(100) NOT NULL default '',
  `count` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL default '1' COMMENT '是否隐藏',
  PRIMARY KEY  (`id`),
  KEY `vote_id` USING BTREE (`vote_id`),
  KEY `stat_index` USING BTREE (`vote_id`,`token`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>