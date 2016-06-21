<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_router_config`;");
E_C("CREATE TABLE `imicms_router_config` (
  `id` int(10) NOT NULL auto_increment,
  `keyword` varchar(200) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  `picurl` varchar(200) NOT NULL default '',
  `info` varchar(300) NOT NULL default '',
  `contact_qq` varchar(12) NOT NULL default '',
  `welcome_img` varchar(200) NOT NULL default '',
  `other_img` varchar(200) NOT NULL default '',
  `token` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>