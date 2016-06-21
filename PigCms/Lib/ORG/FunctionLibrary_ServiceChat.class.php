<?php
class FunctionLibrary_ServiceChat{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		return array(
		'name'=>'微聊天',
		'subkeywords'=>0,
		'sublinks'=>0,
		'link'=>'{siteUrl}/index.php?g=Wap&m=Service&a=chat&token='.$this->token.'&wecha_id={wechat_id}',
		'keyword'=>'微聊天'
		);
		
	}
}