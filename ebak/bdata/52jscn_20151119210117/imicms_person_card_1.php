<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_person_card`;");
E_C("CREATE TABLE `imicms_person_card` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(100) NOT NULL default '',
  `truename` char(100) NOT NULL default '',
  `wecha_id` varchar(255) NOT NULL default '',
  `protrait` varchar(255) NOT NULL default '',
  `mobile` char(11) NOT NULL default '',
  `tel` char(20) NOT NULL,
  `mobile2` char(20) NOT NULL,
  `mail` char(100) NOT NULL default '',
  `fax` char(20) NOT NULL default '',
  `domain` char(100) NOT NULL default '',
  `remark` varchar(255) NOT NULL default '',
  `forward_content` char(100) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `username` char(100) NOT NULL default '',
  `city` char(20) NOT NULL default '',
  `province` char(20) NOT NULL default '',
  `position` char(100) NOT NULL default '',
  `company` char(100) NOT NULL default '',
  `mould_id` int(20) NOT NULL,
  `background` char(100) NOT NULL default '',
  `font_color` char(100) NOT NULL default '',
  `regtime` char(100) NOT NULL default '',
  `background_id` int(20) NOT NULL,
  `is_reg` int(2) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>