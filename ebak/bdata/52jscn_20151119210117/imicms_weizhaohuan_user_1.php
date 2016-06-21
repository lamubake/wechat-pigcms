<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_weizhaohuan_user`;");
E_C("CREATE TABLE `imicms_weizhaohuan_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `wecha_id` char(40) NOT NULL,
  `pid` int(11) NOT NULL,
  `token` char(35) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_count` int(11) NOT NULL,
  `share_key` char(40) NOT NULL,
  `is_real` tinyint(4) NOT NULL,
  `share_num` int(100) NOT NULL,
  `share_code` varchar(100) NOT NULL,
  `is_see` varchar(100) NOT NULL,
  `status` int(1) unsigned zerofill NOT NULL,
  `use_time` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>