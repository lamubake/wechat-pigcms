<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg_banner`;");
E_C("CREATE TABLE `imicms_voteimg_banner` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `img_url` varchar(100) NOT NULL default '',
  `external_links` varchar(1000) NOT NULL,
  `banner_rank` int(11) NOT NULL default '1' COMMENT '播放顺序',
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  PRIMARY KEY  (`id`),
  KEY `vote_id` USING BTREE (`vote_id`),
  KEY `banner_index` USING BTREE (`vote_id`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>