<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_service_setup`;");
E_C("CREATE TABLE `imicms_service_setup` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) default NULL,
  `headimgurl` varchar(200) default NULL,
  `addtime` int(11) default NULL,
  `desc` text,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>