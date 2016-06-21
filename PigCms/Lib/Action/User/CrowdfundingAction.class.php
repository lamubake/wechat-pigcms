<?php

class CrowdfundingAction extends UserAction{
	public $basis_db;
	public $reward_db;

	public function _initialize(){
		parent::_initialize();
		$this->canUseFunction("Crowdfunding");
		/*$checkFunc=new checkFunc();if (!function_exists('fdsrejsie3qklwewerzdagf4ds')){exit('error-4');}
        $checkFunc->cfdwdgfds3skgfds3szsd3idsj();*/

		$this->basis_db 	= D('Crowdfunding');
		$this->reward_db 	= D('Crowdfunding_reward');
	}


	public function index(){
		$search     = $this->_post('search','trim');
        $where      = array('token'=>$this->token);
        if($search){
            $where['name|keyword']  = array('like','%'.$search.'%');
        }

        $count      = $this->basis_db->where($where)->count();
        $Page       = new Page($count,15);
        $list       = $this->basis_db->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        foreach ($list as $key => $val) {
        	$where 	= array('token'=>$this->token,'id'=>$val['id']);
			$info 	= M('Crowdfunding')->where($where)->find();

			$info['end'] 	= strtotime('+'.$info['day'].' days',$info['start']);

			$info['price_count'] 	= $this->getOrderCount($info['id'],'',1);
			$info['people_count'] 	= $this->getOrderCount($info['id']);

			$remain_day = intval(($info['end'] - time())/(60*60*24));
			$info['remain_day'] 		= $remain_day<0?0:$remain_day;

			if($info['max'] != 0){
				$info['fund'] 	= $info['fund']*($info['max']/100);
			}
			$progress 	= $this->percent($info['price_count'],$info['fund']);

			$info['progress'] 		= $progress;
			$info['percent'] 		= ($info['price_count']/$info['fund'])>1?'100%':$progress;

			$list[$key] 	= array_merge($val,$info);
        }

        $this->assign('list',$list);
        $this->assign('page',$Page->show());
		$this->display();
	}

	//$type  人总数  钱总数
	public function getOrderCount($pid='',$reward_id='',$type=''){
		$where 	= array('token'=>$this->token,'paid'=>'1');
		if($pid != ''){
			$where['pid'] 	= $pid;
		}
		if($reward_id != ''){
			$where['reward_id'] 	= $reward_id;
		}
		$count = 0;
		if(empty($type)){
			$people = M('Crowdfunding_order')->where($where)->count();
			if($people){
				$count = $people;
			}
		}else{
			$price 	= M('Crowdfunding_order')->where($where)->sum('price');
			if($price){
				$count = $price;
			}
		}
		
		return $count;
	}

	/*百分比计算函数
	*$p 被除数
	*$t 总个数
	*/
	function percent($p,$t){
		if($t==0){   //因为被除数为0会报警告错误，因此为0的时候默认为1 
			$val = 1;
		}else{
			$val = $p/$t;
		}
		$num = sprintf('%.2f%%',$val*100);
		return $num;
	}

	public function order(){
		$pid 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'pid'=>$pid);

		$search     = $this->_post('search','trim');
        if($search){
            $where['orderid']  = array('like','%'.$search.'%');
        }

		$count  = M('Crowdfunding_order')->where($where)->count();
        $Page   = new Page($count,15);

        $order 	= M('Crowdfunding_order')->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('order',$order);
        $this->assign('page',$Page->show());       
		$this->display();
	}

	public function order_detail(){
		$id 	= $this->_get('id','intval');

		$order 	= M('Crowdfunding_order')->where(array('token'=>$this->token,'id'=>$id))->find();

		$reward = M('Crowdfunding_reward')->where(array('token'=>$this->token,'id'=>$order['reward_id']))->find();
		if($reward){
			$reward['name'] 	= M('Crowdfunding')->where(array('token'=>$this->token,'id'=>$reward['pid']))->getField('name');
		}
		$this->assign('order',$order);
		$this->assign('reward',$reward);

		$this->display();
	}

	public function order_del(){
		$id 	= $this->_get('id','intval');
		$pid 	= $this->_get('pid','intval');
		$where 	= array('token'=>$this->token,'id'=>$id);
		if(M('Crowdfunding_order')->where($where)->delete()){
			$this->success('删除成功',U('Crowdfunding/order',array('token'=>$this->token,'id'=>$pid)));
		}
	}

	public function basis_set(){
		$id 	= $this->_get('id','intval');
		$info 	= $this->basis_db->where(array('token'=>$this->token,'id'=>$id))->find();
		if(IS_POST){
			$_POST['token']		= $this->token;
			if($this->basis_db->create()){  
                if($info){
                	if($this->ck_status($info['id'])){
	                	$save_where 	= array('token'=>$this->token,'id'=>$this->_post('id','intval'));
	                	$this->basis_db->where($save_where)->save($_POST);	           
	                	$this->handleKeyword($this->_post('id','intval'),'Crowdfunding',$this->_post('keyword','trim'));
	                    //$this->redirect('Crowdfunding/reward_set', array('token'=>$this->token,'pid' => $id));
	                    $this->success('修改成功');
                	}else{
                		$this->error('项目已结束，无法修改');
                	}
                }else{
                	$id 	= $this->basis_db->add($_POST);
                	if($id){
                		$this->ck_status($id,1);
                		$this->handleKeyword($id,'Crowdfunding',$this->_post('keyword','trim'));
                		$this->success('项目信息设置完成',U('Crowdfunding/basis_set', array('token'=>$this->token,'id' => $id)));
                	}
                    //$this->success('添加成功',U('Crowdfunding/index',array('token'=>$this->token)));
                }
            }else{
            	$this->error($this->basis_db->getError());
            }

		}else{
			$this->assign('set',$info);
			$this->display();
		}

	}

	public function reward_set(){
		$id 	= $this->_get('id','intval');
		$pid 	= $this->_get('pid','intval');
		if(empty($pid)){
			$this->error('请先保存项目信息');
		}

		$reward_list 	= $this->reward_db->where(array('token'=>$this->token,'pid'=>$pid))->order('id desc')->select();
		$reward_info 	= $this->reward_db->where(array('token'=>$this->token,'pid'=>$pid,'id'=>$id))->find();
		
		if (IS_POST) {
			$_POST['pid'] 	= $pid;
			$_POST['token'] = $this->token;
			if($this->reward_db->create()){
                if($reward_info){
                	if($this->ck_status($pid)){
	                	$save 	= array('token'=>$this->token,'pid'=>$this->_post('pid','intval'),'id'=>$this->_post('id','intval'));
	                	$this->reward_db->where($save)->save($_POST);
	                	$this->redirect('Crowdfunding/reward_set', array('token'=>$this->token,'pid' => $this->_post('pid','intval')));
                	}else{
                		$this->success('项目已结束，修改失败',U('Crowdfunding/reward_set',array('token'=>$this->token,'pid' => $this->_post('pid','intval'))));
                	}
               	}else{
               		$rid 	= $this->reward_db->add($_POST);
               		if($rid && empty($reward_list)){
               			$this->ck_status($this->_post('pid','intval'),2);
               		}
               		$this->redirect('Crowdfunding/reward_set', array('token'=>$this->token,'pid' => $this->_post('pid','intval')));
               	}
            }else{
            	$this->error($this->reward_db->getError());
            }
		}else{

			$this->assign('set',$reward_info);
			$this->assign('reward_list',$reward_list);
			$this->assign('pid',$pid);
			$this->display();
		}
	}

	public function reward_del(){
		$pid 	= $this->_get('pid','intval');
		$id 	= $this->_get('id','intval');

		$where 	= array('token'=>$this->token,'pid'=>$pid,'id'=>$id);

		if($this->reward_db->where($where)->delete()){
			$this->redirect('Crowdfunding/reward_set', array('token'=>$this->token,'pid' => $pid));
		}
	}

	public function affirm(){
		$pid 	= $this->_get('pid','intval');
		if(empty($pid)){
			$this->error('参数错误');
		}
		$where 	= array('token'=>$this->token,'id'=>$pid);
		$status = $this->basis_db->where($where)->getField('status');

		if($_POST){
			if($status == 3){
				$this->error('项目已发布过，无法重复发布');
				exit;
			}else if($status == 4){
				$this->error('项目已经结束，发布无效');
				exit;
			}else {
				if($this->basis_db->where(array('token'=>$this->token,'id'=>$this->_post('pid','intval'),'status'=>array('eq',2)))->save(array('start'=>time(),'status'=>3))){
					$this->success('提交成功',U('Crowdfunding/index',array('token'=>$this->token)));
				}else{
					$this->error('请设置完整信息再发布');
				}
				
				
			}
		}else{
			$this->assign('status',$status);
			$this->assign('pid',$pid);
			$this->display();
		}

	}


	public function ck_status($id,$status=''){
		$where 	= array('token'=>$this->token,'id'=>$id);
		$stat 	= $this->basis_db->where($where)->getfield('status');

		if($status != ''){
			if($this->basis_db->where($where)->save(array('status'=>$status))) {
				return true;
			}else{
				return false;
			}
		}else{
			if($stat == 4){
				return false;
			}else{
				return true;
			}
		}
	}


	public function del($id){
		$id 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'id'=>$id);

		if($this->basis_db->where($where)->delete()){
			$this->reward_db->where(array('token'=>$this->token,'pid'=>$id))->delete();
			$this->success('删除成功',U('Crowdfunding/index',array('token'=>$this->token)));
		}
	}

}



?>