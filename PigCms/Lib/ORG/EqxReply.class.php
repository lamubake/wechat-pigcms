<?php
class EqxReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('eqx_info')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		//return array(array(array($thisItem['title'],'',$thisItem['pic'],$this->siteUrl.U('Wap/Popularity/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	//$thisItem=$this->item;
		//$thisItem['url'] = str_replace('{changjingUrl}','http://www.weihubao.com',html_entity_decode($thisItem['url']));
		return array(array(array($thisItem['title'],$thisItem['info'],$thisItem['picurl'],$thisItem['url'])),'news');
	}
	
}
?>

