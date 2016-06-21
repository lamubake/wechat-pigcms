<?php
class CarsetReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Carset')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],$thisItem['title'],$thisItem['head_url'],$this->siteUrl.U('Wap/Car/index',array('token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}
?>

