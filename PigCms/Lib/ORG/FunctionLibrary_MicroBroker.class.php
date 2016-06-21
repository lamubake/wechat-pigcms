<?php
class FunctionLibrary_MicroBroker{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'微经纪人',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('Broker');
			$where	=array('token'=>$this->token,'isdel'=>0);
			
			$items 	= $db->where($where)->order('id DESC')->select();

			$arr=array(
			'name'=>'微经纪人',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['title'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['title'],'link'=>'{siteUrl}/index.php?g=Wap&m=MicroBroker&a=index&token='.$this->token.'&bid='.$v['id'].'&wecha_id={wechat_id}');
				}
			}
			return $arr;	
		}
	}
}