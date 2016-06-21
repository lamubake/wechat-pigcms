<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_market_slide`;");
E_C("CREATE TABLE `imicms_market_slide` (
  `slide_id` int(10) unsigned NOT NULL auto_increment,
  `slide_title` char(35) default NULL,
  `slide_url` char(200) NOT NULL default '',
  `slide_link` char(200) NOT NULL default '',
  `market_id` int(11) NOT NULL,
  PRIMARY KEY  (`slide_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>