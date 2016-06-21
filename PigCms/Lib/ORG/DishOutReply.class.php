<?php
class DishOutReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Dishout_manage')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array(
			htmlspecialchars_decode($thisItem['rtitle'],ENT_QUOTES),
			htmlspecialchars_decode($thisItem['rinfo'],ENT_QUOTES),
			htmlspecialchars_decode($thisItem['picurl'],ENT_QUOTES),
			$this->siteUrl.U('Wap/DishOut/index',array('token'=>$this->token,'wecha_id'=>$this->wechat_id))
			)),'news');
	}
}
?>