<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dajiangsai_user`;");
E_C("CREATE TABLE `imicms_dajiangsai_user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `help_count` int(11) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_key` char(40) NOT NULL,
  `help_score` int(10) NOT NULL,
  `help_num` int(100) NOT NULL,
  `help_rank` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tel` varchar(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=534 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>