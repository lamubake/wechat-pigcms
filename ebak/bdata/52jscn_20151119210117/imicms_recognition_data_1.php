<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_recognition_data`;");
E_C("CREATE TABLE `imicms_recognition_data` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `rid` int(11) NOT NULL,
  `time` int(11) NOT NULL default '0',
  `year` int(11) NOT NULL default '0',
  `month` int(11) NOT NULL default '0',
  `day` int(11) NOT NULL default '0',
  `is_ali` int(11) NOT NULL default '0',
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>