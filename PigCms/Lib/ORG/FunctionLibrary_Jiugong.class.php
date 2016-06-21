<?php
class FunctionLibrary_Jiugong{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'九宫格',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('Lottery');
			$where	=array('token'=>$this->token,'type'=>10);
			
/*			$count  = $db->where($where)->count();
			$Page   = new Page($count,5);
			$show   = $Page->show();
			$list 	= $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();*/
			$items 	= $db->where($where)->order('id DESC')->select();

			$arr=array(
			'name'=>'九宫格',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['title'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['title'],'link'=>'{siteUrl}/index.php?g=Wap&m=Jiugong&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}