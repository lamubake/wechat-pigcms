<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_ndisk`;");
E_C("CREATE TABLE `imicms_ndisk` (
  `id` bigint(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `kw_pic` text NOT NULL,
  `picurl` text NOT NULL,
  `num` tinyint(4) NOT NULL default '0',
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `info` text NOT NULL,
  `sort` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>