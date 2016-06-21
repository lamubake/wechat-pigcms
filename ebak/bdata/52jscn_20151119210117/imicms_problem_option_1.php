<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_problem_option`;");
E_C("CREATE TABLE `imicms_problem_option` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `question_id` int(11) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `answer` varchar(500) NOT NULL,
  `is_true` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>