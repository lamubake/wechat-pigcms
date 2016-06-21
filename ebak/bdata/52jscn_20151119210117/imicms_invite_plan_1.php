<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_invite_plan`;");
E_C("CREATE TABLE `imicms_invite_plan` (
  `id` int(11) NOT NULL auto_increment,
  `yid` int(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `call` varchar(20) NOT NULL,
  `site` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>