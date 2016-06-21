<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_rippleos_node`;");
E_C("CREATE TABLE `imicms_rippleos_node` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(255) default NULL,
  `node` int(20) default NULL,
  `keyword` varchar(255) default NULL,
  `text` varchar(255) default NULL,
  `code_keyword` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>