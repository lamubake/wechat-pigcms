<?php
class Greeting_cardReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Greeting_card')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){

		$thisItem=$this->item;
		return array(array(array($thisItem['title'],str_replace(array('&nbsp;','br /','&amp;','gt;','lt;'),'',strip_tags(htmlspecialchars_decode($Vote['info']))),$thisItem['picurl'],$this->siteUrl.U('Wap/Greeting_card/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
	
}
?>

