<?php
class Crowdfunding_rewardModel extends Model{

	protected $_validate = array(
		array('money','require','支持金额不能为空'),
		array('content','require','回报内容不能为空'),
		array('img','require','说明图片不能为空'),
		array('people','require','限制名额不能为空'),
		array('back_day','require','回报时间不能为空'),
	);

}

?>