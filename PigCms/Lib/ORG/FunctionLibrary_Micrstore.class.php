<?php
class FunctionLibrary_Micrstore{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		$db		= M('Micrstore_reply');
		$where	=array('token'=>$this->token);
		$items 	= $db->where($where)->find();
		return array(
		'name'=>'微店',
		'subkeywords'=>0,
		'sublinks'=>0,
		'link'=>'{siteUrl}/index.php?g=Wap&m=Micrstore&a=api&store_id='.$items['sid'].'&token='.$this->token.'&wecha_id={wechat_id}',
		'keyword'=>$items['keyword'],
		);
		
	}
}