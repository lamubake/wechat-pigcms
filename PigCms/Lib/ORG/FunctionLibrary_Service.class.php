<?php
class FunctionLibrary_Service{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		return array(
		'name'=>'微客服',
		'subkeywords'=>0,
		'sublinks'=>0,
		'link'=>'{siteUrl}/index.php?g=Wap&m=Service&a=index&token='.$this->token.'&wecha_id={wechat_id}',
		'keyword'=>'微客服'
		);
		
	}
}