<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dajiangsai_record`;");
E_C("CREATE TABLE `imicms_dajiangsai_record` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(35) NOT NULL,
  `pid` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `addtime` char(35) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `score` int(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=409 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>