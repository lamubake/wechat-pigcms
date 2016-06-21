<?php
class FunctionLibrary_Punish{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'惩罚台',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('Punish');
			$where	=array('token'=>$this->token);
			$items 	= $db->where($where)->select();

			$arr=array(
			'name'=>'惩罚台',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['name'],'link'=>'{siteUrl}/index.php?g=Wap&m=Punish&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}