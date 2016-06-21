<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle_mysellergoods`;");
E_C("CREATE TABLE `imicms_livingcircle_mysellergoods` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `sellerid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `display` int(11) NOT NULL default '1',
  `num` int(11) NOT NULL default '1',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>