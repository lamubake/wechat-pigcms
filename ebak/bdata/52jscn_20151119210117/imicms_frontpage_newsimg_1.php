<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_frontpage_newsimg`;");
E_C("CREATE TABLE `imicms_frontpage_newsimg` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `news_id` char(10) NOT NULL default '',
  `default_img` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL,
  `token` char(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>