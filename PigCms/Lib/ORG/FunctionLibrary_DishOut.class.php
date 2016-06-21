<?php
class FunctionLibrary_DishOut{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'微外卖',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('Dishout_manage');
			$cid=session('companyid');
			$cid=intval($cid);
			if(!($cid>0)){
			   $company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			   $cid=$company['id'];
			   unset($company);
			}
			$where=array('token'=>$this->token);
			$items 	= $db->where($where)->order('id DESC')->select();

			$arr=array(
			'name'=>'微外卖',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				$v=$items['0'];
				//foreach ($items as $v){
				   $arr['subkeywords'][$v['id']]=array('name'=>'微外卖','keyword'=>$v['keyword']);
				   $arr['sublinks'][$v['id']]=array('name'=>'微外卖','link'=>'{siteUrl}/index.php?g=Wap&m=DishOut&a=index&token='.$this->token.'&cid='.$cid.'&wecha_id={wechat_id}');
				//}
			}
			return $arr;	
		}
	}
}