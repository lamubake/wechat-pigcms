<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle_sellcircle`;");
E_C("CREATE TABLE `imicms_livingcircle_sellcircle` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(11) NOT NULL default '1',
  `display` int(11) NOT NULL default '1',
  `sellcircleid` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>