<?php 
class HongbaoModel extends Model{
	//自动验证
	protected $_validate = array(
		array('action_name','require','活动名称必填'),
		array('keyword','require','关键词必填'), 
	);
}