<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_autumns_ip`;");
E_C("CREATE TABLE `imicms_autumns_ip` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(50) NOT NULL,
  `bid` int(11) NOT NULL COMMENT '盒子ID',
  `lid` int(11) NOT NULL COMMENT '活动ID',
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>