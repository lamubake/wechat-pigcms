<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wxcert`;");
E_C("CREATE TABLE `imicms_wxcert` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `apiclient_cert` varchar(1000) NOT NULL,
  `apiclient_key` varchar(1000) NOT NULL,
  `rootca` varchar(1000) NOT NULL,
  `uploadtime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>