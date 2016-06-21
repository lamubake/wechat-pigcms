<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_rule`;");
E_C("CREATE TABLE `imicms_member_card_rule` (
  `id` int(12) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `percent_rule` varchar(100) NOT NULL COMMENT '百分比返还',
  `fixed_rule` text NOT NULL COMMENT '固定返还',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>