<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_funintro`;");
E_C("CREATE TABLE `imicms_funintro` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL default '',
  `isnew` tinyint(1) NOT NULL default '0',
  `logo` varchar(200) NOT NULL default '',
  `type` smallint(4) NOT NULL default '0',
  `content` text NOT NULL,
  `time` int(10) NOT NULL default '0',
  `img1` char(200) NOT NULL,
  `img2` char(200) NOT NULL,
  `img3` char(200) NOT NULL,
  `img4` char(200) NOT NULL,
  `img5` char(200) NOT NULL,
  `img6` char(200) NOT NULL,
  `img7` char(200) NOT NULL,
  `img8` char(200) NOT NULL,
  `name1` varchar(200) NOT NULL,
  `name2` varchar(200) NOT NULL,
  `name3` varchar(200) NOT NULL,
  `name4` varchar(200) NOT NULL,
  `name5` varchar(200) NOT NULL,
  `name6` varchar(200) NOT NULL,
  `name7` varchar(200) NOT NULL,
  `name8` varchar(200) NOT NULL,
  `class` varchar(200) NOT NULL,
  `classid` int(11) NOT NULL default '0',
  `public_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>