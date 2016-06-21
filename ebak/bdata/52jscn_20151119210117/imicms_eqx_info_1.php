<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_eqx_info`;");
E_C("CREATE TABLE `imicms_eqx_info` (
  `id` int(11) NOT NULL auto_increment,
  `pic` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `sceneid_bigint` varchar(200) NOT NULL default '',
  `info` varchar(500) NOT NULL default '',
  `keyword` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>