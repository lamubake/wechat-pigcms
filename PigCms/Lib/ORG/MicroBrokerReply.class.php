<?php
class MicroBrokerReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Broker')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;

		return array(array(array($thisItem['title'],$thisItem['rinfo'],$thisItem['imgreply'],$this->siteUrl.U('Wap/MicroBroker/index',array('bid'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}
?>