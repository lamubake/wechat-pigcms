<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dajiangsai`;");
E_C("CREATE TABLE `imicms_dajiangsai` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `share_pic` varchar(250) NOT NULL default '',
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `add_time` char(15) NOT NULL default '',
  `is_open` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_score` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `wecha_id` varchar(100) NOT NULL default '',
  `minscore` char(10) NOT NULL default '1',
  `maxscore` char(10) NOT NULL default '',
  `frist` varchar(20) NOT NULL default '',
  `fristpic` varchar(200) NOT NULL default '',
  `fristnums` varchar(10) NOT NULL default '',
  `fristdoc` varchar(20) NOT NULL default '',
  `second` varchar(20) NOT NULL default '',
  `secondpic` varchar(200) NOT NULL default '',
  `secondnums` varchar(10) NOT NULL default '',
  `seconddoc` varchar(20) NOT NULL default '',
  `third` varchar(20) NOT NULL default '',
  `thirdpic` varchar(200) NOT NULL default '',
  `thirdnums` varchar(10) NOT NULL default '',
  `thirddoc` varchar(20) NOT NULL default '',
  `four` varchar(20) NOT NULL default '',
  `fourpic` varchar(200) NOT NULL default '',
  `fournums` varchar(10) NOT NULL default '',
  `fourdoc` varchar(20) NOT NULL default '',
  `five` varchar(20) NOT NULL default '',
  `fivepic` varchar(200) NOT NULL default '',
  `fivenums` varchar(10) NOT NULL default '',
  `fivedoc` varchar(20) NOT NULL default '',
  `pid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>