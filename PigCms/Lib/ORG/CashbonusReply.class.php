<?php
class CashbonusReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Cashbonus')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],$thisItem['desc'],$thisItem['msg_pic'],$this->siteUrl.U('Wap/Cashbonus/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}

?>

