<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_school_students`;");
E_C("CREATE TABLE `imicms_school_students` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `xq_id` int(11) NOT NULL,
  `area_addr` varchar(60) NOT NULL default '',
  `bj_id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` int(1) NOT NULL,
  `createdate` int(11) NOT NULL,
  `seffectivetime` date default NULL,
  `stheendtime` date default NULL,
  `jf_statu` int(11) default NULL,
  `mobile` char(11) NOT NULL,
  `homephone` char(16) NOT NULL,
  `s_name` char(50) NOT NULL,
  `localdate_id` char(20) NOT NULL default '',
  `note` varchar(50) NOT NULL default '',
  `amount` int(11) NOT NULL,
  `area` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>