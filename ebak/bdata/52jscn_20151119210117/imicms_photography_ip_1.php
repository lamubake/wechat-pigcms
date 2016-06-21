<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_photography_ip`;");
E_C("CREATE TABLE `imicms_photography_ip` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL COMMENT '主表id',
  `ip` varchar(32) NOT NULL COMMENT 'ip',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>