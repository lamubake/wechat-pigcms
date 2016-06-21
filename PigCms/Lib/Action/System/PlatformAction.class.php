<?php
class PlatformAction extends BackAction{
	public function index(){
		if(IS_POST){
			$where = '`p`.`token`=`w`.`token` AND `w`.`uid`=`u`.`id`';
			if($_POST['name']){
				switch($_POST['type']){
					case '1':
						$where .= ' AND `w`.`wxname` LIKE ('."'".'%'.$_POST['name'].'%'."'".')';
						break;
					case '2':
						$where .= ' AND `w`.`id`='.$_POST['name'];
						break;
					case '3':
						$where .= ' AND `w`.`qq` LIKE ('."'".'%'.$_POST['name'].'%'."'".')';
						break;
					case '4':
						$where .= ' AND `p`.`orderid`='."'".$_POST['name']."'";
						break;
				}
			}
			if($_POST['paid']){
				$where .= ' AND `p`.`paid`='.($_POST['paid']-1);
			}
			if($_POST['order']){
				$order = '`p`.`time` ASC';
			}else{
				$order = '`p`.`time` DESC';	
			}
			
			$platform_list = D()->Table(array(C('DB_PREFIX').'platform_pay'=>'p',C('DB_PREFIX').'wxuser'=>'w',C('DB_PREFIX').'users'=>'u'))->where($where)->order($order)->select();
			$this->assign('page','搜索不提供分页，共 '.count($platform_list).' 行数据');
		}else{
			$database_platform_pay = D('Platform_pay');
			$count = $database_platform_pay->count();
			$page=new Page($count,25);
			$platform_list = D()->Table(array(C('DB_PREFIX').'platform_pay'=>'p',C('DB_PREFIX').'wxuser'=>'w',C('DB_PREFIX').'users'=>'u'))->where("`p`.`token`=`w`.`token` AND `w`.`uid`=`u`.`id`")->order('`p`.`time` DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
		}

		$platform_count = array();
		if(is_array($platform_list)){
			foreach($platform_list as &$value){
				$from = strtolower($value['from']);
				switch($from){
					case 'repast':
						$value['from'] = '餐饮';
						break;
					case 'store':
						$value['from'] = '商城';
						break;
					case 'hotels':
						$value['from'] = '酒店';
						break;
					case 'business':
						$value['from'] = '商业';
						break;
					case 'groupon':
						$value['from'] = '团购';
						break;
					case 'dishout':
						$value['from'] = '外卖';
						break;
					case 'business':
						$value['from'] = '行业应用';
						break;
					case 'card':
						$value['from'] = '会员卡充值';
						break;
					case 'medical':
						$value['from'] = '微医疗';
						break;
					case 'unitary':
						$value['from'] = '一元购';
						break;
					case 'livingcircle':
						$value['from'] = '生活圈';
						break;
					case 'bargain':
						$value['from'] = '砍价';
						break;
					case 'crowdfunding':
						$value['from'] = '众筹';
						break;
					case 'seckill' :
						$value['from'] = '秒杀';
						break;
					case 'micrstore' :
						$value['from'] = '微店';
						break;
					case 'drppayment':
						$value['from'] = '商城分销';
						break;
					case 'cutprice':
						$value['from'] = '降价拍';
						break;
				}
				$platform_count['all'] += $value['price'];
				if($value['paid']){
					$platform_count['ok'] += $value['price'];
				}else{
					$platform_count['none'] += $value['price'];
				}
			}
		}
		$this->assign('platform_list', $platform_list);
		$this->assign('platform_count', $platform_count);
		

		$this->display();
	}
	public function paid(){
		$database_platform_pay = D('Platform_pay');
		$data_platform_pay['platform_id'] = $this->_get('platform_id');
		$data_platform_pay['paid'] = $this->_get('paid');
		if($database_platform_pay->data($data_platform_pay)->save()){
			$this->success('处理完成！');
		}else{
			$this->error('处理失败！请重试。');
		}
	}
	public function paid_all(){
		$platform_id_arr = $_POST['platform_id'];
		if(!is_array($platform_id_arr)){
			$this->error('请选中一些订单！');
		}
		$database_platform_pay = D('Platform_pay');
		$condition_platform_pay['platform_id'] = array('in',implode(',',$platform_id_arr));
		$condition_platform_pay['paid'] = 0;
		if(!$database_platform_pay->where($condition_platform_pay)->find()){
			$this->error('您勾选的订单都已经处理过！');
		}
		$condition_save_platform_pay['platform_id'] = array('in',implode(',',$platform_id_arr));
		$data_platform_pay['paid'] = '1';
		if($database_platform_pay->where($condition_save_platform_pay)->data($data_platform_pay)->save()){
			$this->success('处理完成！');
		}else{
			$this->error('处理失败！请重试。');
		}
	}
	public function edit(){
		$where['id']=$this->_get('id','intval');
		$db=D('Links');
		$info=$db->where($where)->find();
		$this->assign('info',$info);
		$this->display('add');
	}
	
	public function add(){
		$this->display();
	}
	
	public function insert(){
		$this->all_insert('Links');
	}
	
	public function upsave(){
		$this->all_save('Links');
	}
	
	public function del(){
		$id=$this->_get('id','intval');
		$db=D('Links');
		if($db->delete($id)){
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	
}