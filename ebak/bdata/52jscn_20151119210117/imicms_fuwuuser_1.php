<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_fuwuuser`;");
E_C("CREATE TABLE `imicms_fuwuuser` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `user_id` varchar(512) default NULL COMMENT '用户的userId',
  `user_type_value` int(1) default NULL COMMENT '用户类型（1/2） 1代表公司账户； 2代表个人账户',
  `user_status` varchar(1) default NULL COMMENT '用户状态（Q/T/B/W）。 Q代表快速注册用户；T代表已认证用户；B代表被冻结账户；W代表已注册，未激活的账户',
  `firm_name` varchar(100) default NULL COMMENT '公司名称（用户类型是公司类型时公司名称才有此字段）。',
  `real_name` varchar(100) default NULL COMMENT '用户的真实姓名。',
  `avatar` varchar(200) default NULL COMMENT '用户头像',
  `cert_no` varchar(100) default NULL COMMENT '证件号码',
  `gender` varchar(1) default NULL COMMENT '性别（F：女性；M：男性）',
  `phone` varchar(20) default NULL COMMENT '电话号码。',
  `mobile` varchar(20) default NULL COMMENT '手机号码。',
  `is_certified` varchar(1) default NULL COMMENT '是否通过实名认证。T是通过 F是没有实名认证',
  `is_student_certified` varchar(1) default NULL COMMENT '是否是学生。T表示是学生，F表示不是学生',
  `is_bank_auth` varchar(1) default NULL COMMENT 'T为是银行卡认证，F为非银行卡认证。',
  `is_id_auth` varchar(1) default NULL COMMENT 'T为是身份证认证，F为非身份证认证。',
  `is_mobile_auth` varchar(1) default NULL COMMENT 'T为是手机认证，F为非手机认证。',
  `is_licence_auth` varchar(1) default NULL COMMENT 'T为通过营业执照认证，F为没有通过',
  `cert_type_value` int(3) default NULL COMMENT '0:身份证；1:护照；2:军官证；3:士兵证；4:回乡证；5:临时身份证；6:户口簿；7:警官证；8:台胞证；9:营业执照；10其它证件',
  `province` varchar(20) default NULL COMMENT '省份名称。',
  `city` varchar(20) default NULL COMMENT '市名称。',
  `area` varchar(20) default NULL COMMENT '区县名称。',
  `address` varchar(200) default NULL COMMENT '详细地址。',
  `zip` int(20) default NULL COMMENT '邮政编码。',
  `address_code` int(100) default NULL COMMENT '区域编码，暂时不返回值',
  `type` int(11) NOT NULL default '0',
  `addtime` int(11) default NULL,
  `wecha_id` varchar(100) default NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>