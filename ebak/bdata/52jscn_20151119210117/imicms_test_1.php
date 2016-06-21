<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_test`;");
E_C("CREATE TABLE `imicms_test` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` varchar(100) default NULL,
  `indexpic` varchar(100) default NULL,
  `questionpic` varchar(100) default NULL,
  `bgcolor` varchar(100) NOT NULL default 'ffcb1d',
  `title` varchar(100) NOT NULL,
  `qtitle` varchar(100) NOT NULL,
  `fistq` varchar(100) NOT NULL,
  `fistapic` varchar(100) NOT NULL,
  `fistatitle` varchar(100) NOT NULL,
  `fistatitle2` varchar(100) NOT NULL,
  `fistainfo` varchar(200) default NULL,
  `secondq` varchar(100) NOT NULL,
  `secondapic` varchar(100) NOT NULL,
  `secondatitle` varchar(100) NOT NULL,
  `secondatitle2` varchar(100) NOT NULL,
  `secondainfo` varchar(200) default NULL,
  `thirdq` varchar(100) default NULL,
  `thirdapic` varchar(100) default NULL,
  `thirdatitle` varchar(100) default NULL,
  `thirdatitle2` varchar(100) default NULL,
  `thirdainfo` varchar(200) default NULL,
  `fourq` varchar(100) default NULL,
  `fourapic` varchar(100) default NULL,
  `fouratitle` varchar(100) default NULL,
  `fouratitle2` varchar(100) default NULL,
  `fourainfo` varchar(200) default NULL,
  `fiveq` varchar(100) default NULL,
  `fiveapic` varchar(100) default NULL,
  `fiveatitle` varchar(100) default NULL,
  `fiveatitle2` varchar(100) default NULL,
  `fiveainfo` varchar(200) default NULL,
  `pv` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL,
  `fistfx` varchar(200) default NULL,
  `secondfx` varchar(200) default NULL,
  `thirdfx` varchar(200) default NULL,
  `fourfx` varchar(200) default NULL,
  `fivefx` varchar(200) default NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>