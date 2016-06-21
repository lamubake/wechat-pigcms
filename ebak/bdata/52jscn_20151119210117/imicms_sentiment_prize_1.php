<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sentiment_prize`;");
E_C("CREATE TABLE `imicms_sentiment_prize` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL,
  `imgurl` varchar(200) NOT NULL,
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>