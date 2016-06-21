<?php
class FunctionLibrary_Auction{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'微拍卖',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('auction');
			$where	=array('token'=>$this->token,'is_del'=>0);
			$items 	= $db->where($where)->select();
			$arr=array(
			'name'=>'微拍卖',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['name'],'link'=>'{siteUrl}/index.php?g=Wap&m=Auction&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}