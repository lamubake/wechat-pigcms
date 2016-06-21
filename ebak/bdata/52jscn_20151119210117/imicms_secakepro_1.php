<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakepro`;");
E_C("CREATE TABLE `imicms_secakepro` (
  `p_id` int(10) unsigned NOT NULL auto_increment,
  `pro_name` varchar(100) NOT NULL default '',
  `pro_num` varchar(100) NOT NULL default '',
  `pro_zhanguan` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`p_id`),
  KEY `pro_name` USING BTREE (`pro_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>