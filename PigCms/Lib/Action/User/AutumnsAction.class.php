<?php
class AutumnsAction extends ActivityBaseAction{
	public $activityType;
	public $activityTypeName;
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Autumns');
		$this->activityType=1;
		$this->activityTypeName='拆礼盒';
		$this->assign('activityTypeName',$this->activityTypeName);
	}

	public function index(){
		parent::index($this->activityType);
		$this->display();
	
	}
	
	public function add(){
		parent::add($this->activityType);
	}
	
	public function edit(){
		parent::edit($this->activityType);
	}

	public function cheat(){
		parent::cheat($this->activityType);
		$this->display();
	}

	public function sn(){
		$lid=$this->_get('id','intval');
		$id=M('Lottery')->where(array('zjpic'=>$lid,'token'=>$this->token))->getField('id');
		$type=$this->_get('type','intval');
		$lottery=M('Activity')->where(array('token'=>$this->token,'id'=>$lid,'type'=>$type))->find();
		$this->assign('Activity',$lottery);

		$recordcount=$lottery['fistlucknums']+$lottery['secondlucknums']+$lottery['thirdlucknums']+$lottery['fourlucknums']+$lottery['fivelucknums']+$lottery['sixlucknums'];
		$datacount=$lottery['fistnums']+$lottery['secondnums']+$lottery['thirdnums']+$lottery['fournums']+$lottery['fivenums']+$lottery['sixnums'];
		$this->assign('datacount',$datacount);
		$this->assign('recordcount',$recordcount);
		
		$box=M('Autumns_box');
		
		$lcount = $box->where(array('token'=>$this->token,'bid'=>$id,'isprize'=>1))->count();
		$page 	= new Page($lcount,20);
		$this->assign('page',$page->show());

		$list = $box->where(array('token'=>$this->token,'bid'=>$id,'isprize'=>1))->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach($list as $key=>$val){
			$user =  M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->field('wechaname,tel')->find();
			$list[$key]['tel'] = $user['tel'];
			$list[$key]['name'] = $user['wechaname'];
		}
		$this->assign('list',$list);

		$send = $box->where(array('bid'=>$id,'isprize'=>1,'sendstutas'=>1))->count();
		$this->assign('send',$send);
		
		$count=$box->where(array('bid'=>$id,'isprize'=>1,'isprizes'=>1))->count();
		$this->assign('count',$count);

		$lottery=M('Activity')->where(array('id'=>$lid,'token'=>$this->token))->find();
		$this->assign('id',$id);
		$this->display();
	}

	public function sendprize(){
		$bid = $this->_get('id','intval');
		$id = $this->_get('bid','intval');

		$where=array('bid'=>$bid,'token'=>$this->token,'id'=>$id);
	
		$data['sntime']=time();
		$data['sendstutas']=1;
		$back=M('Autumns_box')->where($where)->save($data);

		if($back==true){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	public function sendnull(){
		$bid = $this->_get('id','intval');
		$id = $this->_get('bid','intval');
		$where=array('bid'=>$bid,'token'=>$this->token,'id'=>$id);
		$data['sntime']='';
		$data['sendstutas']=0;
		$back=M('Autumns_box')->where($where)->save($data);
		if($back==true){
			$this->success('已经取消');
		}else{
			$this->error('操作失败');
		}
	}

	public function sndelete(){
		$bid = $this->_get('id','intval');
		$id = $this->_get('bid','intval');
		$box=M('Autumns_box');
		$aid=M('Lottery')->where(array('token'=>$this->token,'id'=>$bid))->getField('zjpic');
		$lv=$box->where(array('id'=>$id,'token'=>$this->token))->getField('lvprize');
		$where=array('id'=>$id,'bid'=>$bid,'token'=>$this->token);
		$box->where($where)->delete();
		switch($lv){
			case 一等奖:
				$cont=M('Activity')->where(array('token'=>$this->token,'id'=>$aid))->setDec('fistlucknums');
			break;
			case 二等奖:
				$cont=M('Activity')->where(array('token'=>$this->token,'id'=>$aid))->setDec('secondlucknums');
			break;
			case 三等奖:
				$cont=M('Activity')->where(array('token'=>$this->token,'id'=>$aid))->setDec('thirdlucknums');
			break;
			case 四等奖:
				$cont=M('Activity')->where(array('token'=>$this->token,'id'=>$aid))->setDec('fourlucknums');
			break;
			case 五等奖:
				$cont=M('Activity')->where(array('token'=>$this->token,'id'=>$aid))->setDec('fivelucknums');
			break;
			case 六等奖:
				$cont=M('Activity')->where(array('token'=>$this->token,'id'=>$aid))->setDec('sixlucknums');
			break;
		}
		$this->success('操作成功');
	}

	public function endLottery(){
		parent::endLottery($this->activityType);
	}

	public function startLottery(){
		parent::startLottery($this->activityType);
	}

	public function localUploadSNExcel(){
		$return=$this->localUpload(array('xls'));
		if ($return['error']){
			$this->error($return['msg']);
		}else {
			$data = new Spreadsheet_Excel_Reader();
			// 设置输入编码 UTF-8/GB2312/CP936等等
			$data->setOutputEncoding('UTF-8');
			$data->read(str_replace('http://'.$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$return['msg']));
			chmod(str_replace('http://'.$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$return['msg']),0777);
			//
			$sheet=$data->sheets[0];
			$rows=$sheet['cells'];
			if ($rows){
				$i=0;
				foreach ($rows as $r){
					if ($i!=0){
						$db=M('Autumns_box');
						$where=array('token'=>$this->token,'bid'=>intval($_POST['lid']),'sn'=>trim($r[1]),'prize'=>trim($r[2]),'lvprize'=>trim($r[3]),'name'=>$r[4]);
						$check=$db->where(array('token'=>$this->token,'bid'=>intval($_POST['lid']),'sn'=>trim($r[1])))->find();
						$dbs=M('Userinfo');

						$list=$dbs->where(array('wechaname'=>$r[4],'token'=>$this->token,'tel'=>trim($r[5])))->find();
						if(!$list){
							$this->error('中奖的用户必须已经关注公众号');
						}
						if (!$check){
							$where['isprize']='1';
							$where['isprizes']='1';
							$where['prtime']=time();
							$where['box']='1';
							$where['wecha_id']=$list['wecha_id'];
							$db->add($where);
						}
					}
					$i++;
				}
			}
			$this->success('操作完成');
		}
	}
	function localUpload($filetypes=''){
		$upload = new UploadFile();
		$upload->maxSize  = intval(C('up_size'))*1024 ;
		if (!$filetypes){
			$upload->allowExts  = explode(',',C('up_exts'));
		}else {
			$upload->allowExts  = $filetypes;
		}
		$upload->autoSub=1;
		if (isset($_POST['width'])&&intval($_POST['width'])){
			$upload->thumb = true;
			$upload->thumbMaxWidth=$_POST['width'];
			$upload->thumbMaxHeight=$_POST['height'];
			//$upload->thumbPrefix='';
			$thumb=1;
		}
		$upload->thumbRemoveOrigin=true;
		//
		$firstLetter=substr($this->token,0,1);
		$upload->savePath =  './uploads/'.$firstLetter.'/'.$this->token.'/';// 设置附件上传目录
		//
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads')||!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads')){
			mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads',0777);
		}
		$firstLetterDir=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$firstLetter;
		if (!file_exists($firstLetterDir)||!is_dir($firstLetterDir)){
			mkdir($firstLetterDir,0777);
		}
		if (!file_exists($firstLetterDir.'/'.$this->token)||!is_dir($firstLetterDir.'/'.$this->token)){
			mkdir($firstLetterDir.'/'.$this->token,0777);
		}
		//
		$upload->hashLevel=4;
		if(!$upload->upload()) {// 上传错误提示错误信息
			$error=1;
			$msg=$upload->getErrorMsg();
		}else{// 上传成功 获取上传文件信息
			$error=0;
			$info =  $upload->getUploadFileInfo();
			$this->siteUrl=$this->siteUrl?$this->siteUrl:C('site_url');
			if ($thumb==1){
				$paths=explode('/',$info[0]['savename']);
				$fileName=$paths[count($paths)-1];
				$msg=$this->siteUrl.substr($upload->savePath,1).str_replace($fileName,'thumb_'.$fileName,$info[0]['savename']);
			}else {
				$msg=$this->siteUrl.substr($upload->savePath,1).$info[0]['savename'];
			}
			M('Users')->where(array('id'=>$this->user['id']))->setInc('attachmentsize',intval($info[0]['size']));
			$Files = new Files();
			$Files->index($msg,intval($info[0]['size']),$info[0]['extension'],$this->user['id'],$this->token);
			//M('Files')->add(array('token'=>$this->token,'size'=>intval($info[0]['size']),'time'=>time(),'type'=>$info[0]['extension'],'url'=>$msg));
		}
		
		if($this->_get('imgfrom') == 'photo_list'){
			echo $msg;exit;
		}else{
			return array('error'=>$error,'msg'=>$msg);
		}
		
	}
}

?>