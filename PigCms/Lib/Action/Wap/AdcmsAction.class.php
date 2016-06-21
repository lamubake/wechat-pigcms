<?php
class AdcmsAction extends WapAction
{
    public $token;
    public $set1;
    protected function _initialize()
    {
        parent::_initialize();
		$ip = get_client_ip();
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip . '';
        $res = file_get_contents($url);
        $arr = json_decode($res, true);
        $mycity = $arr['data']['city'];
		$myprovince = $arr['data']['region'];
		$myxian = $arr['data']['county'];
        $set1 = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->set = $set;
        $set = M('adcms_black')->where(array('token' => $_GET['token']))->find();
        $black = explode('、', $set['wecha_id']);
		$quyu = explode('、', $set1['quyu']);
		$quyus = explode('、', $set1['quyus']);
		$quyux = explode('、', $set1['quyux']);
		if(empty( $this->wecha_id)){	
			$this->error('获取参数错误');	exit;
		}
			
        if (in_array($this->wecha_id, $black)) {
            $this->error('您因为违规操作，已经被列入黑名单！');
            die;
        }
		if(!$_GET['invite1']){
			 if (!in_array($myprovince, $quyus) && !empty($quyus[0])) {
				$this->error('您所在的区域无法参与该活动');
				die;
			 }	
			 if (!in_array($mycity, $quyu) && !empty($quyu[0])) {
				$this->error('您所在的区域无法参与该活动');
				die;
			 }	
			 if (!in_array($quyux, $quyux)&& !empty($quyux[0])) {
				$this->error('您所在的区域无法参与该活动');
				die;
			 }	
		}
    }
	
    public function index()
    {   $ip = get_client_ip();
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip . '';
        $res = file_get_contents($url);
        $arr = json_decode($res, true);
        $mycity = $arr['data']['city'];
		$myprovince = $arr['data']['region'];
		$myxian = $arr['data']['county'];
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $time = time();
        $token = $_GET['token'];
        //
        $fans = M('userinfo')->where(array('token' => $token, 'wecha_id' => $this->wecha_id))->find();
        $date['name'] = $fans['wechaname'];
        $date['headpic'] = $fans['portrait'];
        $date['wecha_id'] = $this->wecha_id;
        $date['token'] = $token;
		$date['city'] =$mycity;
		$date['province'] = $myprovince;
        $date['time'] = time();
        $date['invite1'] = $_GET['invite1'];
        $access_token = $this->getAccessToken();
        $info = M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $this->wecha_id))->find();
        $invite_user = M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $info['invite1']))->find();
        //历史个人收益
        $total_1 = M('adcms_record')->where(array('token' => $token, 'wecha_id' => $this->wecha_id))->sum('alipay_money');
        $total = $total_1 + $info['balance'];
        //历史粉丝收益
        $total_fans = M('adcms_userinfo')->where(array('token' => $token, 'invite1' => $this->wecha_id))->sum('total_balance');
        //dump($access_token);exit;
        $set = M('adcms_set')->where(array('token' => $token))->find();
        if (empty($info)) {
            $a = M('adcms_userinfo')->add($date);
            if ($a) {
                if ($date['invite1']) {
                    $txt = '{"touser":"' . $_GET['invite1'] . '","template_id":"' . $set['xiaxianid'] . '","url":"' . C('site_url') . '/index.php?g=Wap&m=Adcms&a=index&token=' . $_GET['token'] . '","topcolor":"#FF0033","data":{"first": {"value":"恭喜您，有新会员加入您的团队","color":"#173177"},"keyword1": {"value":"' . $date['name'] . '","color":"#FF0033"},"keyword2": {"value":"' . ($time = date('Y年m月d日 H:i:s', $time) . '","color":"#FF0033"},"remark": {"value":"感谢您的努力","color":"#173177"}}}');
                    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token . '';
                    $result = $this->https_post($url, $txt);
                }
            }
        }
        //dump($date);exit;
        $this->total_fans = $total_fans;
        //dump($invite_user);exit;
        $this->invite_user = $invite_user;
        $this->fans = $fans;
        $this->total = $total;
        $this->set = $set;
        $this->info = $info;
		
        $this->display();
    }
    public function lists()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $token = $_GET['token'];
        $type = $_GET['type'];
        $count = M('adcms_list')->where(array('token' => $_GET['token'], 'type' => $type))->order('time ASC ')->count();
        $page = new Page($count, 12);
        $info = M('adcms_list')->where(array('token' => $token, 'type' => $type))->limit($page->firstRow . ',' . $page->listRows)->select();
        foreach ($info as $k => $v) {
            $new_key = intval($k / 4);
            $new[$new_key][] = $v;
        }
        //幻灯片
        $flash = M('adcms_flash')->where(array('token' => $_GET['token']))->order('time ASC ')->select();
        //dump($new);exit;
        $this->info = $new;
        $this->flash = $flash;
        $this->type = $type;
        $this->assign('page', $page->show());
        $this->display();
    }
    public function contents()
    {
        $wecha_id = $this->wecha_id;
        $ip = get_client_ip();
        $token = $_GET['token'];

        $id = $_GET['id'];
        $first = $_GET['first'];
        $info = M('adcms_list')->where(array('token' => $token, 'id' => $id))->find();
        $infos = M('adcms_set')->where(array('token' => $token))->find();
        $userinfo = M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $first))->find();
        $set = M('adcms_set')->where(array('token' => $token))->find();
        //识别分享后别人的阅读
        $ip_find = M('adcms_clickrecord')->where(array('token' => $token, 'pid' => $id, 'ip' => $ip))->find();
        if ($info['adrmb'] - $set['rmb'] > 0) {
            //判断当前的广告费用是否已经消耗完毕
            if ($first) {
                //判断是否从分享的链接进来
                $date['ip'] = $ip;
                $date['pid'] = $id;
                $date['time'] = time();
                $date['first'] = $first;
                $date['token'] = $token;
                $date['adblance'] = $set['rmb'];
                $date['wecha_id'] = $wecha_id;
                $date['invite'] = $userinfo['invite1'];
                if (empty($ip_find)) {
                    //查看IP是否已经存在
                    M('adcms_clickrecord')->add($date);
                    M('adcms_list')->where(array('id' => $id))->setInc('click', 1);
                    $newblance['balance'] = $userinfo['balance'] + $set['rmb'];
                    //有人点击后增加人民币
                    $newblance['total_balance'] = $newblance['balance'];
                    //方便统计粉丝总收益
                    M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $first))->save($newblance);
                    //增加金额
                    $invite1 = M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $userinfo['invite1']))->find();
                    $invite1_blance['balance'] = $set['rmb'] * $set['yj'] / 100 + $invite1['balance'];
                    $invite1_blance['total_balance'] = $invite1_blance['balance'];
                    M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $userinfo['invite1']))->save($invite1_blance);
                    //上线增加金额
                    //判断是否有上级
                    $a = M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $userinfo['invite1']))->find();
                    if ($a) {
                        $adrmb['adrmb'] = $info['adrmb'] - $set['rmb'] + $set['rmb'] * $set['yj'] / 100;
                    } else {
                        $adrmb['adrmb'] = $info['adrmb'] - $set['rmb'];
                    }
                    M('adcms_list')->where(array('token' => $token, 'id' => $id))->save($adrmb);
                }
            }
        } else {
            $this->error('该篇文章广告费用已经消耗完毕，请【' . $info['sjname'] . '】商家尽快充值！');
        }
        $this->info = $info;
        $this->infos = $infos;
        $this->display();
    }
    public function exchange()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $wecha_id = $this->wecha_id;
        $info = M('adcms_userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $wecha_id))->find();
        $tixian = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $set = M('adcms_record')->where(array('token' => $_GET['token'], 'wecha_id' => $wecha_id))->order('time desc')->select();
        $alipay = M('adcms_record')->where(array('token' => $_GET['token'], 'wecha_id' => $wecha_id))->order('time desc')->limit('1')->find();
        $this->alipay = $alipay;
        if (IS_POST) {
            $date['alipay_money'] = $_POST['alipay_money'];
            $date['time'] = time();
            $date['wecha_id'] = $wecha_id;
            $date['token'] = $_GET['token'];
            $date['pid'] = $info['id'];
            $date['headpic'] = $info['headpic'];
            $date['name'] = $info['name'];
            if ($info['balance'] < $date['alipay_money']) {
                $arr = array('status' => -1, 'msg' => '可提现余额不足！');
                $this->ajaxReturn($arr, 'json');
                die;
            } else {
                if ($tixian['txtype'] == '1') {
                    //判断提现方式 1为微信红包
                    //红包接口测试
                    $config = array();
                    $config['nick_name'] = '点赚客';
                    $config['send_name'] = '点赚客';
                    $config['wishing'] = '恭喜您成功提现';
                    $config['act_name'] = '点赚客提现';
                    $config['remark'] = '点赚客提现';
                    $config['token'] = $_GET['token'];
                    $config['openid'] = $this->wecha_id;
                    $config['money'] = $date['alipay_money'];
                    $hb = new Hongbao($config);
                    $res = json_decode($hb->send(), true);
                }
                if ($res['status'] == 'SUCCESS') {
                    $date['type'] = '1';
                    //微信红包提现
                    $date['statue'] = '1';
                    $a = M('adcms_record')->add($date);
                    $money['balance'] = $info['balance'] - $date['alipay_money'];
                    $newmoney = M('adcms_userinfo')->where(array('wecha_id' => $wecha_id))->save($money);
                    $arr = array('status' => 1, 'msg' => '提现成功，红包已经发放，请在公众号聊天窗口领取');
                    $this->ajaxReturn($arr, 'json');
                    die;
                }
                if ($res['status'] == 'FAIL') {
                    $arr = array('status' => 1, 'msg' => $res['msg']);
                    $this->ajaxReturn($arr, 'json');
                    die;
                } else {
                    $date['alipay'] = $_POST['alipay'];
                    $date['type'] = '0';
                    //支付宝提现
                    $a = M('adcms_record')->add($date);
                    $money['balance'] = $info['balance'] - $date['alipay_money'];
                    $newmoney = M('adcms_userinfo')->where(array('wecha_id' => $wecha_id))->save($money);
                    $arr = array('status' => 1, 'msg' => '申请提现成功,等待商家打款！');
                    $this->ajaxReturn($arr, 'json');
                    die;
                }
            }
        }
        $this->set = $set;
        $this->tixian = $tixian;
        $this->info = $info;
        $this->display();
    }
    public function hezuo()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        if (IS_POST) {
            $date['address'] = $_POST['address'];
            $date['name'] = $_POST['name'];
            $date['tel'] = $_POST['tel'];
            $date['qq'] = $_POST['qq'];
            $date['time'] = time();
            $date['token'] = $_GET['token'];
            $date['beizhu'] = $_POST['beizhu'];
            $a = M('adcms_hezuo')->add($date);
        }
        if ($a) {
            $arr = array('status' => 1, 'msg' => '提交成功！请等待平台商与您联系');
            $this->ajaxReturn($arr, 'json');
            die;
        }
        $this->display();
    }
    public function rwen()
    {
        $wecha_id = $this->wecha_id;
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        if (IS_POST) {
            $date['url'] = $_POST['url'];
            $date['wecha_id'] = $wecha_id;
            $date['token'] = $_GET['token'];
            $a = M('adcms_rwen')->add($date);
        }
        if ($a) {
            $arr = array('status' => 1, 'msg' => '提交成功！');
            $this->ajaxReturn($arr, 'json');
            die;
        }
        $this->display();
    }
    public function income_fans()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $token = $_GET['token'];
        $wecha_id = $this->wecha_id;
        $info = M('adcms_userinfo')->where(array('token' => $token, 'invite1' => $wecha_id))->order('total_balance desc')->select();
        $set = M('adcms_set')->where(array('token' => $token))->find();
        foreach ($info as $key => $value) {
            $resault[$key]['xybalance'] = $value['total_balance'] * $set['yj'] / 100;
            $resault[$key]['total_balance'] = $value['total_balance'];
            $resault[$key]['headpic'] = $value['headpic'];
            $resault[$key]['name'] = $value['name'];
        }
        //历史粉丝收益
        $total_fans = M('adcms_userinfo')->where(array('token' => $token, 'invite1' => $this->wecha_id))->sum('total_balance') * $set['yj'] / 100;
        $timedays = strtotime(date('Y-m-d', time()));
        //今天0点的时间点
        $time = array('egt', $timedays);
        $total_income = M('adcms_clickrecord')->where(array('token' => $token, 'invite' => $wecha_id, 'time' => $time))->sum('adblance') * $set['yj'] / 100;
        //今日收益
        $timedaye = $timedays - 3600 * 24;
        //昨天0点
        $yes_time = array(array('gt', $timedaye), array('lt', $timedays));
        $total_yestincome = M('adcms_clickrecord')->where(array('token' => $token, 'invite' => $wecha_id, 'time' => $yes_time))->sum('adblance') * $set['yj'] / 100;
        //昨日收益
        $thirty = $timedays - 3600 * 720;
        //30天前
        $thirty_time = array('egt', $thirty);
        $total_thirtyincome = M('adcms_clickrecord')->where(array('token' => $token, 'invite' => $wecha_id, 'time' => $thirty_time))->sum('adblance') * $set['yj'] / 100;
        //30日收益
        $myfans = M('adcms_userinfo')->where(array('token' => $token, 'invite1' => $this->wecha_id))->count();
        //我的下线总数
        $this->myfans = $myfans;
        $this->info = $resault;
        $this->total_fans = $total_fans;
        $this->total_income = $total_income;
        $this->total_yestincome = $total_yestincome;
        $this->total_thirtyincome = $total_thirtyincome;
        $this->display();
    }
    public function top()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $token = $_GET['token'];
        $wecha_id = $this->wecha_id;
        $info = M('adcms_userinfo')->where(array('token' => $token))->order('total_balance desc')->limit('30')->select();
        $this->info = $info;
        $this->display();
    }
    public function news_lists()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $token = $_GET['token'];
        $count = M('adcms_news')->where(array('token' => $_GET['token']))->count();
        $page = new Page($count, 16);
        $info = M('adcms_news')->where(array('token' => $token))->limit($page->firstRow . ',' . $page->listRows)->select();
        //dump($new);exit;
        $this->info = $info;
        $this->assign('page', $page->show());
        $this->display();
    }
    public function notice()
    {
        $this->display();
    }
    public function news_info()
    {
        $id = $_GET['id'];
        $token = $_GET['token'];
        $db = M('adcms_news');
        $db->where(array('id' => $id))->setInc('click', 1);
        $info = $db->where(array('token' => $token, 'id' => $id))->find();
        $this->info = $info;
        $this->display();
    }
    public function income()
    {
        $bu = M('adcms_set')->where(array('token' => $_GET['token']))->find();
        $this->bu = $bu;
        $wecha_id = $this->wecha_id;
        $token = $_GET['token'];
        $timedays = strtotime(date('Y-m-d', time()));
        //今天0点的时间点
        $time = array('egt', $timedays);
        $set = M('adcms_set')->where(array('token' => $token))->find();
        $total_income = M('adcms_clickrecord')->where(array('token' => $token, 'first' => $wecha_id, 'time' => $time))->sum('adblance') + M('adcms_clickrecord')->where(array('token' => $token, 'invite' => $wecha_id, 'time' => $time))->sum('adblance') * $set['yj'] / 100;
        //今日收益
        $timedaye = $timedays - 3600 * 24;
        //昨天0点
        $yes_time = array(array('gt', $timedaye), array('lt', $timedays));
        $total_yestincome = M('adcms_clickrecord')->where(array('token' => $token, 'first' => $wecha_id, 'time' => $yes_time))->sum('adblance') + M('adcms_clickrecord')->where(array('token' => $token, 'invite' => $wecha_id, 'time' => $yes_time))->sum('adblance') * $set['yj'] / 100;
        //昨日收益
        $thirty = $timedays - 3600 * 720;
        //30天前
        $thirty_time = array('egt', $thirty);
        $total_thirtyincome = M('adcms_clickrecord')->where(array('token' => $token, 'first' => $wecha_id, 'time' => $thirty_time))->sum('adblance') + M('adcms_clickrecord')->where(array('token' => $token, 'invite' => $wecha_id, 'time' => $thirty_time))->sum('adblance') * $set['yj'] / 100;
        //昨日收益
        $info = M('adcms_userinfo')->where(array('token' => $token, 'wecha_id' => $this->wecha_id))->find();
        //历史个人收益
        $total_1 = M('adcms_record')->where(array('token' => $token, 'wecha_id' => $this->wecha_id))->sum('alipay_money');
        $total = $total_1 + $info['balance'];
        $this->total_income = $total_income;
        $this->total_yestincome = $total_yestincome;
        $this->total_thirtyincome = $total_thirtyincome;
        $this->total = $total;
        $this->display();
    }
	
	public function invite()
	{
		$wecha_id = $this->wecha_id;
		if ($this->wecha_id) {
			;
			$erweima = M('adcms_userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
			

			if ($erweima['erweima'] == false) {
				include 'PigCms/Lib/ORG/php/phpqrcode.php';
				$host = $_SERVER['HTTP_HOST'];
				
				$value= C('site_url') . U('Wap/Adcms/index', array('token' => $this->token, 'invite1' => $this->wecha_id));
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 4;
				$imgurl = './uploads/erweima/shareerweima/share' . $mytwid . '.png';
				QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize, 2);
				$logo = $imgurl;
				$QR = './uploads/erweima/shareimg/share.jpg';

				if ($logo !== false) {
					$QR = imagecreatefromstring(file_get_contents($QR));
					$logo = imagecreatefromstring(file_get_contents($logo));
					$QR_width = imagesx($QR);
					$QR_height = imagesy($QR);
					$logo_width = imagesx($logo);
					$logo_height = imagesy($logo);
					$logo_qr_width = $QR_width / 2;
					$scale = $logo_width / $logo_qr_width;
					$logo_qr_height = $logo_height / $scale;
					$from_width = ($QR_width - $logo_qr_width) / 2;
					$from_height = 280;
					imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
				}

				imagepng($QR, './uploads/erweima/shareimg/share' . $wecha_id . '.png');
				
				$data['erweima'] = '/uploads/erweima/shareimg/share' . $wecha_id . '.png';
				
				D('adcms_userinfo')->where(array('token'=>$this->token,'wecha_id'=>$wecha_id))->save($data);
			}
		}

		
		$this->assign('viewUrl', $erweima);
		$this->display();
	}
    public function invite1()
    {
        $wecha_id = $this->wecha_id;
        $info = M('adcms_userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $wecha_id))->find();
        if (empty($info)) {
            $this->error('您还未注册成为点赚客粉丝，本平台将自动为您注册！', U('Adcms/index', array('token' => $this->token)));
            die;
        }
        $viewUrl = C('site_url') . U('Wap/Adcms/index', array('token' => $this->token, 'invite1' => $this->wecha_id));
        $viewUrl = urldecode($viewUrl);
        $this->assign('viewUrl', $viewUrl);
        $this->display();
    }
    public function QRcode()
    {
        include './PigCms/Lib/ORG/phpqrcode.php';
        $viewUrl = C('site_url') . U('Wap/Adcms/index', array('token' => $this->token, 'invite1' => $this->wecha_id));
        $url = urldecode($viewUrl);
        QRcode::png($url, false, 0, 8);
    }
    public function https_post($url, $data)
    {
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
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
        $errorno = curl_errno($ch);
        if ($errorno) {
            return array('rt' => false, 'errorno' => $errorno);
        } else {
            $js = json_decode($tmpInfo, 1);
            if ($js['errcode'] == '0') {
                return array('rt' => true, 'errorno' => 0);
            } else {
                $errmsg = GetErrorMsg::wx_error_msg($js['errcode']);
                //$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$errmsg);
                return array($js);
            }
        }
    }
	
    public function getAccessToken()
    {
        $info = M('wxuser')->where(array('token' => $this->token))->find();
        $this->_appids = $info['appid'];
        $this->_secrets = $info['appsecret'];
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->_appids . '&secret=' . $this->_secrets . '';
		$res = file_get_contents($url);
		$arr = json_decode($res, true);
		$access_token = $arr['access_token'];
        //
        return $access_token;
    }
}