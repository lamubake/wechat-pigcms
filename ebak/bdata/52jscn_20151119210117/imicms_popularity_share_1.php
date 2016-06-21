<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_popularity_share`;");
E_C("CREATE TABLE `imicms_popularity_share` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `share_key` char(32) NOT NULL,
  `add_time` char(15) NOT NULL,
  `num` int(11) NOT NULL,
  `share_count` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>