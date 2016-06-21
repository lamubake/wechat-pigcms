<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_tag`;");
E_C("CREATE TABLE `imicms_tag` (
  `tagid_int` int(11) NOT NULL,
  `userid_int` int(11) NOT NULL default '0',
  `name_varchar` varchar(50) default NULL,
  `type_int` int(11) default NULL COMMENT '背景还是图片0背景,1图片,2音乐,88样例,99用户',
  `biztype_int` int(11) default NULL,
  `create_time` datetime default NULL,
  PRIMARY KEY  (`tagid_int`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT");

require("../../inc/footer.php");
?>