<?php
class SeckillReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Seckill_action')->where(array('action_id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['reply_title'],$thisItem['reply_content'],$thisItem['reply_pic'],$this->siteUrl.U('Wap/Seckill/index',array('id'=>$thisItem['action_id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}
?>