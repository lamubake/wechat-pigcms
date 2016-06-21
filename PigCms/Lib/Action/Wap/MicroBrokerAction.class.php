<?php

class MicroBrokerAction extends WapAction {

    protected $thisopenduser;

    public function __construct() {

        $this->bid = $this->_get('bid') ? intval($this->_get('bid', 'trim')) : 0;
        parent::_initialize();
		
        if (!$this->wecha_id) {
            $this->wecha_id = '';
            $_SESSION['token_openid_' . $this->token] = '';
        }
		if($this->owndomain){
		   $this->siteUrl="http://".$this->owndomain;
		}
        $this->thisopenduser = $this->bid . '_user' . $this->wecha_id;
        //$loginuserid=isset($_SESSION[$thisopenduser]) && !empty($_SESSION[$thisopenduser]) ? intval($_SESSION[$thisopenduser]) : 0;
        $loginuserid = cookie($this->thisopenduser);
        $this->loginuserid = $loginuserid ? intval($loginuserid) : 0;
		$tmpuserid=$this->_get('loginuserid') ? intval($this->_get('loginuserid','trim')) : 0;
		if($this->owndomain && ($this->rget==3) && ($tmpuserid>0)){
		  $this->loginuserid=$tmpuserid;
		}
		$bgimg=$_SESSION['MicroBroker_bgimg' . $this->bid];
        $this->assign('loginuserid', $this->loginuserid);
        $this->assign('wecha_id', $this->wecha_id);
        $this->assign('bid', $this->bid);
		$this->assign('bgimg', $bgimg);
    }

    public function index() {
        $_SESSION['MicroBroker_bid' . $this->wecha_id] = $this->bid;
        if ($this->loginuserid > 0) {
            Header("Location:" . $this->siteUrl . U('MicroBroker/home', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        }
        $Userarr = $this->getUserinfo();
        if (!empty($Userarr) && is_array($Userarr)) {
            Header("Location:" . $this->siteUrl . U('MicroBroker/home', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        }
        if (!($this->bid > 0))
            $this->exitdisplay('活动不存在！');
        $db_broker = M('Broker');
        $where = array('token' => $this->token, 'id' => $this->bid);
        $brokerarr = $db_broker->where($where)->find();

        if (empty($brokerarr) || !is_array($brokerarr)) {
            //exit('活动不存在！');
            $this->exitdisplay('活动不存在！');
        }
        if ($brokerarr['isdel'] == 1) {
            //exit('活动已经被删除了');
            $this->exitdisplay('活动已经被删除了');
        }
        $brokerarr['picurl'] = $brokerarr['picurl'] ? $brokerarr['picurl'] : $this->staticPath . '/tpl/static/microbroker/images/bg-loader-default.jpg';
		$_SESSION['MicroBroker_bgimg' . $this->bid] = !empty($brokerarr['bgimg']) ? $brokerarr['bgimg'] : false;
        $this->assign('brokerarr', $brokerarr);
        $this->display();
    }

    //错误提醒页面
    private function exitdisplay($tips = "") {
        //保证输出不受静态缓存影响
        C('HTML_CACHE_ON', false);
        $this->assign('tips', $tips);
        $this->display('exitdisplay');
        exit;
    }

    //获取登录人信息
    private function getUserinfo($uid = 0) {
        $db_brokeruser = M('Broker_user');
        if ($uid > 0) {
            return $db_brokeruser->where(array('id' => $uid))->find();
        }
        if ($this->loginuserid > 0) {
            $wherearr = array('id' => $this->loginuserid, 'token' => $this->token, 'bid' => $this->bid, 'wecha_id' => $this->wecha_id);
        } else {
            $wherearr = array('token' => $this->token, 'bid' => $this->bid, 'wecha_id' => $this->wecha_id);
        }

        $usrtmparr = $db_brokeruser->where($wherearr)->find();
        //echo $db_brokeruser->getLastSql(); 
        return $usrtmparr;
    }

    public function home() {
        //$token		= $this->_get('token');
        $this->wecha_id = trim($this->wecha_id);
        $bid = $this->bid;
        //$this->assign('token',$token);
        //$this->assign('wecha_id',$wecha_id);
        //$this->assign('id',$id);
        if (!($bid > 0))
            $this->exitdisplay('活动不存在！');
        //exit('活动不存在！');
        $db_broker = M('Broker');
        $db_broker_item = M('Broker_item');
        $where = array('token' => $this->token, 'id' => $bid);
        $brokerarr = $db_broker->where($where)->find();

        if (empty($brokerarr) || !is_array($brokerarr)) {
            $this->exitdisplay('活动不存在！');
        }
        if ($brokerarr['isdel'] == 1) {
            $this->exitdisplay('活动已经被删除了');
        }
        $success = array();
        if ($brokerarr['statdate'] > time()) {
            $this->exitdisplay('活动还没有开始');
        }

        //if ($brokerarr['enddate'] < time()) {
        //exit('活动已经结束了');
        //$success = array('err' => 2, 'info' => '活动已经结束');
        //}
        $is_go_login = false;
        $brokeruser = $this->getUserinfo();
        if (!empty($brokeruser) && is_array($brokeruser)) {
            if ($brokeruser['status'] == 1) {
                $this->exitdisplay('您已经被活动管理人员拉入黑名单了！');
            }
            $tmp = M('Broker_translation')->where(array('id' => $brokeruser['identity']))->find();
            $brokeruser['identitystr'] = $tmp['description'];
            $brokeruser['identitylevel'] = $tmp['type'];
            if ($this->loginuserid == 0) {
                $is_go_login = true; //有信息没cookie 需要登录
            }
        } else {
            cookie($this->thisopenduser, 0);
        }
        $_SESSION['MicroBroker_bid' . $this->wecha_id] = $bid; //重新赋值

        if ($is_go_login)
            Header("Location:" . $this->siteUrl . U('MicroBroker/login', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $bid)));
        $broker_item = $db_broker_item->where(array('bid' => $bid))->order('id ASC')->select();
        $isproduct = false;
        if (!empty($broker_item) || is_array($broker_item)) {
            $isproduct = true;
            foreach ($broker_item as $kk => $vv) {
                $broker_item[$kk]['tourl'] = $this->getLink($vv['tourl']); //功能库取出来的地址需转换
            }
        }
		$bgimg=$_SESSION['MicroBroker_bgimg' . $bid] = !empty($brokerarr['bgimg']) ? $brokerarr['bgimg'] : false;
        $brokerarr['picurl'] = $brokerarr['picurl'] ? $brokerarr['picurl'] : $this->staticPath . '/tpl/static/microbroker/images/bg-loader-default.jpg';
        $this->assign('loginuserid', $this->loginuserid);
        $this->assign('brokeruser', $brokeruser);
        $this->assign('noproduct', $noproduct);
        $this->assign('bid', $bid);
		$this->assign('bgimg', $bgimg);
        $this->assign('isproduct', $isproduct);
        $this->assign('success', $success);
        $this->assign('broker_item', $broker_item);
        $this->assign('brokerarr', $brokerarr);
        $this->display();
    }

    //登录 手机号为用户名
    public function login() {
        $bid = $_SESSION['MicroBroker_bid' . $this->wecha_id];
		/*$wechaid = $this->_get('wecha_id','trim');
        if (!empty($wechaid) && ($wechaid != $this->wecha_id)) {
            $this->wecha_id = $wechaid;
			$this->assign('wecha_id', $wechaid);
            $_SESSION['token_openid_' . $this->token] = $wechaid;
        }*/
        if ($bid != $this->bid)
            Header("Location:" . $this->siteUrl . U('MicroBroker/home', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        $brokerarr = $this->getUserinfo();
        $this->assign('brokerarr', $brokerarr);
        $this->display();
    }

    public function logining() {
        //1参数出错 2手机号必须为纯数字 3用户名或密码错误 0 OK
        if (!($this->bid > 0) || empty($this->token)) {
            echo 1;
            exit();
        }
        $phone = $this->_post('phone', 'trim');
        if (!is_numeric($phone)) {
            echo 2;
            exit();
        }
        $pwd = md5($this->_post('password', 'trim'));
        $db_brokeruser = M('Broker_user');
		$this->wecha_id = $this->wecha_id ? $this->wecha_id : "MBK_" . $this->bid . '_temp' . $phone;
        $brokerarr = $db_brokeruser->where(array('tel' => $phone, 'pwd' => $pwd, 'bid' => $this->bid, 'token' => $this->token))->find();
        if (!empty($brokerarr) && is_array($brokerarr)) {
            $thisopenduser = $this->bid . '_user' . $this->wecha_id;
            $tm = 365 * 24 * 3600;
            cookie($thisopenduser, $brokerarr['id'], array('expire' => $tm, 'path' => '/'));
            $this->loginuserid = $brokerarr['id'];
            //更新一下wecha_id
            $db_brokeruser->where(array('id' => $brokerarr['id'], 'bid' => $this->bid))->save(array('wecha_id' => $this->wecha_id));
            $_SESSION['token_openid_' . $this->token] = $this->wecha_id;
			if($this->owndomain && ($this->rget==3)){
				$this->dexit(array('analyze'=>1,'error' => 0, 'msg' =>'opt_cookie','data'=>array('ckkey'=>$thisopenduser,'ckv'=>$brokerarr['id'],'expire'=>$tm)));
			}else{
              echo 0;
              exit();
			}
        }
        echo 3;
        exit();
    }

    public function Register() {
        $bid = $_SESSION['MicroBroker_bid' . $this->wecha_id];
        if ($bid != $this->bid)
            Header("Location:" . $this->siteUrl . U('MicroBroker/home', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        $this->display();
    }

	/**修改密码页面**/
	public function modifyPwd(){
	   $brokeruser = $this->getUserinfo();
	   $this->assign('brokerarr', $brokeruser);
	   $this->display();
	}

	/**修改密码**/
	public function modifyPwding(){
	    //1参数出错 2手机号必须为纯数字 3此手机号用户不存在，您可以先去注册 0 OK
        if (!($this->bid > 0) || empty($this->token)) {
            echo 1;
            exit();
        }
        $phone = $this->_post('phone', 'trim');
        if (!is_numeric($phone)) {
            echo 2;
            exit();
        }
        $pwd = md5($this->_post('password', 'trim'));
        $db_brokeruser = M('Broker_user');
        $this->wecha_id = $this->wecha_id ? $this->wecha_id : "MBK_" . $this->bid . '_temp' . $phone;
        $brokerarr = $db_brokeruser->where(array('tel' => $phone, 'bid' => $this->bid, 'token' => $this->token,'wecha_id'=>$this->wecha_id))->find();
        if (!empty($brokerarr) && is_array($brokerarr)) {
            //更新一下密码
            $db_brokeruser->where(array('id' => $brokerarr['id'], 'bid' => $this->bid))->save(array('pwd' => $pwd));
              echo 0;
              exit();
        }
        echo 3;
        exit();
	}
    public function Registering() {
        //1参数出错2手机号不能为空3手机号已经被注册过了4注册失败0 OK
        if (!($this->bid > 0) || empty($this->token)) {
            echo 1;
            exit();
        }
        $sp = $this->_post('sp', 'trim');
        if ($sp == 'check') {
            $phone = $this->_post('phone', 'trim');
            if ($phone == '') {
                echo 2;
                exit();
            }
            $tmp = M('broker_user')->where(array('bid' => $this->bid, 'tel' => $phone))->find();
            if ($tmp) {
                echo 3;
                exit();
            }
            echo 0;
            exit();
        } elseif ($sp == 'save') {
            $datas['token'] = $this->token;
            $datas['bid'] = $this->bid;
            $datas['tel'] = $this->_post('phone', 'trim');
            $this->wecha_id = $this->wecha_id ? $this->wecha_id : "MBK_" . $this->bid . '_temp' . $datas['tel'];
            $datas['username'] = $this->_post('name');
            $datas['pwd'] = md5($this->_post('password', 'trim'));
            $datas['identity'] = $this->_post('myjob', 'intval');
            $datas['company'] = $this->_post('company', 'trim');
            $datas['wecha_id'] = $this->wecha_id;
            $datas['addtime'] = time();
            $userid = M('broker_user')->add($datas);
            if ($userid > 0) {
                $thisopenduser = $this->bid . '_user' . $this->wecha_id;
                $tm = 365 * 24 * 3600;
                cookie($thisopenduser, $userid, array('expire' => $tm, 'path' => '/'));
				if($this->owndomain && ($this->rget==3)){
				    $this->dexit(array('analyze'=>1,'error' => 0, 'msg' =>'opt_cookie','data'=>array('ckkey'=>$thisopenduser,'ckv'=>$brokerarr['id'],'expire'=>$tm)));
				}else{
                  echo 0;
                  exit;
				}
            } else {
                echo 4;
                exit;
            }
        }
    }

    //我要推荐 页面
    public function Recommend() {
        $this->check_login();
        $broker_item = M('Broker_item')->where(array('bid' => $this->bid))->order('id ASC')->select();
        if (empty($broker_item)) {
            Header("Location:" . $this->siteUrl . U('MicroBroker/home', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        }
        $itemarr = array();
        foreach ($broker_item as $vv) {
            $itemarr['s' . $vv['id']] = array('xmt' => $vv['xmtype'], 'xmn' => $vv['xmnum']);
        }
        $tmp = json_encode($itemarr);
        $this->assign('itemstr', $tmp);
        $this->assign('broker_item', $broker_item);
        $this->display();
    }

    //我要推荐 拉人处理
    public function Recommending() {
        //1参数出错 2推荐信息保存失败 3此人在此项目已经被推荐过了 0 OK
        if (!($this->bid > 0) || empty($this->token) || empty($this->wecha_id)) {
            echo 1;
            exit();
        }
        $datas['token'] = $this->token;
        $datas['bid'] = $this->bid;
        $datas['tjuid'] = $this->loginuserid; //推荐人用户id
        $brokeruser = $this->getUserinfo();
        if ($brokeruser['is_verify'] == 1) {
            $datas['verifyuid'] = $this->loginuserid;
        } else {
            $datas['verifyuid'] = 0;
        }
        $datas['status'] = 0;
        $datas['cname'] = $this->_post('clientname', 'trim');
        $datas['ctel'] = $this->_post('cellphone', 'trim');
        $datas['proid'] = $this->_post('proid', 'intval'); //意向产品id
        $datas['remark'] = $this->_post('remark', 'tirm');
        $datas['addtime'] = $datas['uptime'] = time();
        $datas['wecha_id'] = $this->wecha_id;
		/**$tmparr=M('Broker_client')->where(array('bid'=>$this->bid,'tjuid'=>$this->loginuserid,'ctel'=>$datas['ctel'],'proid'=>$datas['proid']))->find();****17689 工单编号,还有其他几个用户也提到了**/
	 /****现在改成同一个项目一个手机号只能被推荐一次，一个经纪人推荐了，另一个经济人就不能够推荐此人此项目了****/
		$tmparr=M('Broker_client')->where(array('bid'=>$this->bid,'ctel'=>$datas['ctel'],'proid'=>$datas['proid']))->find();
		if(!empty($tmparr) && is_array($tmparr)){
		   echo 3;
		   exit();
		}
        $clientid = M('Broker_client')->add($datas);
        M('Broker_user')->where(array('id' => $this->loginuserid, 'bid' => $this->bid, 'token' => $this->token))->setInc('recommendnum', 1);
        if ($clientid > 0) {
            echo 0;
        } else {
            echo 2;
        }
    }

    //个人信息修改处理中心页面
    public function SetUser() {
        $this->check_login();
        $brokeruser = $this->getUserinfo();
        if (!empty($brokeruser) && is_array($brokeruser)) {
            $tmp = M('Broker_translation')->where(array('id' => $brokeruser['identity']))->find();
            $brokeruser['identitystr'] = $tmp['description'];
            $brokeruser['identitylevel'] = $tmp['type'];
        }
        $this->assign('brokeruser', $brokeruser);
        $this->display();
    }

    //个人信息编辑页面
    public function EidtUser() {
        $this->check_login();
        $brokeruser = $this->getUserinfo();
        if (!empty($brokeruser) && is_array($brokeruser)) {
            $tmp = M('Broker_translation')->where(array('id' => $brokeruser['identity']))->find();
            $brokeruser['identitystr'] = $tmp['description'];
            $brokeruser['identitylevel'] = $tmp['type'];
        }
        $this->assign('brokeruser', $brokeruser);
        $this->display();
    }

    //个人信息修改处理
    public function EidtUsering() {
        //1参数出错 2推荐信息保存失败 0 OK
        if (!($this->bid > 0) || empty($this->token) || empty($this->wecha_id)) {
            echo 1;
            exit();
        }
        $datas['tel'] = $this->_post('phone', 'trim');
        $datas['username'] = $this->_post('name');
        $datas['identity'] = $this->_post('myjob', 'intval');
        $datas['company'] = $this->_post('company', 'trim');
        if (in_array($datas['identity'], array(1, 2))) {
            $datas['company'] = '';
        }
        $tmp = M('Broker_user')->where(array('id' => $this->loginuserid, 'bid' => $this->bid))->save($datas);
        if ($tmp) {
            echo 0;
        } else {
            echo "保存失败请重试";
        }
    }

    //注册人身份转换
    public function SwitchIdentity() {
        $this->check_login();
        $identity = $this->_get('t', 'trim');
        $changarr = array('toConsultant' => 7, 'toOwner' => 6);
        if (!in_array($identity, array('toConsultant', 'toOwner'))) {
            Header("Location:" . $this->siteUrl . U('MicroBroker/SetUser', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        }
        $identity = $changarr[$identity];
        $brokeruser = $this->getUserinfo();
        $this->assign('brokeruser', $brokeruser);
        $this->assign('identity', $identity);
        $this->display();
    }

    //注册人身份转换
    public function Switching() {
        //1参数出错 2推荐信息保存失败 0 OK
        if (!($this->bid > 0) || empty($this->token) || empty($this->wecha_id)) {
            echo 1;
            exit();
        }
        $identity = $this->_post('tome', 'trim');
        $identity = intval($identity);
        if (!in_array($identity, array(6, 7))) {
            echo 1;
            exit();
        }
        if ($identity == 6) {
            $datas['tel'] = $this->_post('phone', 'trim');
            $datas['username'] = $this->_post('name', 'trim');
            $datas['identity'] = 6;
            $datas['identitycode'] = $this->_post('identitycode', 'trim');
            $tmp = M('Broker_user')->where(array('id' => $this->loginuserid, 'bid' => $this->bid))->save($datas);
            echo 0;
            /* if ($tmp) {
              echo 0;
              } else {
              echo "保存失败请稍后重试";
              } */
        } elseif ($identity == 7) {
            $invitcode = $this->_post('invitcode', 'trim');
            //$db_Broker=M('Broker');
            $Brokerarr = M('Broker')->where(array('id' => $this->bid, 'token' => $this->token))->find();
            if ($Brokerarr['invitecode'] == $invitcode) {
                $datas['tel'] = $this->_post('phone', 'trim');
                $datas['username'] = $this->_post('name', 'trim');
                $datas['identity'] = 7;
                $datas['is_verify'] = 1;
                $datas['company'] = $this->_post('company', 'trim');
                $tmp = M('Broker_user')->where(array('id' => $this->loginuserid, 'bid' => $this->bid))->save($datas);
                echo 0;
                /* if ($tmp) {
                  echo 0;
                  } else {
                  echo "保存失败请稍后重试";
                  } */
            } else {
                echo "邀请码不正确";
            }
        }
        exit;
    }

    //我推荐的客户列表 
    public function MyClientList() {
        $this->check_login(false);
        $db_client = M('broker_client');
        $jointable = C('DB_PREFIX') . 'broker_item';
        $db_client->join('as b_c LEFT JOIN ' . $jointable . ' as b_i on b_c.proid=b_i.id');
        $brokeruser = $this->getUserinfo();
        $is_verify = $brokeruser['is_verify'];
        if ($is_verify == 1) {
            $db_client->where("(b_c.tjuid=" . $this->loginuserid . " OR b_c.verifyuid=" . $this->loginuserid . ") AND b_c.bid=" . $this->bid . " AND b_c.token='" . $this->token . "'");
        } else {
            $db_client->where("b_c.tjuid=" . $this->loginuserid . " AND b_c.bid=" . $this->bid . " AND b_c.token='" . $this->token . "'");
        }
        $rest = $db_client->field('b_c.*,b_i.xmname,b_i.tourl')->order('b_c.id DESC')->select();
        $statusarr = array(0 => '新用户', 1 => '已跟进', 2 => '已到访', 3 => '已认筹', 4 => '已认购', 5 => '已签约', 6 => '已回款', 7 => '完成');
        $this->assign('myclients', $rest);
        $this->assign('statusarr', $statusarr);
        $this->display();
    }

    //客户具体流程状态信息
    public function MyClientView() {
        $this->check_login(false);
        $id = intval($this->_get('id', 'trim'));
        $brokeruser = $this->getUserinfo();
        $is_verify = $brokeruser['is_verify'];
        $db_client = M('broker_client');
        if ($is_verify == 1) {
            $db_client->where("id=" . $id . " AND (tjuid=" . $this->loginuserid . " OR verifyuid=" . $this->loginuserid . ") AND bid=" . $this->bid . " AND token='" . $this->token . "'");
        } else {
            $db_client->where("id=" . $id . " AND tjuid=" . $this->loginuserid . " AND bid=" . $this->bid . " AND token='" . $this->token . "'");
        }
        $client = $db_client->find();
        $optionlog = array();
        if (!empty($client) && is_array($client)) {
            $tmp = M('broker_item')->where(array('id' => $client['proid']))->find();
            $client['xmname'] = is_array($tmp) ? $tmp['xmname'] : '';
            if ($client['verifyuid'] > 0) {
                $userarr = $this->getUserinfo($client['verifyuid']);
                $client['zygwname'] = is_array($userarr) ? $userarr['username'] : ''; //置业顾问名字
                $client['zygwtel'] = is_array($userarr) ? $userarr['tel'] : '';
            }
            $opts = M('broker_commission')->where(array('clientid' => $client['id'], 'bid' => $this->bid))->select();

            if (is_array($opts)) {
                foreach ($opts as $vv) {
                    $optionlog["s" . $vv['client_status']] = $vv;
                }
            }
        }
        $statusarr = array(0 => '推荐', 1 => '已跟进', 2 => '已到访', 3 => '已认筹', 4 => '已认购', 5 => '已签约', 6 => '已回款', 7 => '完成');
        $this->assign('statusarr', $statusarr);
        $this->assign('is_verify', $is_verify); //自己是置业顾问
        $this->assign('client', $client);
        $this->assign('optionlog', $optionlog);
        $this->display();
    }

    //客户获得佣金信息
    public function Commission() {
        $this->check_login(false);
        $brokeruser = $this->getUserinfo();
        $this->assign('brokeruser', $brokeruser);
        $db_commission = M('broker_commission');
        $tmp = $db_commission->where('bid= ' . $this->bid . ' AND tjuid= ' . $this->loginuserid . ' AND status =1 AND client_status >= 6 AND money > 0')->select();
        $this->assign('mylits', $tmp);
        $this->display();
    }

    //绑定银行卡页面
    public function bindCard() {
        $this->check_login(false);
        $brokeruser = $this->getUserinfo();
        $this->assign('brokeruser', $brokeruser);
        $this->display();
    }

    //绑定银行卡 处理信息
    public function bindCarding() {
        //1参数出错 2推荐信息保存失败 0 OK
        if (!($this->bid > 0) || empty($this->token) || empty($this->wecha_id)) {
            echo 1;
            exit();
        }
        $datas['bank_truename'] = $this->_post('baccount', 'trim');
        $datas['bank_cardnum'] = $this->_post('bcode', 'trim');
        $datas['bank_name'] = $this->_post('bname', 'trim');
        $tmp = M('Broker_user')->where(array('id' => $this->loginuserid, 'bid' => $this->bid))->save($datas);
        if ($tmp) {
            echo 0;
        } else {
            echo "保存失败请重试";
        }
    }

    //取介绍描述
    public function Description() {
        $desc = intval($this->_get('desc', 'trim'));
        $desc = $desc == 1 || $desc == 2 ? $desc : 1;
        $Brokerarr = M('Broker')->where(array('id' => $this->bid, 'token' => $this->token))->find();
        $desctitle = "活动细则";
        if ($desc == 1) {
            //$desc = html_entity_decode(htmlspecialchars_decode($Brokerarr['ruledesc']));
            $desc = $Brokerarr['ruledesc'];
        } elseif ($desc == 2) {
            //$desc = html_entity_decode(htmlspecialchars_decode($Brokerarr['registration']));
            $desc = $Brokerarr['registration'];
            $desctitle = "注册协议";
        }
        $this->assign('desctitle', $desctitle);
        $this->assign('desc', $desc);
        $this->display();
    }

    //置业顾问改变客户状态
    public function changStatus() {
        if (!($this->bid > 0) || empty($this->token) || empty($this->wecha_id)) {
            $this->dexit(array('error' => 1, 'msg' => '参数出错!'));
        }
        $clientid = $this->_post('clientid', 'intval');
        $nowstatus = $this->_post('nowstatus', 'intval');
        $tostatus = $this->_post('tostatus', 'intval');
        $insertdata = array();
        $db_client = M('broker_client');
        $clientarr = $db_client->where(array('id' => $clientid, 'bid' => $this->bid))->find();
        if ($clientarr['status'] == $nowstatus) {
            $insertdata['bid'] = $clientarr['bid'];
            $insertdata['tjuid'] = $clientarr['tjuid'];
            $insertdata['clientid'] = $clientarr['id'];
            $insertdata['client_name'] = $clientarr['cname'];
            $insertdata['client_tel'] = $clientarr['ctel'];
            $tostatus = $clientarr['status'] + 1;
            $insertdata['client_status'] = $tostatus;
            $insertdata['proid'] = $clientarr['proid'];
            $tjuidarr = $this->getUserinfo($clientarr['tjuid']);
            $insertdata['tjname'] = $tjuidarr['username'];
            //$insertdata['tjtel']=$tjuidarr['tel'];
            $tmp = M('broker_item')->where(array('id' => $clientarr['proid']))->find();
            $insertdata['proname'] = $tmp['xmname'];
            $verifyarr = $this->getUserinfo();
            $insertdata['verifyname'] = $verifyarr['username'];
            $insertdata['verifytel'] = $verifyarr['tel'];
            if ($tostatus == 6) {
                if ($tmp['xmtype'] == 1) {
                    $insertdata['status'] = 0; //需要审核一下
                    $insertdata['money'] = $tmp['xmnum'];
                } else {
                    $insertdata['status'] = 1; //不需要审核一下
                    $insertdata['money'] = 0;
                }
            } else {
                $insertdata['status'] = 1; //不需要审核一下
                $insertdata['money'] = 0;
            }
            $insertdata['addtime'] = time();
            $insertid = M('broker_commission')->add($insertdata);
            $db_client->where(array('id' => $clientid, 'bid' => $this->bid))->save(array('status' => $tostatus));
            $this->dexit(array('error' => 0, 'msg' => '操作成功!', 'inid' => $insertid));
        }
        $this->dexit(array('error' => 1, 'msg' => '修改状态出错了'));
    }

    //监控用户登录状态
    private function check_login($is_end = true) {
        $brokerarr = M('Broker')->where(array('token' => $this->token, 'id' => $this->bid))->find();
        if (empty($brokerarr) || $brokerarr['isdel'] == 1) {
            $this->exitdisplay('活动已经被删除了');
        }
        if ($is_end && ($brokerarr['enddate'] < time())) {
            $this->exitdisplay('活动已经结束了');
        }
        $bid = $_SESSION['MicroBroker_bid' . $this->wecha_id];
        $brokeruser = $this->getUserinfo();
        if ($bid != $this->bid) {
            Header("Location:" . $this->siteUrl . U('MicroBroker/home', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
        } elseif ($this->loginuserid > 0) {
            if ($brokeruser['status'] == 1) {
                $this->exitdisplay('您已经被活动管理人员拉入黑名单了！');
            }
            if (empty($brokeruser)) {
                cookie($this->thisopenduser, 0);
            }
            return true;
        } else {
            //$wherearr = array('token' => $this->token, 'bid' => $this->bid, 'wecha_id' => $this->wecha_id);
            //$userarr = M('Broker_user')->where($wherearr)->find();
            if (!empty($brokeruser) && is_array($brokeruser)) {
                Header("Location:" . $this->siteUrl . U('MicroBroker/login', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
            } else {
                Header("Location:" . $this->siteUrl . U('MicroBroker/Register', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bid' => $this->bid)));
            }
        }
    }

    //分享key  最长32
    public function getKey($length = 16) {
        $str = substr(md5(time() . mt_rand(1000, 9999)), 0, $length);
        return $str;
    }

    //json格式输出封装函数
    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }

}

?>