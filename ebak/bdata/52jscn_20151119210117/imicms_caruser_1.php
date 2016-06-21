<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_caruser`;");
E_C("CREATE TABLE `imicms_caruser` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `brand_serise` varchar(50) NOT NULL,
  `car_no` varchar(20) NOT NULL,
  `car_userName` varchar(50) NOT NULL,
  `car_startTime` varchar(50) NOT NULL,
  `car_insurance_lastDate` varchar(50) NOT NULL,
  `car_insurance_lastCost` decimal(10,2) NOT NULL,
  `car_care_mileage` int(11) NOT NULL,
  `user_tel` char(11) NOT NULL,
  `car_care_lastDate` varchar(50) NOT NULL,
  `car_care_lastCost` decimal(10,2) NOT NULL,
  `kfinfo` varchar(200) NOT NULL default '',
  `insurance_Date` varchar(50) default NULL,
  `insurance_Cost` decimal(10,2) default NULL,
  `care_mileage` int(11) default NULL,
  `car_care_Date` varchar(50) default NULL,
  `car_care_Cost` decimal(10,2) default NULL,
  `car_buyTime` varchar(50) NOT NULL default '',
  `car_care_inspection` varchar(50) NOT NULL default '',
  `care_next_mileage` int(11) NOT NULL default '0',
  `next_care_inspection` varchar(50) NOT NULL default '',
  `next_insurance_Date` varchar(50) NOT NULL default '',
  `carmodel` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>