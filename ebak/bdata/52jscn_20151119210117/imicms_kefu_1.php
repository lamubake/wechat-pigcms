<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_kefu`;");
E_C("CREATE TABLE `imicms_kefu` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `title` varchar(50) NOT NULL,
  `picurl` varchar(200) NOT NULL,
  `info2` varchar(200) NOT NULL,
  `text` varchar(50) NOT NULL,
  `status` text NOT NULL,
  `sc` text NOT NULL,
  `cy` text NOT NULL,
  `lt` text NOT NULL,
  `yy` text NOT NULL,
  `hyk` text NOT NULL,
  `zp` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>