<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_frontpage_configure`;");
E_C("CREATE TABLE `imicms_frontpage_configure` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `apikey` char(30) NOT NULL default '',
  `secretkey` char(50) NOT NULL default '',
  `access_token` char(100) NOT NULL default '',
  `expires_in` int(11) NOT NULL,
  `token` char(50) NOT NULL default '',
  `addtime` int(11) NOT NULL,
  `isusing` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>