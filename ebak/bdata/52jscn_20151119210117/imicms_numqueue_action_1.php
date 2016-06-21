<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_numqueue_action`;");
E_C("CREATE TABLE `imicms_numqueue_action` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `reply_keyword` char(30) NOT NULL,
  `reply_pic` varchar(100) NOT NULL,
  `reply_title` varchar(20) NOT NULL,
  `reply_content` varchar(200) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `is_open` tinyint(1) NOT NULL default '1',
  `token` char(25) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>