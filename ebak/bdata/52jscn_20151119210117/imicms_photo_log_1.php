<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_photo_log`;");
E_C("CREATE TABLE `imicms_photo_log` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL default '',
  `picurl` varchar(200) NOT NULL default '',
  `openid` varchar(100) NOT NULL default '',
  `printed` tinyint(1) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>