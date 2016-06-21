<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_photography_info`;");
E_C("CREATE TABLE `imicms_photography_info` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL COMMENT '主表id',
  `phone` varchar(11) NOT NULL COMMENT '电话',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>