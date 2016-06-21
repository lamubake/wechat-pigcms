<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_router`;");
E_C("CREATE TABLE `imicms_router` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `bywechat` tinyint(1) NOT NULL default '1',
  `wechat` varchar(50) NOT NULL default '',
  `qrcode` varchar(200) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `token` varchar(40) NOT NULL default '',
  `gw_id` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>