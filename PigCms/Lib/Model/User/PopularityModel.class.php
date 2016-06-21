<?php
class PopularityModel extends Model{
		
	protected $_validate = array(
		array('title','require','名称不能为空'),
		array('keyword','require','关键词不能为空'),
		array('pic','require','消息回复图片不能为空'),
		array('start','require','开始时间不能为空'),
		array('end','require','结束时间不能为空'),
		array('addr','require','地址不能为空'),
		array('longitude','require','经度不能为空'),
		array('latitude','require','纬度不能为空'),
	);

/*	public $_auto 	= array(
		array('start','strtotime',3,'function'), 
		array('end','strtotime',3,'function'), 
		array('token','getToken',Model:: MODEL_BOTH,'callback'), 
		array('add_time','time',1,'function'),
	);
		
	public function getToken(){
		return $_SESSION['token'];
	}*/

}

?>