<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_custom_field`;");
E_C("CREATE TABLE `imicms_custom_field` (
  `field_id` int(11) NOT NULL auto_increment,
  `field_name` char(15) NOT NULL,
  `filed_option` varchar(500) NOT NULL,
  `field_type` char(10) NOT NULL,
  `item_name` char(15) NOT NULL,
  `field_match` char(80) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `is_empty` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `err_info` char(35) NOT NULL,
  `set_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY  (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_custom_field` values('4','服务类型','','text','7i8c88_2','','1','1','50','输入错误，请重新输入','2','257e21c8a9e1be8c');");
E_D("replace into `imicms_custom_field` values('5','服务地址','','text','3vn0ff_2','','1','1','0','输入错误，请重新输入','2','257e21c8a9e1be8c');");
E_D("replace into `imicms_custom_field` values('6','联系电话','','text','t7axb_2','^13[0-9]{9}\$|^15[0-9]{9}\$|^18[0-9]{9}\$','1','1','50','输入错误，请重新输入','2','257e21c8a9e1be8c');");
E_D("replace into `imicms_custom_field` values('7','联系人','','text','bws6bm_2','','1','1','50','输入错误，请重新输入','2','257e21c8a9e1be8c');");
E_D("replace into `imicms_custom_field` values('8','服务类型','','text','majopo_3','','1','1','50','输入错误，请重新输入','3','c4448ac95e30a1eb');");
E_D("replace into `imicms_custom_field` values('9','联系电话','','text','shjsfp_3','^13[0-9]{9}\$|^15[0-9]{9}\$|^18[0-9]{9}\$','1','1','50','输入错误，请重新输入','3','c4448ac95e30a1eb');");
E_D("replace into `imicms_custom_field` values('10','联系人','','text','cawqua_3','','1','1','50','输入错误，请重新输入','3','c4448ac95e30a1eb');");
E_D("replace into `imicms_custom_field` values('11','服务地址','','text','tbx49_3','','1','1','50','输入错误，请重新输入','3','c4448ac95e30a1eb');");
E_D("replace into `imicms_custom_field` values('12','上门服务时间','','date','6ehokg_3','','1','1','50','输入错误，请重新输入','3','c4448ac95e30a1eb');");
E_D("replace into `imicms_custom_field` values('13','其他备注','','text','4chxn7_3','','1','1','50','输入错误，请重新输入','3','c4448ac95e30a1eb');");
E_D("replace into `imicms_custom_field` values('14','预约日期','','date','vokyo4_4','^[0-9]{1,30}\$','1','1','4','','4','1c5990460d702b81');");
E_D("replace into `imicms_custom_field` values('15','姓名','','text','kgb754_4','','1','1','1','输入你的姓名','4','1c5990460d702b81');");
E_D("replace into `imicms_custom_field` values('16','联系电话','','text','68eprx_4','','1','1','2','','4','1c5990460d702b81');");
E_D("replace into `imicms_custom_field` values('17','预约项目','','text','m7v09g_4','','1','1','3','','4','1c5990460d702b81');");
E_D("replace into `imicms_custom_field` values('18','昵称','','text','apb6pm_4','','1','1','50','昵称不能为空！','5','kaiqpo1447853601');");
E_D("replace into `imicms_custom_field` values('19','留言内容','','textarea','muyxab_4','','1','1','50','留言内容必填！','5','kaiqpo1447853601');");
E_D("replace into `imicms_custom_field` values('20','联系电话','','text','iitde_4','','1','0','50','','5','kaiqpo1447853601');");
E_D("replace into `imicms_custom_field` values('21','性别','男|女','select','d5xaw3_4','','1','1','50','','5','kaiqpo1447853601');");

require("../../inc/footer.php");
?>