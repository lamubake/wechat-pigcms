<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_busines_pic`;");
E_C("CREATE TABLE `imicms_busines_pic` (
  `pid` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `bid_id` int(11) NOT NULL,
  `picurl_1` varchar(200) NOT NULL default '',
  `picurl_2` varchar(200) NOT NULL default '',
  `picurl_3` varchar(200) NOT NULL default '',
  `picurl_4` varchar(200) NOT NULL default '',
  `picurl_5` varchar(200) NOT NULL default '',
  `token` varchar(50) NOT NULL,
  `type` char(15) NOT NULL,
  `ablum_id` int(11) NOT NULL,
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>