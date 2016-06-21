<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_photography`;");
E_C("CREATE TABLE `imicms_photography` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL COMMENT '相册id',
  `token` varchar(60) NOT NULL COMMENT '所属公众号',
  `keyword` varchar(60) NOT NULL COMMENT '关键字',
  `tpl` varchar(30) NOT NULL default 'index' COMMENT '模板名',
  `title` varchar(150) NOT NULL COMMENT '标题',
  `picurl` varchar(200) NOT NULL COMMENT '封面图',
  `openpic` varchar(200) NOT NULL COMMENT '开场图',
  `toppic` varchar(200) NOT NULL COMMENT '顶部图',
  `man` varchar(30) NOT NULL COMMENT '男方姓名',
  `woman` varchar(30) NOT NULL COMMENT '女方姓名',
  `who_first` tinyint(1) NOT NULL COMMENT '谁名字在先',
  `video` varchar(200) NOT NULL COMMENT '视频',
  `mp3url` varchar(200) NOT NULL COMMENT '背景音乐',
  `word` varchar(200) NOT NULL COMMENT '留言',
  `firstvote` varchar(20) NOT NULL COMMENT '第一个投票内容',
  `firstvotenum` int(1) NOT NULL COMMENT '第一个投票票数',
  `secondvote` varchar(20) NOT NULL COMMENT '第二个投票内容',
  `secondvotenum` int(1) NOT NULL COMMENT '第二个投票票数',
  `thirdvote` varchar(20) NOT NULL COMMENT '第三个投票内容',
  `thirdvotenum` int(1) NOT NULL COMMENT '第三个投票票数',
  `first` varchar(100) NOT NULL COMMENT '第一个奖品',
  `firstnum` int(1) NOT NULL COMMENT '第一个奖品需要的助力数量',
  `second` varchar(100) NOT NULL COMMENT '第二个奖品',
  `secondnum` int(1) NOT NULL COMMENT '第二个奖品数量',
  `nownum` int(1) NOT NULL COMMENT '当前助力数量',
  `success` varchar(100) NOT NULL COMMENT '报名成功提示',
  `bottompic` varchar(200) NOT NULL COMMENT '底部图片',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `toolbar` tinyint(1) NOT NULL default '1' COMMENT '显示分类按钮',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>