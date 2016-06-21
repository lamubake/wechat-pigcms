<?php
class HongbaoqiyeAction extends WapAction
{
    public $token;
    protected function _initialize()
    {
        parent::_initialize();
        $ip = get_client_ip();
        $info = M('hongbaoqiye_set')->where(array('token' => $_GET['token']))->find();
        $user_info = M('userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $this->wecha_id))->find();
        $blackip = explode(',', $info['blackip']);
        $blackuser = explode(',', $info['blackuser']);
        if (in_array($ip, $blackip) && $info['blackip']) {
            $this->error('您已经被加入黑名单了！');
            die;
        }
        if (in_array($user_info['wechaname'], $blackuser) && $info['blackuser']) {
            $this->error('您已经被加入黑名单了！');
            die;
        }
		if(empty( $this->wecha_id)){
			
		$this->error('获取参数错误');	exit;
			}
		
    }
    public function index()
    {
        $ip = get_client_ip();
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip . '';
        $res = file_get_contents($url);
        $arr = json_decode($res, true);
        $mycity = $arr['data']['city'];
		$myprovince = $arr['data']['region'];
		$myxian = $arr['data']['county'];
        $pid = $_GET['pid'];
        $info = M('hongbaoqiye_set')->where(array('token' => $_GET['token']))->find();
        $record = M('hongbaoqiye_record')->where(array('token' => $_GET['token']))->order('time desc')->limit('5')->select();
        $zzs = M('hongbaoqiye_zzs')->where(array('token' => $_GET['token']))->select();
        $user_info = M('userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $this->wecha_id))->find();
        $pid_info = M('userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $_GET['pid']))->find();
        //搜索pid头像
        if ($_GET['pid']) {
            $user_click = M('hongbaoqiye_click')->where(array('token' => $_GET['token'], 'wecha_id' => $this->wecha_id))->find();
            //是否帮好友点击过了
            if (!$user_click) {
                $date['pid'] = $_GET['pid'];
                $date['token'] = $_GET['token'];
                $date['time'] = time();
                $date['wecha_id'] = $this->wecha_id;
                $total_hb = M('hongbaoqiye_click')->add($date);
            }
        }
        $timedays = strtotime(date('Y-m-d', time()));
        $timeday24 = $timedays + 3600 * 24;
        $click_total = M('hongbaoqiye_click')->where(array('token' => $_GET['token'], 'pid' => $this->wecha_id, 'time' => array('between', '' . $timedays . ',' . $timeday24 . '')))->count();
        //今日点击总数
        $total_hb = M('hongbaoqiye_record')->where(array('token' => $_GET['token'], 'wecha_id' => $this->wecha_id, 'time' => array('between', '' . $timedays . ',' . $timeday24 . '')))->count();
        //已经领取红包次数
        $lingqu_total = M('hongbaoqiye_click')->where(array('token' => $_GET['token'], 'wecha_id' => $this->wecha_id, 'time' => array('between', '' . $timedays . ',' . $timeday24 . '')))->count();
        //今日领取次数
        $lingqu_total = floor($info['ci'] + $click_total * $info['choujiangci'] / $info['haoyou']);
        //总共可以领取次
        //领取间隔时间计算
		

       if (!$this->isSubscribe()) {
			$is_sub = 2;
		}else{
			$is_sub = 1;
		}
		
		//dump($this->isSubscribe());
		//die;
		
		
      
	    $dotime = $shijian['time'] + $info['jiange'] - time();
      
	  //区域查找
	   $city = explode('、', $info['quyu']);//市
	   $province = explode('、', $info['quyus']);//省
	   $xian = explode('、', $info['quyux']);//县
	   //dump($arr);exit;
	    if ($info['enddate'] < time()) {
            $this->div = '		  <div class="submit" style="background:#bd2822;" onclick="show(\'#tips\',1)"> 活动已经结束啦~ </div>	';
            $this->tt='亲~活动结束啦~你可以关注我们的公众号获取更多好玩的活动';
	    } elseif ($info['statdate'] > time()) {
            $this->div = '		  <div class="submit" style="background:#bd2822;" onclick="show(\'#tips\',1)"> 活动还没开始呐~</div> ';
             $this->tt='亲~活动还没开始呐~喝杯茶休息休息';
	    } elseif (!in_array($arr['data']['region'], $province)&& !empty($province[0])) {
		
            $this->div = '		  <div class="submit" style="background:#bd2822;" onclick="show(\'#city\',1)">您所在的区域无法参加哦 </div> ';
        } elseif (!in_array($arr['data']['city'],$city )&&  !empty($city[0]) ) {
				
            $this->div = '		  <div class="submit" style="background:#bd2822;" onclick="show(\'#city\',1)">您所在的区域无法参加哦 </div> ';
        } 
		elseif (!in_array($arr['data']['county'],$xian )&& !empty($xian[0])) {
            $this->div = '		  <div class="submit" style="background:#bd2822;" onclick="show(\'#city\',1)">您所在的区域无法参加哦 </div> ';
        } 
		
		
		
		elseif ($lingqu_total - $total_hb <= 0) {
            $this->div = '		   <div class="submit" style="background:#bd2822;" onclick="show(\'#tips\',1)"> 您没有参与机会了~</div> <div class="submit" style="background:#349800;" onclick="show(\'#share\',1)">
     点我获取更多机会</div> ';
             $this->tt='亲~您的抽取机会全部用完啦！';
		} elseif (!$pid && $info['state_subscribe'] == '0' && $is_sub == 1) {
            //dump($dotime);exit;
            $this->div = "\t\t  <div class=\"submit\" style=\"background:#cccccc;\" onclick=\"hb()\" endtime={$dotime} id=\"take_do\">Loading...</div>";
        } elseif ($info['state_subscribe'] == '0' && $is_sub == 1) {
            $this->div = "\t\t  <div class=\"submit\" style=\"background:#cccccc;\" onclick=\"hb()\" endtime={$dotime} id=\"take_do\">Loading...</div>";
        } else {
            $url = $info['url'];
            $this->div = "\t\t  <a href={$url}> <div class=\"submit\" style=\"background:#bd2822;\">我也要领取~</div> </a>";
        }
        $time = time();
        $this->jihui = $lingqu_total - $total_hb;
        $this->info = $info;
		 $this->as = $as;
        $this->mycity = $mycity;
		 $this->myprovince = $myprovince;
		 $this->myxian = $myxian;
		 $this->token = $_GET['token'];
        $this->pid_info = $pid_info;
        $this->pid = $pid;
        $this->wecha_id = $this->wecha_id;
        $this->record = $record;
        $this->lingqucishu = $lingqucishu;
        $this->zzs = $zzs;
        $this->user_info = $user_info;
        $this->time = $time;
        $this->display();
    }
	 public function pm()
    {
		
		$pid_info = M('userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $_GET['wecha_id']))->find();
	$info = M('hongbaoqiye_set')->where(array('token' => $_GET['token']))->find();
	 $record = M('hongbaoqiye_record')->where(array('token' => $_GET['token']))->field('sum(money) as money,name,head_pic')->group("wecha_id")->order('money desc')->limit('5')->select();	
	 $this->record = $record;
	
	 $this->pid_info = $pid_info;
	 
	  $this->info = $info;	
	   $this->display();
		}
	 public function todaypm()
    {
		$timedays = strtotime(date('Y-m-d', time()));
        //今天0点的时间点
        $time = array('egt', $timedays);
		$pid_info = M('userinfo')->where(array('token' => $_GET['token'], 'wecha_id' => $_GET['wecha_id']))->find();
	$info = M('hongbaoqiye_set')->where(array('token' => $_GET['token']))->find();
	 $record = M('hongbaoqiye_record')->where(array('token' => $_GET['token'],'time'=>$time))->field('sum(money) as money,name,head_pic')->group("wecha_id")->order('money desc')->limit('5')->select();	
	 $this->record = $record;
	
	 $this->pid_info = $pid_info;
	 
	  $this->info = $info;	
	   $this->display();
		}	
    public function lingqu()
    {
        //$nowtime=date("H", time());
        //		if((0<$nowtime) and ($nowtime<8)){
        //		 $arr = array('status' => 1, 'msg' =>'0点到8点非领取红包时间');
        //                   echo json_encode($arr);
        //            exit;
        //
        //			}
        $infos = M('hongbaoqiye_set')->where(array('token' => $this->token))->find();
        //概率算法
        $set = M('hongbaoqiye_rand')->where(array('token' => $this->token))->find();
        $prize_arr = array('0' => array('id' => 1, 'prize' => $set['r1'], 'v' => $set['rand1']), '1' => array('id' => 2, 'prize' => $set['r2'], 'v' => $set['rand2']), '2' => array('id' => 3, 'prize' => $set['r3'], 'v' => $set['rand3']), '3' => array('id' => 4, 'prize' => $set['r4'], 'v' => $set['rand4']), '4' => array('id' => 5, 'prize' => $set['r5'], 'v' => $set['rand5']));
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->get_rand($arr);
        //根据概率获取奖项id
        $send_money = $prize_arr[$rid - 1]['prize'];
        //中奖项
        //
        $config = array();
        
        $config['amt_type'] = 'ALL_RAND';
        $config['desc'] = $infos['desc'];
        
        $config['money'] = $send_money;
        $config['openid'] = $this->wecha_id;
        $config['token'] = $this->token;
        $hb = new Hongbaoqiye($config);
        $res = json_decode($hb->send(), true);
		
        if ($res['status'] == 'SUCCESS') {
            $user_info = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
            $liuyan = explode('、', $infos['ly']);
            $num = rand(0, count($liuyan) - 1);
            $date['ly'] = $liuyan[$num];
            $date['wecha_id'] = $this->wecha_id;
            $date['token'] = $this->token;
            $date['time'] = time();
            $date['money'] = $send_money / 100;
            $date['head_pic'] = $user_info['portrait'];
            $date['name'] = $user_info['wechaname'];
            $date['ip'] = get_client_ip();
            $date['pid'] = $_GET['pid'];
            $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . get_client_ip() . '';
            $res = file_get_contents($url);
            $arr = json_decode($res, true);
            $date['province'] = $arr['data']['region'];
            $date['city'] = $arr['data']['city'];
            M('hongbaoqiye_record')->add($date);
            $arr = array('status' => 1, 'msg' => '恭喜你获得' . $date['money'] . '元红包');
            echo json_encode($arr);
            die;
        }
    }
    public function page()
    {     $as = M('hongbaoqiye_record')->where(array('token' => $_GET['token']))->field('sum(money) as money,name,head_pic')->group("wecha_id")->order('money desc')->limit('5')->select();
        $count = M('hongbaoqiye_record')->where(array('token' => $this->token))->count();
        $page = new Page($count, 5);
        $user = M('hongbaoqiye_record')->where(array('token' => $this->token))->order('time desc')->limit('' . ($_GET['p'] - 1) * '5' . ',5')->select();
		
       
		
		
        //$a=<li><a href='javascript:void(0)'><img src=".$info[$i]['id']." alt=''></a></li>
        for ($i = 0; $i < 5 and $user[$i]; $i++) {
            $time = date('m-d H:i:s', $user[$i]['time']);
            $content = $content . '<div class="line"> 
     <div class="head">
      <img src=' . $user[$i]['head_pic'] . ' />
     </div> 
     <div class="right"> 
      <div class="info"> 
       <div class="user"> 
        <div class="date">
         ' . $time . '
        </div> 
        <div class="big"> 
         <div class="nickname">
       ' . $user[$i]['name'] . '
         </div> 
        </div> 
         <div class="slogan">  ' . $user[$i]['ly'] . '</div>
       </div> 
       <div class="slogan">
       
       </div> 
      </div> 
     </div> 
    </div>';
        }
        echo $content;
    }
	  public function pages()
    {    
	    
        $count = M('hongbaoqiye_record')->where(array('token' => $this->token))->count();
        $page = new Page($count, 5);
        $user = M('hongbaoqiye_record')->where(array('token' => $_GET['token']))->field('sum(money) as money,name,head_pic,ly')->group("wecha_id")->order('money desc')->limit('' . ($_GET['p'] - 1) * '5' . ',5')->select();
       
		
		$p=$_GET['p'];
        //$a=<li><a href='javascript:void(0)'><img src=".$info[$i]['id']." alt=''></a></li>
        for ($i = 0; $i < 5 and $user[$i]; $i++) {
          $k=($p-1)*5+$i+1;
            $content = $content . '<div class="line"> 
			<div class="head" style="text-align:center;line-height:40px;color: #999;font-size:12px;">
     ' . $k . '
     </div> 
     <div class="head" style="padding-left:-10px;padding-right:10px;">
      <img src=' . $user[$i]['head_pic'] . ' />
     </div> 
     <div class="right"> 
      <div class="info"> 
       <div class="user"> 
        <div class="date">
         ' . $user[$i]['money'] . ' 元
        </div> 
        <div class="big"> 
         <div class="nickname">
       ' . $user[$i]['name'] . '
         </div> 
        </div> 
         <div class="slogan">  ' . $user[$i]['ly'] . '</div>
       </div> 
       <div class="slogan">
       
       </div> 
      </div> 
     </div> 
    </div>';
        }
        echo $content;
    }
	  public function pagess()
    {    $timedays = strtotime(date('Y-m-d', time()));
        //今天0点的时间点
        $time = array('egt', $timedays);
        $count = M('hongbaoqiye_record')->where(array('token' => $this->token,'time'=>$time))->count();
        $page = new Page($count, 5);
        $user = M('hongbaoqiye_record')->where(array('token' => $_GET['token'],'time'=>$time))->field('sum(money) as money,name,head_pic,ly')->group("wecha_id")->order('money desc')->limit('' . ($_GET['p'] - 1) * '5' . ',5')->select();
       
		
		$p=$_GET['p'];
        //$a=<li><a href='javascript:void(0)'><img src=".$info[$i]['id']." alt=''></a></li>
        for ($i = 0; $i < 5 and $user[$i]; $i++) {
          $k=($p-1)*5+$i+1;
            $content = $content . '<div class="line"> 
			<div class="head" style="text-align:center;line-height:40px;color: #999;font-size:12px;">
     ' . $k . '
     </div> 
     <div class="head" style="padding-left:-10px;padding-right:10px;">
      <img src=' . $user[$i]['head_pic'] . ' />
     </div> 
     <div class="right"> 
      <div class="info"> 
       <div class="user"> 
        <div class="date">
         ' . $money . ' 元
        </div> 
        <div class="big"> 
         <div class="nickname">
       ' . $user[$i]['name'] . '
         </div> 
        </div> 
         <div class="slogan">  ' . $user[$i]['ly'] . '</div>
       </div> 
       <div class="slogan">
       
       </div> 
      </div> 
     </div> 
    </div>';
        }
        echo $content;
    }
	  public function cbt()
    {
		$info=M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->getField('hongbaoqiye_qr');
		$infos=M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->getField('wechaname');
		$qr=M('hongbaoqiye_set')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->getField('cbt');
	   
		
		if ($info == false) {
				include 'PigCms/Lib/ORG/phpqrcode.php';
				$host = $_SERVER['HTTP_HOST'];
				//$value= C('site_url') . U('Wap/Hongbaoqiye/index', array('token' => $this->token, 'pid' => $this->wecha_id));
				$value = 'http://'.$host.'/index.php?g=Wap&m=Hongbaoqiye&a=index&token='.$this->token.'&pid='.$this->wecha_id.'';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 4;
				$imgurl = './uploads/erweima/hongbao/share' . $this->wecha_id . '.png';
				QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize, 2);
				$logo = $imgurl;
				$font = 'PigCms/Lib/ORG/Fuwu/lotusphp_runtime/Captcha/fonts/msyhbd.ttf';
			
				$QR = './tpl/static/Hongbaoqiye/hongbao.jpg';
		}
		
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
		$white = imagecolorallocate($QR, 255,255, 255);  
		imagettftext($QR, 30, 0, $from_width-20, $logo_qr_height, $white, $font, $infos.'的红包'); 
		imagepng($QR, './uploads/erweima/hongbao/share' . $this->wecha_id . '.png');

		$data['hongbaoqiye_qr'] = '/uploads/erweima/hongbao/share' . $this->wecha_id . '.png';
		
		$a=	D('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->save($data);
			
	
		 $this->info = $info;
		 $this->token = $_GET['token'];
		 $this->display();	
		
		}
	 public function QRcode()
    {
        include './PigCms/Lib/ORG/phpqrcode.php';
        $viewUrl = C('site_url') . U('Wap/Hongbaoqiye/index', array('token' => $this->token, 'pid' => $this->wecha_id));
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
    public function get_rand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($proArr);
        return $result;
    }
    public function getAccessToken()
    {
        $info = M('wxuser')->where(array('token' => $this->token))->find();
        $this->_appids = $info['appid'];
        $this->_secrets = $info['appsecret'];
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->_appids . '&secret=' . $this->_secrets . '';
		$res = file_get_contents($url);
		$arr = json_decode($res, true);
		$access_token = $arr['access_token'];
        return $access_token;
    }
}