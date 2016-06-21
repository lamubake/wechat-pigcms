<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yml_record`;");
E_C("CREATE TABLE `imicms_yml_record` (
  `id` bigint(20) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `wxid` varchar(100) NOT NULL,
  `step` text NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>