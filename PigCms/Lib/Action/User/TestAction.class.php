<?php
class TestAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		/*$checkFunc=new checkFunc();if (!function_exists('fdsrejsie3qklwewerzdagf4ds')){exit('error-4');}
        $checkFunc->cfdwdgfds3skgfds3szsd3idsj();*/
		$this->canUseFunction("Test");
		
		//加载数据库
		$this->m_test = M("test");
		$this->m_user = M("test_user");
	}
	//趣味测试列表页
	public function index(){
		$where_test['token'] = $this->token;
		$where_page['token'] = $this->token;
		if(!empty($_GET['name'])){
			$where_test['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_test->where($where_test)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$test_list = $this->m_test->where($where_test)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign("test_list",$test_list);
		$this->display();
	}
	//趣味测试添加页
	public function add(){
		
		$this->display();
	}
	//趣味测试执行添加
	public function doadd(){
		if(IS_POST){
			$_POST['token'] = $this->token;
			$_POST['addtime'] = time();
			if($this->m_test->create()!=false){
				if($id=$this->m_test->add()){
					$this->handleKeyword($id,'Test',$_POST['keyword'],0,0);
					$this->success("活动创建成功",U('Test/index',array('token'=>$this->token)));
				}else{
					$this->error('服务器繁忙,请稍候再试');
				}
			}else{
				$this->error($this->m_test->getError());
			}
		}else{
			$this->error("操作失败");
		}
	}
	//趣味测试修改
	public function update(){
		$where_test['token'] = $this->token;
		$where_test['imicms_id'] = (int)($_GET['id']);
		$test = $this->m_test->where($where_test)->find();
		$this->assign("test",$test);
		$this->display();
	}
	//趣味测试执行修改
	public function doupdate(){
		if(IS_POST){
			$where['imicms_id'] = $_POST['imicms_id'];
			$where['token'] = $this->token;
			if($this->m_test->create()!=false){
				$update=$this->m_test->where($where)->save();
				$id = $_POST['imicms_id'];
				$this->handleKeyword($id,'Test',$_POST['keyword'],0,0);
				$this->success("活动修改成功",U('Test/index',array('token'=>$this->token)));
			}else{
				$this->error($this->m_test->getError());
			}
		}else{
			$this->error("操作失败");
		}
	}
	//趣味测试选项数据
	public function data(){
		$where_test['token'] = $this->token;
		$where_test['imicms_id'] = (int)($_GET['id']);
		$test = $this->m_test->where($where_test)->find();
		$this->assign("test",$test);
		$where_data1['token'] = $this->token;
		$where_data1['testid'] = (int)($_GET['id']);
		$where_data1['testtype'] = 1;
		$count[1] = $this->m_user->where($where_data1)->count();
		$where_data2['token'] = $this->token;
		$where_data2['testid'] = (int)($_GET['id']);
		$where_data2['testtype'] = 2;
		$count[2] = $this->m_user->where($where_data2)->count();
		$where_data3['token'] = $this->token;
		$where_data3['testid'] = (int)($_GET['id']);
		$where_data3['testtype'] = 3;
		$count[3] = $this->m_user->where($where_data3)->count();
		$where_data4['token'] = $this->token;
		$where_data4['testid'] = (int)($_GET['id']);
		$where_data4['testtype'] = 4;
		$count[4] = $this->m_user->where($where_data4)->count();
		$where_data5['token'] = $this->token;
		$where_data5['testid'] = (int)($_GET['id']);
		$where_data5['testtype'] = 5;
		$count[5] = $this->m_user->where($where_data5)->count();
		$this->assign("count",$count);
		$this->display();
	}
	//执行操作
	public function operate(){
		$where_test['imicms_id'] = $_GET['id'];
		$where_test['token'] = $this->token;
		switch($_GET['type']){
			case 'testdel':
				$deltest = $this->m_test->where($where_test)->delete();
				$this->success("删除成功",U("Test/index",array("token"=>$this->token)));
			break;
		}
	}
	
}