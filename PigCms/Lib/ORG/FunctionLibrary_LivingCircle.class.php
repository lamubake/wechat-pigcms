<?php
class FunctionLibrary_LivingCircle{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		$db		= M('livingcircle');
		$where	=array('token'=>$this->token);
		$items 	= $db->where($where)->find();
		return array(
		'name'=>'生活圈',
		'subkeywords'=>0,
		'sublinks'=>0,
		'link'=>'{siteUrl}/index.php?g=Wap&m=LivingCircle&a=index&token='.$this->token,
		'keyword'=>$items['keyword']
		);
		
	}
}