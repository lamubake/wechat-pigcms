<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_zhida`;");
E_C("CREATE TABLE `imicms_zhida` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(1000) default NULL,
  `status` tinyint(1) NOT NULL default '0',
  `token` char(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>