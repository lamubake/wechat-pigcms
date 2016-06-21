<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbaoqiye_set`;");
E_C("CREATE TABLE `imicms_hongbaoqiye_set` (
  `id` int(11) NOT NULL auto_increment,
  `pic` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `token` varchar(255) NOT NULL,
  `quyu` varchar(255) NOT NULL,
  `state_subscribe` int(11) NOT NULL,
  `statdate` varchar(255) NOT NULL,
  `enddate` varchar(255) NOT NULL,
  `tian` int(11) NOT NULL,
  `ci` int(11) NOT NULL,
  `haoyou` int(11) NOT NULL,
  `choujiangci` int(11) NOT NULL,
  `jiange` varchar(255) NOT NULL,
  `gz` varchar(255) NOT NULL,
  `banquan` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `ly` varchar(255) NOT NULL,
  `blackuser` varchar(255) NOT NULL,
  `blackip` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `state_pyq` int(11) NOT NULL,
  `state_hy` int(11) NOT NULL,
  `state_fansname` int(11) NOT NULL,
  `state_fanspic` int(11) NOT NULL,
  `cbt` varchar(255) NOT NULL,
  `quyus` varchar(255) NOT NULL,
  `quyux` varchar(255) NOT NULL,
  `user_total` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>