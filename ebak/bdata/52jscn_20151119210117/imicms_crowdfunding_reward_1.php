<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_crowdfunding_reward`;");
E_C("CREATE TABLE `imicms_crowdfunding_reward` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(30) NOT NULL,
  `pid` int(11) NOT NULL,
  `money` float NOT NULL,
  `content` text NOT NULL,
  `img` varchar(250) NOT NULL,
  `people` int(11) NOT NULL,
  `back_day` smallint(6) NOT NULL,
  `carriage` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>