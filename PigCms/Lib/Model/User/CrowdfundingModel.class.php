<?php
	class CrowdfundingModel extends Model{

	protected $_validate = array(
		array('name','require','项目名称不能为空'),
		array('keyword','require','关键词不能为空'),
		array('type','require','项目类型必须选择'),
		array('pic','require','产品图片不能为空'),
		array('intro','require','项目简介不能为空'),
		array('fund','require','筹集金额不能为空'),
		array('max','require','筹集上限不能为空'),
		array('day','require','筹集天数不能为空'),
		array('detail','require','项目详情不能为空'),
	);

}

?>