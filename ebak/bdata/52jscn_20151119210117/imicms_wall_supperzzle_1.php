<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wall_supperzzle`;");
E_C("CREATE TABLE `imicms_wall_supperzzle` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sceneid` int(10) unsigned NOT NULL,
  `nid` int(10) unsigned NOT NULL,
  `vid` int(10) unsigned NOT NULL,
  `addtime` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>