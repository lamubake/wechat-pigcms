<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_question_bank`;");
E_C("CREATE TABLE `imicms_question_bank` (
  `id` int(11) NOT NULL,
  `figure` varchar(2) default NULL,
  `question_types` varchar(2) default NULL,
  `question` varchar(255) default NULL,
  `option_num` int(11) default NULL,
  `optionA` varchar(100) default NULL,
  `optionB` varchar(100) default NULL,
  `optionC` varchar(100) default NULL,
  `optionD` varchar(100) default NULL,
  `optionE` varchar(100) default NULL,
  `optionF` varchar(100) default NULL,
  `answer` varchar(6) default NULL,
  `classify` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>