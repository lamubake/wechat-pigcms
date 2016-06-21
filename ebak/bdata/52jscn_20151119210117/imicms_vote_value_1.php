<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_vote_value`;");
E_C("CREATE TABLE `imicms_vote_value` (
  `id` bigint(11) NOT NULL auto_increment,
  `pid` bigint(11) NOT NULL,
  `vtitle` varchar(200) NOT NULL COMMENT '投票标题',
  `sort` tinyint(4) NOT NULL default '0' COMMENT '投票排序',
  `picurl` varchar(200) NOT NULL,
  `piclink` varchar(200) NOT NULL,
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>