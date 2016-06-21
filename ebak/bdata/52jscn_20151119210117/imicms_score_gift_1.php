<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_score_gift`;");
E_C("CREATE TABLE `imicms_score_gift` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '奖品名称',
  `score` int(11) NOT NULL default '0' COMMENT '所需积分',
  `num` int(11) NOT NULL COMMENT '剩余数量',
  `status` tinyint(2) NOT NULL default '1' COMMENT '状态',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>