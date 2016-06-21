<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_problem_user`;");
E_C("CREATE TABLE `imicms_problem_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(20) NOT NULL,
  `wecha_id` char(100) NOT NULL,
  `user_name` char(50) NOT NULL,
  `phone` char(11) NOT NULL,
  `nickname` char(50) NOT NULL,
  `add_time` char(10) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `score_count` int(11) NOT NULL,
  `expend_count` mediumint(9) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>