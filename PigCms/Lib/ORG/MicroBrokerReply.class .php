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
		//������ʽ return array(array(arr1,arr2,arr3),'news');//���10��
		//ÿ�������ʽarr �� ���⣨Title����������Description����ͼƬ��ַ��PicUrl������ת���ӣ�Url��
		return array(array(array($thisItem['title'],$thisItem['title'],$thisItem['imgreply'],$this->siteUrl.U('Wap/MicroBroker/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}
?>