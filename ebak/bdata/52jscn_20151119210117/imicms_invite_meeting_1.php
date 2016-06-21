<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_invite_meeting`;");
E_C("CREATE TABLE `imicms_invite_meeting` (
  `id` int(11) NOT NULL auto_increment,
  `yid` int(1) NOT NULL,
  `token` varchar(60) NOT NULL,
  `time` int(11) NOT NULL,
  `ytime` int(11) NOT NULL,
  `xtime` int(11) NOT NULL,
  `content` text NOT NULL,
  `guest` varchar(200) NOT NULL,
  `call` text NOT NULL,
  `site` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>