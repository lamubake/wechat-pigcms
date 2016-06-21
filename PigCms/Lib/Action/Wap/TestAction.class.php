<?php
class TestAction extends WapAction{
	public function _initialize(){
		parent::_initialize();
		/*$checkFunc=new checkFunc();if (!function_exists('fdsrejsie3qklwewerzdagf4ds')){exit('error-4');}
        $checkFunc->cfdwdgfds3skgfds3szsd3idsj();*/
		//$this->wecha_id = "o6Jlyt-8jTEZKtpmN-5-DpmFB3cA";//测试使用，上线前删除
		//加载数据库
		$this->m_test = M("test");
		$this->m_user = M("test_user");
		
		if(empty($this->wecha_id) && !in_array(ACTION_NAME,array("gzhurl"))){
			$this->success("您好，您还没有关注我们的公众号,关注后才能继续喔。",U("Test/gzhurl",array("token"=>$this->token)));
			exit;
		}
	}
	//判断关注
	public function gzhurl(){
		$gzhurl = M('Home')->where(array('token'=>$this->token))->getField('gzhurl');
		if($gzhurl == null){
			$this->show("<h1>未设置关注链接</h1>");
		}else{
			$this->show("<script>window.location.href='".$gzhurl."'</script>");
		}
	}
	//首页
	public function index(){
		$where_test['token'] = $this->token;
		$where_test['imicms_id'] = (int)($_GET['id']);
		$test = $this->m_test->where($where_test)->find();
		$save['pv'] = $test['pv'] + 1;
		$update = $this->m_test->where($where_test)->save($save);
		$this->assign("test",$test);
		$where_test_list['token'] = $this->token;
		$test_list1 = $this->m_test->where($where_test_list)->order("imicms_id desc")->select();
		$test_list2 = $this->m_test->where($where_test_list)->order("imicms_id")->select();
		if($test_list1[0]['imicms_id'] == (int)($_GET['id'])){
			$next['id'] = $test_list2[0]['imicms_id'];
			$next['name'] = $test_list2[0]['name'];
		}else{
			foreach($test_list2 as $k=>$v){
				$test_next[$v['imicms_id']] = $k;
			}
			$where_test_next['token'] = $this->token;
			$where_test_next['imicms_id'] = $test_list2[($test_next[(int)($_GET['id'])]+1)]['imicms_id'];
			$find_test_next = $this->m_test->where($where_test_next)->find();
			$next['id'] = $find_test_next['imicms_id'];
			$next['name'] = $find_test_next['name'];
		}
		$this->assign("next",$next);
		$this->display();
	}
	//跳转页面
	public function index2(){
		$where_test['token'] = $this->token;
		$where_test['imicms_id'] = (int)($_GET['id']);
		$test = $this->m_test->where($where_test)->find();
		$save['pv'] = $test['pv'] + 1;
		$update = $this->m_test->where($where_test)->save($save);
		$this->assign("test",$test);
		$where_test_list['token'] = $this->token;
		$test_list1 = $this->m_test->where($where_test_list)->order("imicms_id desc")->select();
		$test_list2 = $this->m_test->where($where_test_list)->order("imicms_id")->select();
		if($test_list1[0]['imicms_id'] == (int)($_GET['id'])){
			$next['id'] = $test_list2[0]['imicms_id'];
			$next['name'] = $test_list2[0]['name'];
		}else{
			foreach($test_list2 as $k=>$v){
				$test_next[$v['imicms_id']] = $k;
			}
			$where_test_next['token'] = $this->token;
			$where_test_next['imicms_id'] = $test_list2[($test_next[(int)($_GET['id'])]+1)]['imicms_id'];
			$find_test_next = $this->m_test->where($where_test_next)->find();
			$next['id'] = $find_test_next['imicms_id'];
			$next['name'] = $find_test_next['name'];
		}
		$this->assign("next",$next);
		switch($_GET['answer']){
			case '0':
				$imgUrl = $test['fistapic'];
				$tTitle = $test['fistatitle'].$test['fistatitle2'];
				$st=$test['fistatitle'];
				$sc=$test['fistatitle2'];
				$tContent = $test['fistainfo'];
			break;
			case '1':
				$imgUrl = $test['secondapic'];
				$tTitle = $test['secondatitle'].$test['secondatitle2'];
				$st=$test['secondatitle'];
				$sc=$test['secondatitle2'];
				$tContent = $test['secondainfo'];
			break;
			case '2':
				$imgUrl = $test['thirdapic'];
				$tTitle = $test['thirdatitle'].$test['thirdatitle2'];
				$st=$test['thirdatitle'];
				$sc=$test['thirdatitle2'];
				$tContent = $test['thirdainfo'];
			break;
			case '3':
				$imgUrl = $test['fourapic'];
				$tTitle = $test['fouratitle'].$test['fouratitle2'];
				$st=$test['fouratitle'];
				$sc=$test['fouratitle2'];
				$tContent = $test['fourainfo'];
			break;
			case '4':
				$imgUrl = $test['fiveapic'];
				$tTitle = $test['fiveatitle'].$test['fiveatitle2'];
				$st=$test['fiveatitle'];
				$sc=$test['fiveatitle2'];
				$tContent = $test['fiveainfo'];
			break;
		}
		$this->assign("st",$st);
		$this->assign("sc",$sc);
		$this->assign("imgUrl",$imgUrl);
		$this->assign("tTitle",$tTitle);
		$this->assign("tContent",$tContent);
		$this->display();
	}
	//ajax
	public function ajax(){
		switch($_POST['type']){
			case 'adduer':
				$add_user['token'] = $this->token;
				$add_user['wecha_id'] = $this->wecha_id;
				$add_user['testid'] = $_POST['id'];
				$add_user['testtype'] = $_POST['index']+1;
				$add_user['addtime'] = time();
				$id_user = $this->m_user->add($add_user);
				if($id_user > 0){
					$data['error'] = 0;
					$this->ajaxReturn($data,'JSON');
				}
			break;
		}
	}
}
?>