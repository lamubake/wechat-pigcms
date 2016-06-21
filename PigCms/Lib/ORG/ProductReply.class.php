<?php
class ProductReply {	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl) {
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
		$like['keyword']=$data['keyword'];
		$like['precisions']=1;
		$like['token']=$this->token;
		/* //暂时不使用模糊查询
		$keywords=M('keyword')->where($like)->order('id desc')->find();
		if (!$data){
			$like['keyword']=array('like','%'.$data['keyword'].'%');
			$like['precisions']=0;
		}
		*/
		$this->item=M('Product')->where($like)->limit(9)->order('id DESC')->select();
	}
	public function index() {
		$thisItems=$this->item;
		foreach ($thisItems as $item) {
			if (!$item['groupon']){
				$url = $this->siteUrl.U('Wap/Store/product',array('id'=>$item['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id));
			}else {
				$url = $this->siteUrl.U('Wap/Groupon/product',array('id'=>$item['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id));
			}
			$return[] = array($item['name'],'',$item['logourl'],$url);
		}
		//$return[]=array($thisItem['name'],$this->handleIntro(strip_tags(htmlspecialchars_decode($thisItem['intro']))),$thisItem['logourl'],$url);
			
		return array($return, 'news');
	}
}
?>

