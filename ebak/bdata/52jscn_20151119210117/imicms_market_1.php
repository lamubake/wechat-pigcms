<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_market`;");
E_C("CREATE TABLE `imicms_market` (
  `market_id` int(10) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL,
  `title` char(30) NOT NULL,
  `keyword` char(20) NOT NULL,
  `tel` char(25) NOT NULL,
  `address` varchar(100) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  `line` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `logo_pic` char(100) NOT NULL,
  `token` char(20) NOT NULL,
  `market_index_tpl` mediumint(9) NOT NULL,
  `tenant_index_tpl` mediumint(9) NOT NULL,
  `tenant_list_tpl` mediumint(9) NOT NULL,
  PRIMARY KEY  (`market_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>