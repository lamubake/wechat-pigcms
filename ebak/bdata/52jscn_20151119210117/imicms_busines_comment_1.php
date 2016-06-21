<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_busines_comment`;");
E_C("CREATE TABLE `imicms_busines_comment` (
  `cid` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `type` char(15) NOT NULL default '',
  `title` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL default '',
  `position` varchar(50) NOT NULL default '',
  `face_picurl` varchar(200) NOT NULL,
  `face_desc` varchar(1000) NOT NULL default '',
  `sort` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>