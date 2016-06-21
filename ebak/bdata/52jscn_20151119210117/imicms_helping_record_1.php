<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_helping_record`;");
E_C("CREATE TABLE `imicms_helping_record` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(35) NOT NULL,
  `pid` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `addtime` char(35) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `pid` (`pid`),
  KEY `share_key` (`share_key`),
  KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>