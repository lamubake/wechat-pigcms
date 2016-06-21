<?php
/**
 *文本回复
**/
class AdcmsAction extends UserAction{
	protected function _initialize(){
		parent::_initialize();
		$this->canUseFunction("Adcms");
	}
	public function index(){
		
		$db=D('adcms_list');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		
		 $count=$db->where(array('token'=>$_GET['token']))->order('time ASC ')->count();
		  $page=new Page($count,10);	
		  $info=$db->where(array('token'=>$_GET['token']))->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->display();


	}
	public function flash(){
		
		$db=D('adcms_flash');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		
		 $count=$db->where(array('token'=>$_GET['token']))->order('time ASC ')->count();
		  $page=new Page($count,10);	
		  $info=$db->where(array('token'=>$_GET['token']))->limit($page->firstRow.','.$page->listRows)->select();	
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->display();


	}
	public function flash_add(){
		$data['token'] = $_GET['token'];
		$data['info'] = $_POST['info'];
        $data['time'] = time();
		$data['pic'] = $_POST['pic'];
		
		$data['url'] = $_POST['url'];
		
		if(IS_POST){
			$a = M('adcms_flash')->add($data);
			if($a){	
			
				$this->success('添加成功！',U('Adcms/flash',array('token'=>$this->token)));
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}


	}
	public function flash_set(){
			
		
		$data['info'] = $_POST['info'];
        
		$data['pic'] = $_POST['pic'];
		
		$data['url'] = $_POST['url'];
		$set=M('adcms_flash')->where(array('id'=>$_GET['id']))->find();
		$this->assign('info',$set);
		if(IS_POST){
			$a = M('adcms_flash')->where(array('id'=>$_GET['id']))->save($data);
			if($a){	
			
				$this->success('保存成功！',U('Adcms/flash',array('token'=>$this->token)));exit;
			}else{
				$this->error('保存失败！');exit;
			}
			
		
		
	}
	$this->display();
	}
	public function hezuo(){
		
		$db=D('adcms_hezuo');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		
		 $count=$db->where(array('token'=>$_GET['token']))->order('time ASC ')->count();
		  $page=new Page($count,10);	
		  $info=$db->where(array('token'=>$_GET['token']))->limit($page->firstRow.','.$page->listRows)->select();	
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->display();


	}
	public function peizhi(){
		$data['token'] = $_GET['token'];
		
		$data['wxname'] = $_POST['wxname'];
		$data['wxurl'] = $_POST['wxurl'];
		$data['sjtg'] = $_POST['sjtg'];
		$data['adurl'] = $_POST['adurl'];
		$data['xszd'] = $_POST['xszd'];
		$data['rmb'] = $_POST['rmb'];
		$data['yj'] = $_POST['yj'];
		$data['mbid'] = $_POST['mbid'];
		$data['quyu'] = $_POST['quyu'];
		$data['quyus'] = $_POST['quyus'];
		$data['quyux'] = $_POST['quyux'];
		$data['txtype'] = $_POST['txtype'];
		$data['xiaxianid'] = $_POST['xiaxianid'];
		$info=M('adcms_set')->where(array('token'=>$_GET['token']))->find();
		$this->assign('set',$info);	
		if(IS_POST){
			if($info){
				$a = M('adcms_set')->where(array('token'=>$_GET['token']))->save($data);
				if($a){	
			
				$this->success('保存成功！');exit;
			}else{
				$this->error('保存失败！');exit;
			}
				
				
				
				}else{
			$a = M('adcms_set')->add($data);}
			if($a){	
			
				$this->success('添加成功！');exit;
			}else{
				$this->error('添加失败！');exit;
			}
		}
		
		
			$this->display();
		
		
	}


	
	public function news_lists(){
		
		$db=D('adcms_news');
		$where['token']=$_GET['token'];
		$count=$db->where($where)->count();
		
		 $count=$db->where(array('token'=>$_GET['token']))->order('time desc ')->count();
		  $page=new Page($count,10);	
		  $info=$db->where(array('token'=>$_GET['token']))->limit($page->firstRow.','.$page->listRows)->order('time desc ')->select();	
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		
		$this->display();


	}
	public function user(){
		$db=D('adcms_userinfo');
		$where['token']=$_GET['token'];
		$count=$db->where($where)->count();
		sprintf("%.2f", $num);  
		 $count=$db->where(array('token'=>$_GET['token']))->count();
		  $sum=$db->where(array('token'=>$_GET['token']))->sum('total_balance');
		   $sum=sprintf("%.2f", $sum); 
		   $sumye=$db->where(array('token'=>$_GET['token']))->sum('balance');
		   $sumye=sprintf("%.2f", $sumye); 
		  $page=new Page($count,10);	
		  $lists=$db->where(array('token'=>$_GET['token']))->limit($page->firstRow.','.$page->listRows)->order('time desc ')->select();
		  // $lists=$db->where(array('token'=>$_GET['token']))->select();
		   foreach ($lists as $key => $val) {
			$user_info = M('adcms_userinfo')->where(array('token'=>$_GET['token'],'invite1'=>$val['wecha_id']))->count();

			$lists[$key]['fans'] 	= $user_info?$user_info:'无';;
			
		} 
		//dump($lists);exit;
		  
		$this->assign('info',$lists);
		$this->assign('page',$page->show());
		$this->assign('count',$count);
		$this->assign('sum',$sum);
		$this->assign('sumye',$sumye);
		
		$this->display();
	}
	public function exchange(){
		$db=D('adcms_record');
		$where['token']=$_GET['token'];
		$where['wecha_id']=$_GET['wecha_id'];
		$count=$db->where($where)->count();
		
		 $count=$db->where(array('token'=>$_GET['token']))->count();
		 
		  $page=new Page($count,10);	
		  $info=$db->where(array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id']))->limit($page->firstRow.','.$page->listRows)->order('time desc ')->select();
		   $infos=M('adcms_userinfo')->where(array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id']))->find();	
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->assign('infos',$infos);
		
		
		$this->display();
	}
	public function user_fans(){
		$db=D('adcms_userinfo');
		$where['token']=$_GET['token'];
		$where['invite1']=$_GET['wecha_id'];
		$count=$db->where($where)->count();
		
		
		  $page=new Page($count,10);	
		  $info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('time desc ')->select();
		
		  $father=$db->where(array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id']))->find();
		  
		$this->assign('info',$info);
		$this->assign('father',$father);
		$this->assign('page',$page->show());
		
		$this->display();
	}
	public function tixian(){
		$db=D('adcms_record');
		$where['token']=$_GET['token'];
		$where['statue']==0;
		
		$count=$db->where($where)->count();
		
		 $count=$db->where(array('token'=>$_GET['token']))->count();
		  $page=new Page($count,10);	
		  $info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('time desc ')->select();
		  $sqtx=$db->where($where)->count();
		   $ytx=$db->where(array('token'=>$_GET['token'],'statue'=>'1'))->count();
		   $dcl=$sqtx- $ytx;
		  $zje=$db->where($where)->sum('alipay_money');
		  $ztxje=$db->where(array('token'=>$_GET['token'],'statue'=>'1'))->sum('alipay_money');
		  $zdtxje=$zje-$ztxje;
		  
		$this->assign('info',$info);
		$this->assign('sqtx',$sqtx);
		$this->assign('ytx',$ytx);
		$this->assign('info',$info);
		$this->assign('zje',$zje);
		$this->assign('ztxje',$ztxje);
		$this->assign('zdtxje',$zdtxje);
		
		$this->assign('page',$page->show());
		
		$this->display();
	}
	public function tixian_ok(){
		$db=D('adcms_record');
		$where['token']=$_GET['token'];
		$where['id']=$_GET['id'];
		$time=time();
		$access_token = $this->getAccessToken();
		
		
		$set=M('Adcms_set')->where(array('token'=>$_GET['token']))->find();
		$user=M('adcms_userinfo')->where(array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id']))->find();
		$infos=$db->where(array('token'=>$_GET['token'],'id'=>$_GET['id']))->find();
		
		$date['statue']=1;
		  $info=$db->where($where)->save($date);
		  if($info){
			  $txt='{"touser":"'.$_GET['wecha_id'].'","template_id":"'.$set['mbid'].'","url":"'.C('site_url').'/index.php?g=Wap&m=Adcms&a=index&token='.$_GET['token'].'","topcolor":"#FF0033","data":{"first": {"value":"'.$user['name'].'，您好。您的提现申请已处理。","color":"#173177"},"keyword1": {"value":"'.$infos['alipay_money'].'元","color":"#FF0033"},"keyword2": {"value":"支付宝提现","color":"#FF0033"},"keyword3": {"value":"'.$infos['time'] = date("Y年m月d日 H:i:s", $infos['time']).'","color":"#FF0033"},"keyword4": {"value":"审核通过","color":"#FF0033"},"keyword5": {"value":"'.$time = date("Y年m月d日 H:i:s",$time).'","color":"#FF0033"},"remark": {"value":"请留意您的支付宝，有任何疑问，请致电客服。","color":"#173177"}}}';
			
			  
	
	
	$url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token.'';
	
	$result=$this->https_post($url,$txt);
	
	
			  $this->success('打款成功！');
			  }else{
				 $this->error('操作失败！'); 
				  
				  }
		 
		  
		
	}
	public function news_add(){
		$data['token'] = $_GET['token'];
		$data['title'] = $_POST['title'];
        $data['time'] = time();
		
		$data['contents'] = $_POST['contents'];
		
		if(IS_POST){
			$a = M('adcms_news')->add($data);
			if($a){	
			
				$this->success('添加成功！',U('Adcms/news_lists',array('token'=>$this->token)));
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}


	}
	public function black(){
			
		
		$set=M('adcms_black')->where(array('token'=>$_GET['token']))->find();
		
		
			
		
		if(IS_POST){
			
			$date['wecha_id'] = $_POST['wecha_id'];
			$date['token'] = $this->token;
		
			if(empty($set)){
				
			$a=M('adcms_black')->add($date);	
				
				
				}else{
					
					$a=M('adcms_black')->where(array('token'=>$_GET['token']))->save($date);		
					}
		if($a){	
			
				$this->success('保存成功！',U('Adcms/black',array('token'=>$this->token)));exit;
			}else{
				$this->error('保存失败！');exit;
			}
	}
	
	$this->assign('set',$set);
	$this->display();
	}
	
	public function news_set(){
			
		$data['title'] = $_POST['title'];
       
		
		$data['contents'] = $_POST['contents'];
		$set=M('adcms_news')->where(array('id'=>$_GET['id']))->find();
		$this->assign('set',$set);
		if(IS_POST){
			$a = M('adcms_news')->where(array('id'=>$_GET['id']))->save($data);
			if($a){	
			
				$this->success('保存成功！');exit;
			}else{
				$this->error('保存失败！');exit;
			}
			
		
		
	}
	$this->display();
	}
	
	public function add(){
		
		$data['token'] = $_GET['token'];
		$data['title'] = $_POST['title'];
		$data['pic'] = $_POST['pic'];
        $data['time'] = time();
		$data['type'] = $_POST['type'];
		$data['adrmb'] = $_POST['adrmb'];
		$data['sjname'] = $_POST['sjname'];
		$data['gdadrmb'] = $_POST['adrmb'];
		$data['contents'] = $_POST['contents'];
		
		if(IS_POST){
			$a = M('adcms_list')->add($data);
			if($a){	
			
				$this->success('添加成功！',U('Adcms/index',array('token'=>$this->token)));
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}
	}
	public function set(){
		$info=M('adcms_list')->where(array('id'=>$_GET['id']))->find();
		$data['token'] = $_GET['token'];
		$data['title'] = $_POST['title'];
		$data['pic'] = $_POST['pic'];
		$data['adrmb'] = $_POST['adrmb'];
        $data['gdadrmb'] = $_POST['gdadrmb'];
		$data['sjname'] = $_POST['sjname'];
		$data['type'] = $_POST['type'];
		$data['contents'] = $_POST['contents'];
		
		$this->assign('info',$info);
		
		if(IS_POST){
			
			$a = M('adcms_list')->where(array('id'=>$_GET['id']))->save($data);
			if($a){	
			
				$this->success('保存成功！',U('Adcms/index',array('token'=>$this->token)));exit;
			}else{
				$this->error('保存失败！');exit;
			}
			
		
		
	}
	$this->display();
	}
	public function hezuo_do(){
		
		$date['statue']=1;
		$info=M('adcms_hezuo')->where(array('id'=>$_GET['id']))->save($date);
		if($info){
			$this->success('已标记受理！');exit;
		}else{
		
		}	$this->error('服务器异常，稍后再试！');exit;
	}
	public function del(){
		$info=M('adcms_list')->where(array('id'=>$_GET['id']))->delete();
		if($info){
			$this->success('删除成功！');exit;
		}else{
		
		}	$this->error('删除失败！');exit;
	}
	public function flash_del(){
		$info=M('adcms_flash')->where(array('id'=>$_GET['id']))->delete();
		if($info){
			$this->success('删除成功！');exit;
		}else{
		
		}	$this->error('删除失败！');exit;
	}
	public function hezuodel(){
		$info=M('adcms_hezuo')->where(array('id'=>$_GET['id']))->delete();
		if($info){
			$this->success('删除成功！');exit;
		}else{
		
		}	$this->error('删除失败！');exit;
	}
	public function user_del(){
		$infos=M('adcms_userinfo')->where(array('id'=>$_GET['id']))->find();
		$info=M('adcms_userinfo')->where(array('id'=>$_GET['id']))->delete();
		M('adcms_clickrecord')->where(array('wecha_id'=>$infos['wecha_id']))->delete();
		M('adcms_record')->where(array('wecha_id'=>$infos['wecha_id']))->delete();
		if($info){
			$this->success('删除成功！');exit;
		}else{
		$this->error('删除失败！');exit;
		}	
	}
	public function news_del(){
		$info=M('adcms_news')->where(array('id'=>$_GET['id']))->delete();
		if($info){
			$this->success('删除成功！');exit;
		}else{
		
		}	$this->error('删除失败！');exit;
	}
	public function reply(){
		$data['token'] = $_GET['token'];
		$data['title'] = $_POST['title'];
		$data['url'] = $_POST['url'];
		$data['pic1'] = $_POST['pic1'];
		$data['pic2'] = $_POST['pic2'];
		$data['pic3'] = $_POST['pic3'];
		$data['ad'] = $_POST['ad'];
		$data['qr'] = $_POST['qr'];
		$data['adinfo'] = $_POST['adinfo'];
		$data['adinfos'] = $_POST['adinfos'];
		$data['adurl'] = $_POST['adurl'];
		$data['banquan'] = $_POST['banquan'];
		$data['contents'] = $_POST['contents'];	
		$info=M('Nrreply')->where(array('token'=>$_GET['token']))->find();
		$this->assign('info',$info);	
		if(IS_POST){
			if($info){
				$a = M('Nrreply')->where(array('token'=>$_GET['token']))->save($data);
				if($a){	
			
				$this->success('保存成功！');exit;
			}else{
				$this->error('保存失败！');exit;
			}
				
				
				
				}else{
			$a = M('Nrreply')->add($data);}
			if($a){	
			
				$this->success('添加成功！');exit;
			}else{
				$this->error('添加失败！');exit;
			}
		}
		
		
			$this->display();
		
		
	}
	 function https_post($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				return array('rt'=>true,'errorno'=>0);
			}else {
				$errmsg=GetErrorMsg::wx_error_msg($js['errcode']);
				$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$errmsg);
			}
		}
	}   
	protected function getAccessToken() {
		 $info=M('wxuser')->where(array('token'=>$this->token))->find();
		 $this->_appid = $info['appid'];
		 $this->_secret =$info['appsecret'];	
		 $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_secret.'';
		 $res = file_get_contents($url);
		 $arr = json_decode($res, true);
		 $access_token = $arr['access_token'];
		 return $access_token;
	}	
	
	public function exportForms()
    {
        $where = array('token' =>$_GET['token']);
        $list = M('adcms_record')->where($where)->order('time desc')->select();
		
        $data = array();
        $title = array('用户名', '提现金额');
       $fields = array('申请提现时间','提现状态','提现方式',);
        $title = array_merge($title, $fields);
        foreach ($list as $key => $value) {
            $data[$key][] = $value['name'];
			$data[$key][] = $value['alipay_money'];
			$data[$key][] = $value['time'] = date("Y年m月d日 H:i:s",  $value['time']);
		if( $value['statue']==0){
			$value['statue']="等待打款";
			}else{
			$value['statue']="打款成功"	;
				
				}
		if( $value['type']==1){
			$value['type']="支付宝提现";
			}else{
			$value['type']="微信红包提现"	;
				
				}		
			$data[$key][] = $value['statue'];
			$data[$key][] = $value['type'];
			
          
        }
        $exname = '提现数据';
        $this->exportexcel($data, $title, $exname);
    } 
 public function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header('Content-type:application/octet-stream');
        header('Accept-Ranges:bytes');
        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename=' . $filename . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv('UTF-8', 'GB2312', $v);
            }
            $title = implode('	', $title);
            echo "{$title}\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv('UTF-8', 'GB2312', $cv);
                }
                $data[$key] = implode('	', $data[$key]);
            }
            echo implode('
', $data);
        }
    }
	
}


?>