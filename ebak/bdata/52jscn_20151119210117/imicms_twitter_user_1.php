<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_twitter_user`;");
E_C("CREATE TABLE `imicms_twitter_user` (
  `id` int(11) NOT NULL,
  `agentid` int(10) NOT NULL,
  `uid` int(11) NOT NULL,
  `wxname` varchar(60) NOT NULL,
  `token` varchar(255) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>