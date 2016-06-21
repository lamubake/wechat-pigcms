<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wxwall_members`;");
E_C("CREATE TABLE `imicms_wxwall_members` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID',
  `nickname` varchar(20) NOT NULL default '' COMMENT '昵称',
  `avatar` varchar(100) NOT NULL default '' COMMENT '头像',
  `wxq_id` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
  `isjoin` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否正在加入话题',
  `isblacklist` tinyint(1) NOT NULL default '0' COMMENT '用户是否是黑名单',
  `phone` varchar(11) NOT NULL default '',
  `lastupdate` int(10) unsigned NOT NULL COMMENT '用户最后发表时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>