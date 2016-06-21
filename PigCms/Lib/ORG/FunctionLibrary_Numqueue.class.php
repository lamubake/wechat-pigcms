<?php
class FunctionLibrary_Numqueue{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'微排号',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('numqueue_action');
			$where	=array('token'=>$this->token);
			$items 	= $db->where($where)->order('id DESC')->select();

			$arr=array(
			'name'=>'微排号',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['reply_title'],'keyword'=>$v['reply_keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['reply_title'],'link'=>'{siteUrl}/index.php?g=Wap&m=Numqueue&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}