<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_custom`;");
E_C("CREATE TABLE `imicms_member_card_custom` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `wechaname` tinyint(4) NOT NULL default '1',
  `tel` tinyint(4) NOT NULL default '1',
  `truename` tinyint(4) NOT NULL default '0',
  `qq` tinyint(4) NOT NULL default '0',
  `paypass` tinyint(4) NOT NULL default '1',
  `portrait` tinyint(4) NOT NULL default '0',
  `sex` tinyint(4) NOT NULL default '0',
  `bornyear` tinyint(4) NOT NULL default '0',
  `bornmonth` tinyint(4) NOT NULL default '0',
  `bornday` tinyint(4) NOT NULL default '0',
  `is_wechaname` tinyint(4) NOT NULL default '1',
  `is_tel` tinyint(4) NOT NULL default '1',
  `is_truename` tinyint(4) NOT NULL default '1',
  `is_qq` tinyint(4) NOT NULL default '0',
  `is_paypass` tinyint(4) NOT NULL default '1',
  `is_portrait` tinyint(4) NOT NULL default '0',
  `is_sex` tinyint(4) NOT NULL default '0',
  `is_bornyear` tinyint(4) NOT NULL default '0',
  `is_bornmonth` tinyint(4) NOT NULL default '0',
  `is_bornday` tinyint(4) NOT NULL default '0',
  `address` tinyint(1) NOT NULL default '0',
  `is_address` tinyint(1) NOT NULL default '0',
  `origin` tinyint(1) NOT NULL default '0',
  `is_origin` tinyint(1) NOT NULL default '0',
  `carnumber` tinyint(4) NOT NULL,
  `is_carnumber` tinyint(1) NOT NULL,
  `cart` tinyint(4) NOT NULL default '0',
  `is_cart` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>