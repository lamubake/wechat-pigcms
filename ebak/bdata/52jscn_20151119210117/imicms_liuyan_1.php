<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_liuyan`;");
E_C("CREATE TABLE `imicms_liuyan` (
  `id` int(11) NOT NULL auto_increment,
  `uid` varchar(30) NOT NULL,
  `title` varchar(30) default NULL,
  `text` varchar(500) default NULL,
  `createtime` int(20) default NULL,
  `uptatetime` int(20) default NULL,
  `token` varchar(30) NOT NULL,
  `re` varchar(500) default NULL,
  `wecha_id` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>