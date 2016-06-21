<?php

class ServiceAction extends UserAction
{
	public $apiurl;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction('Service');
		$this->m_service = M('service_setup');
		$this->m_my = M('service_my');
		$this->m_preferential = M('service_preferential');
		$this->m_wechat = M('wechat_group_list');
		$this->m_wxuser = M('wxuser');
		$this->m_mywxuser = M('service_wxuser');
		$this->apiurl['create'] = 'http://im-link.meihua.com/api/app_create.php';
		$this->apiurl['status'] = 'http://im-link.meihua.com/api/app_edit.php';
		$this->apiurl['data'] = 'http://im-link.meihua.com/api/app_service.php';
		$serviceChange = new ServiceChange();
		$serviceChange->index($this->token);
	}

	public function delstart()
	{
		M('service_wxuser')->where(array('token' => $this->token))->delete();
		M('service_setup')->where(array('token' => $this->token))->delete();
		$add_mywxuser['token'] = $this->token;
		$id_mywxuser = $this->m_mywxuser->add($add_mywxuser);
		$this->redirect('User/Service/state', array('token' => $this->token, 'state' => 1));
	}

	public function servicelist()
	{
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();

		if ($mywxuser == '') {
			$add_mywxuser['token'] = $this->token;
			$id_mywxuser = $this->m_mywxuser->add($add_mywxuser);
		}

		$state = $this->m_mywxuser->where(array('token' => $this->token))->getField('state');
		$this->assign('state', $state);
		$where_service['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['name'])) {
			$where_service['nickname'] = array('like', '%' . $_GET['name'] . '%');
			$where_page['name'] = $_GET['name'];
		}

		import('ORG.Util.Page');
		$count = $this->m_service->where($where_service)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$service_list = $this->m_service->where($where_service)->order('addtime')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($service_list as $k => $v) {
			$where_wechat['token'] = $this->token;
			$where_wechat['openid'] = $v['openid'];
			$wechat = $this->m_wechat->where($where_wechat)->find();
			$service_list[$k]['wxname'] = $wechat['nickname'];
		}

		$this->assign('page', $show);
		$this->assign('service_list', $service_list);
		$this->display();
	}

	public function state()
	{
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();

		if ($_GET['state'] == '1') {
			if ($mywxuser['app_id'] == '') {
				$where_wxuser['token'] = $this->token;
				$wxuser = $this->m_wxuser->where($where_wxuser)->find();
				$data['domain'] = str_replace('http://', '', $this->siteUrl);
				$data['domain'] = str_replace('https://', '', $data['domain']);
				$data['label'] = $this->token;
				$data['wx_app_id'] = $wxuser['appid'];
				$data['wx_app_secret'] = $wxuser['appsecret'];
				$data['from'] = '1';
				$data['activity_url'] = $this->siteUrl . '/index.php?g=Wap&m=ServiceReturn&a=activity&token=' . $this->token;
				$data['msg_tip_url'] = $this->siteUrl . '/index.php?g=Wap&m=ServiceReturn&a=msgtip&token=' . $this->token;
				$data['my_url'] = $this->siteUrl . '/index.php?g=Wap&m=ServiceReturn&a=my&token=' . $this->token;
				ksort($data);
				$i = 0;

				foreach ($data as $k => $v) {
					if ($i == 0) {
						$mydata .= $k . '=' . $v;
					}
					else {
						$mydata .= '&' . $k . '=' . $v;
					}

					$i++;
				}

				$data['key'] = md5($mydata);
				$open = json_decode($this->https_request($this->apiurl['create'], $data), true);

				if ($open['err_code'] == 0) {
					$where_mywxuser['token'] = $this->token;
					$save_mywxuser['state'] = $_GET['state'];
					$save_mywxuser['app_id'] = $open['app_id'];
					$save_mywxuser['app_key'] = $open['app_key'];
					$update_mywxuser = $this->m_mywxuser->where($where_mywxuser)->save($save_mywxuser);
					$this->redirect('Service/servicelist', array('token' => $this->token, 'type' => 'setup'));
				}
				else {
					$this->error($open['err_msg']);
				}
			}
			else {
				$data['app_id'] = $mywxuser['app_id'];
				$data['data'] = array('status' => $_GET['state']);
				$mydata['app_id'] = $mywxuser['app_id'];
				$data['key'] = $this->set_key($mydata, $mywxuser['app_key']);
				$open = json_decode($this->https_request($this->apiurl['status'], $data), true);

				if ($open['err_code'] == 0) {
					$where_mywxuser['token'] = $this->token;
					$save_mywxuser['state'] = $_GET['state'];
					$update_mywxuser = $this->m_mywxuser->where($where_mywxuser)->save($save_mywxuser);
					$this->redirect('Service/servicelist', array('token' => $this->token, 'type' => 'setup'));
				}
				else {
					$this->error($open['err_msg']);
				}
			}
		}
		else {
			$data['app_id'] = $mywxuser['app_id'];
			$data['status'] = $_GET['state'];
			$mydata['app_id'] = $mywxuser['app_id'];
			$data['key'] = $this->set_key($mydata, $mywxuser['app_key']);
			$open = json_decode($this->https_request($this->apiurl['status'], $data), true);

			if ($open['err_code'] == 0) {
				$where_mywxuser['token'] = $this->token;
				$save_mywxuser['state'] = $_GET['state'];
				$update_mywxuser = $this->m_mywxuser->where($where_mywxuser)->save($save_mywxuser);
				$this->redirect('Service/servicelist', array('token' => $this->token, 'type' => 'setup'));
			}
			else {
				$this->error($open['err_msg']);
			}
		}
	}

	public function add()
	{
		$this->display();
	}

	public function service_fans()
	{
		$group = $this->_get('group', 'intval');
		$name = $this->_post('name', 'trim');
		$where = array('token' => $this->token);

		if ($group) {
			$where['g_id'] = $group;
		}

		if ($name) {
			$where['nickname'] = array('like', '%' . $name . '%');
		}

		$count = M('Wechat_group_list')->where($where)->count();
		$page = new Page($count, 10);
		$list = M('Wechat_group_list')->where($where)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $key => $val) {
			if ($val['g_id']) {
				$list[$key]['group_name'] = M('Wechat_group')->where(array('id' => $val['g_id']))->getField('name');
			}
			else {
				$list[$key]['group_name'] = '未分组';
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $page->show());
		$this->display();
	}

	public function doadd()
	{
		$where_wechat['token'] = $this->token;
		$where_wechat['openid'] = $_POST['openid'];
		$wechat = $this->m_wechat->where($where_wechat)->find();

		if ($wechat == '') {
			$this->error('openid不正确');
		}
		else {
			$where_service['token'] = $this->token;
			$where_service['nickname'] = $_POST['nickname'];
			$service = $this->m_service->where($where_service)->find();
			$where_service2['token'] = $this->token;
			$where_service2['openid'] = $_POST['openid'];
			$service2 = $this->m_service->where($where_service2)->find();
			if (($service == '') && ($service2 == '')) {
				$add_service['token'] = $this->token;
				$add_service['openid'] = $_POST['openid'];
				$add_service['nickname'] = $_POST['nickname'];
				$add_service['headimgurl'] = $_POST['headimgurl'];
				$add_service['desc'] = $_POST['desc'];
				$add_service['addtime'] = time();
				$id_service = $this->m_service->add($add_service);
				$this->topost();
				$this->success('添加成功', U('Service/servicelist', array('token' => $this->token, 'type' => 'setup')));
			}
			else {
				$this->error('此客服已存在');
			}
		}
	}

	public function topost()
	{
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
		$where_service['token'] = $this->token;
		$service = $this->m_service->where($where_service)->select();

		foreach ($service as $k => $v) {
			$servicedata[$k]['nickname'] = $v['nickname'];
			$servicedata[$k]['avatar'] = $v['headimgurl'];
			$servicedata[$k]['openid'] = $v['openid'];
			$servicedata[$k]['desc'] = $v['desc'];
		}

		$data['app_id'] = $mywxuser['app_id'];
		$data['data'] = $servicedata;
		$mydata['app_id'] = $mywxuser['app_id'];
		$data['key'] = $this->set_key($mydata, $mywxuser['app_key']);
		$todata = json_decode($this->https_request($this->apiurl['data'], $data), true);

		if ($todata['err_code'] != 0) {
			$this->error($todata['err_msg']);
			exit();
		}
	}

	public function set_key($data, $app_key)
	{
		$new_arr = array();
		ksort($data);

		foreach ($data as $k => $v) {
			$new_arr[] = $k . '=' . $v;
		}

		$new_arr[] = 'app_key=' . $app_key;
		$str = implode('&', $new_arr);
		return md5($str);
	}

	public function update()
	{
		$where_service['token'] = $this->token;
		$where_service['imicms_id'] = (int) $_GET['id'];
		$service = $this->m_service->where($where_service)->find();
		$this->assign('service', $service);
		$this->display();
	}

	public function doupdate()
	{
		$where_wechat['token'] = $this->token;
		$where_wechat['openid'] = $_POST['openid'];
		$wechat = $this->m_wechat->where($where_wechat)->find();

		if ($wechat == '') {
			$this->error('openid不正确');
		}
		else {
			$where_service1['token'] = $this->token;
			$where_service1['openid'] = $_POST['openid'];
			$where_service1['imicms_id'] = array('neq', $_POST['imicms_id']);
			$service1 = $this->m_service->where($where_service1)->find();
			$where_service2['token'] = $this->token;
			$where_service2['nickname'] = $_POST['nickname'];
			$where_service2['imicms_id'] = array('neq', $_POST['imicms_id']);
			$service2 = $this->m_service->where($where_service2)->find();
			if (($service1 == '') && ($service2 == '')) {
				$where_service['token'] = $this->token;
				$where_service['imicms_id'] = (int) $_POST['imicms_id'];
				$save_service['openid'] = $_POST['openid'];
				$save_service['nickname'] = $_POST['nickname'];
				$save_service['headimgurl'] = $_POST['headimgurl'];
				$save_service['desc'] = $_POST['desc'];
				$update_service = $this->m_service->where($where_service)->save($save_service);
				$this->topost();
				$this->success('修改成功', U('Service/servicelist', array('token' => $this->token, 'type' => 'setup')));
			}
			else {
				$this->error('此客服已存在');
			}
		}
	}

	public function del()
	{
		$where_service['token'] = $this->token;
		$where_service['imicms_id'] = (int) $_GET['id'];
		$del_service = $this->m_service->where($where_service)->delete();
		$this->topost();
		$this->success('删除成功', U('Service/servicelist', array('token' => $this->token, 'type' => 'setup')));
	}

	public function preferentiallist()
	{
		$where_preferential['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['name'])) {
			$where_preferential['name'] = array('like', '%' . $_GET['name'] . '%');
			$where_page['name'] = $_GET['name'];
		}

		import('ORG.Util.Page');
		$count = $this->m_preferential->where($where_preferential)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$preferential_list = $this->m_preferential->where($where_preferential)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('page', $show);
		$this->assign('preferential_list', $preferential_list);
		$this->display();
	}

	public function preferentialadd()
	{
		$this->display();
	}

	public function preferentialdoadd()
	{
		$add_preferential['token'] = $this->token;
		$add_preferential['name'] = $_POST['name'];
		$add_preferential['img'] = $_POST['img'];
		$add_preferential['url'] = $_POST['url'];
		$add_preferential['info'] = $_POST['info'];
		$add_preferential['addtime'] = time();
		$id_preferential = $this->m_preferential->add($add_preferential);
		$this->success('添加成功', U('Service/preferentiallist', array('token' => $this->token, 'type' => 'preferential')));
	}

	public function preferentialupdate()
	{
		$where_preferential['token'] = $this->token;
		$where_preferential['imicms_id'] = (int) $_GET['id'];
		$preferential = $this->m_preferential->where($where_preferential)->find();
		$this->assign('preferential', $preferential);
		$this->display();
	}

	public function preferentialdoupdate()
	{
		$save_preferential['name'] = $_POST['name'];
		$save_preferential['img'] = $_POST['img'];
		$save_preferential['url'] = $_POST['url'];
		$save_preferential['info'] = $_POST['info'];
		$where_preferential['token'] = $this->token;
		$where_preferential['imicms_id'] = (int) $_POST['imicms_id'];
		$update_preferential = $this->m_preferential->where($where_preferential)->save($save_preferential);
		$this->success('修改成功', U('Service/preferentiallist', array('token' => $this->token, 'type' => 'preferential')));
	}

	public function preferentialdel()
	{
		$where_preferential['token'] = $this->token;
		$where_preferential['imicms_id'] = (int) $_GET['id'];
		$del_preferential = $this->m_preferential->where($where_preferential)->delete();
		$this->success('删除成功', U('Service/preferentiallist', array('token' => $this->token, 'type' => 'preferential')));
	}

	public function my()
	{
		if (IS_POST) {
			$where_my_cy['token'] = $this->token;
			$where_my_cy['type'] = 'cy';
			$save_my_cy['display'] = $_POST['display_cy'];
			$save_my_cy['title'] = $_POST['title_cy'];
			$save_my_cy['img'] = $_POST['img_cy'];
			$update_my_cy = $this->m_my->where($where_my_cy)->save($save_my_cy);
			$where_my_sc['token'] = $this->token;
			$where_my_sc['type'] = 'sc';
			$save_my_sc['display'] = $_POST['display_sc'];
			$save_my_sc['title'] = $_POST['title_sc'];
			$save_my_sc['img'] = $_POST['img_sc'];
			$update_my_sc = $this->m_my->where($where_my_sc)->save($save_my_sc);
			$where_my_tg['token'] = $this->token;
			$where_my_tg['type'] = 'tg';
			$save_my_tg['display'] = $_POST['display_tg'];
			$save_my_tg['title'] = $_POST['title_tg'];
			$save_my_tg['img'] = $_POST['img_tg'];
			$update_my_tg = $this->m_my->where($where_my_tg)->save($save_my_tg);
			$where_my_wm['token'] = $this->token;
			$where_my_wm['type'] = 'wm';
			$save_my_wm['display'] = $_POST['display_wm'];
			$save_my_wm['title'] = $_POST['title_wm'];
			$save_my_wm['img'] = $_POST['img_wm'];
			$update_my_wm = $this->m_my->where($where_my_wm)->save($save_my_wm);
		}
		else {
			$where_my_cy['token'] = $this->token;
			$where_my_cy['type'] = 'cy';
			$my_cy = $this->m_my->where($where_my_cy)->find();

			if ($my_cy == '') {
				$add_my_cy['token'] = $this->token;
				$add_my_cy['type'] = 'cy';
				$add_my_cy['title'] = '我的餐饮';
				$add_my_cy['img'] = $this->staticPath . '/tpl/static/attachment/icon/canyin/canyin_red/5.png';
				$add_my_cy['display'] = 1;
				$id_my_cy = $this->m_my->add($add_my_cy);
			}

			$where_my_sc['token'] = $this->token;
			$where_my_sc['type'] = 'sc';
			$my_sc = $this->m_my->where($where_my_sc)->find();

			if ($my_sc == '') {
				$add_my_sc['token'] = $this->token;
				$add_my_sc['type'] = 'sc';
				$add_my_sc['title'] = '我的商城';
				$add_my_sc['img'] = $this->staticPath . '/tpl/static/attachment/icon/hotel/hotel_red/8.png';
				$add_my_sc['display'] = 1;
				$id_my_sc = $this->m_my->add($add_my_sc);
			}

			$where_my_tg['token'] = $this->token;
			$where_my_tg['type'] = 'tg';
			$my_tg = $this->m_my->where($where_my_tg)->find();

			if ($my_tg == '') {
				$add_my_tg['token'] = $this->token;
				$add_my_tg['type'] = 'tg';
				$add_my_tg['title'] = '我的团购';
				$add_my_tg['img'] = $this->staticPath . '/tpl/static/attachment/icon/hotel/hotel_red/27.png';
				$add_my_tg['display'] = 1;
				$id_my_tg = $this->m_my->add($add_my_tg);
			}

			$where_my_wm['token'] = $this->token;
			$where_my_wm['type'] = 'wm';
			$my_wm = $this->m_my->where($where_my_wm)->find();

			if ($my_wm == '') {
				$add_my_wm['token'] = $this->token;
				$add_my_wm['type'] = 'wm';
				$add_my_wm['title'] = '我的外卖';
				$add_my_wm['img'] = $this->staticPath . '/tpl/static/attachment/icon/canyin/canyin_red/20.png';
				$add_my_wm['display'] = 1;
				$id_my_wm = $this->m_my->add($add_my_wm);
			}
		}

		$where_my['token'] = $this->token;
		$my = $this->m_my->where($where_my)->select();

		foreach ($my as $k => $v) {
			$myinfo[$v['type']]['title'] = $v['title'];
			$myinfo[$v['type']]['img'] = $v['img'];
			$myinfo[$v['type']]['display'] = $v['display'];
		}

		$this->assign('my', $myinfo);
		$this->display();
	}

	protected function https_request($url, $data = NULL)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
}

?>
