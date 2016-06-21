<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_custom_info`;");
E_C("CREATE TABLE `imicms_custom_info` (
  `info_id` int(11) NOT NULL auto_increment,
  `token` char(20) NOT NULL,
  `wecha_id` char(30) NOT NULL,
  `set_id` int(11) NOT NULL,
  `add_time` char(11) NOT NULL,
  `user_name` char(35) NOT NULL,
  `phone` char(11) NOT NULL,
  `sub_info` text NOT NULL,
  PRIMARY KEY  (`info_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_custom_info` values('4','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','2','1412665870','匿名','匿名','a:4:{i:0;a:2:{s:4:\"name\";s:12:\"服务类型\";s:5:\"value\";s:12:\"保洁沙发\";}i:1;a:2:{s:4:\"name\";s:12:\"联系电话\";s:5:\"value\";s:11:\"13888384509\";}i:2;a:2:{s:4:\"name\";s:9:\"联系人\";s:5:\"value\";s:9:\"杨小时\";}i:3;a:2:{s:4:\"name\";s:12:\"服务地址\";s:5:\"value\";s:12:\"昆钢小区\";}}');");

require("../../inc/footer.php");
?>