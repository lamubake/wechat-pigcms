<?php
class FunctionLibrary_Unitary{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'一元购',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('Unitary');
			$where	=array('token'=>$this->token);
			
/*			$count  = $db->where($where)->count();
			$Page   = new Page($count,5);
			$show   = $Page->show();
			$list 	= $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();*/
			$items 	= $db->where($where)->order('addtime DESC')->select();

			$arr=array(
			'name'=>'一元购',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['name'],'link'=>'{siteUrl}/index.php?g=Wap&m=Unitary&a=goodswhere&token='.$this->token.'&wecha_id={wechat_id}&unitaryid='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}