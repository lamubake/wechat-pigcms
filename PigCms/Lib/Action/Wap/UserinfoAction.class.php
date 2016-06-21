<?php
class UserinfoAction extends WapAction{
	public function _initialize() {
		parent::_initialize();
		session('wapupload',1);
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
	}
	
	public function index()
	{
		$cardid = intval($this->_get('cardid'));
		$data['wecha_id'] = $this->wecha_id;
		$data['token'] = $this->token;
		//
		$cardInfoRow['wecha_id'] = $this->wecha_id;
		$cardInfoRow['token'] = $this->token;
		$cardInfoRow['cardid'] = $cardid;
		$card = D('Member_card_create');
		$cardinfo = $card->where($cardInfoRow)->find(); //是否领取过
		$this->assign('cardInfo', $cardinfo);
		//
		$member_card_set_db = M('Member_card_set');
		$thisCard = $member_card_set_db->where(array('token' => $this->token,'id' => $cardid))->find();
		if (!$thisCard && $cardid) exit();
		$img = $thisCard['memberinfo'] ? $thisCard['memberinfo'] : 'tpl/Wap/default/common/images/userinfo/fans.jpg';
		
		$where = array('wecha_id' => $this->wecha_id, 'token' => $this->token);
		$userTB = D('Userinfo');
		$userinfo = $userTB->where($where)->find();
		
		$is_check = (empty($cardinfo) && $thisCard['is_check']) ? true :false;
		$html_layout = $this->html_layout($this->token, $userinfo, $is_check);
		
		$this->assign('verify', $html_layout['verify']);
		$this->assign('formData', $html_layout['string']);
		
		
        $this->assign('isFuwu', $this->isFuwu);
		$this->assign('cardnum', $cardinfo['number']);
		$this->assign('is_check', $thisCard['is_check']);
		$this->assign('homepic', $img);
		$this->assign('info', $userinfo);
		$this->assign('cardid', $cardid);
		$this->assign('giftCard', $this->getGiftCard($cardid));
		//redirect url
		if (isset($_GET['redirect'])) {
			$urlinfo = explode('|', $_GET['redirect']);
			$parmArr = explode(',', $urlinfo[1]);
			$parms = array('token' => $cardInfoRow['token'], 'wecha_id' => $cardInfoRow['wecha_id']);
			if ($parmArr) {
				foreach ($parmArr as $pa) {
					$pas = explode(':', $pa);
					$parms[$pas[0]] = $pas[1];
				}
			}
			$redirectUrl = U($urlinfo[0], $parms);
			$this->assign('redirectUrl', $redirectUrl);
		}
		//
		if (IS_POST) {
			$attact = isset($_POST['data']) ? $_POST['data'] : array();
			unset($attact[0]);
			foreach ($attact as $index => $row) {
				switch ($row['item_name']) {
					case 'wechaname':
						$data['wechaname'] = $row['val'];
						break;
					case 'tel':
						$data['tel'] = $row['val'];
						break;
					case 'portrait':
						$data['portrait'] = $row['val'];
						break;
					case 'truename':
						$data['truename'] = $row['val'];
						break;
					case 'qq':
						$data['qq'] = $row['val'];
						break;
// 					case 'paypass':
// 						$data['paypass'] = md5($row['val']);
// 						break;
					case 'sex':
						if ($row['val'] == '男') {
							$data['sex'] = 1;
						} elseif ($row['val'] == '女') {
							$data['sex'] = 2;
						} elseif ($row['val'] == '其他') {
							$data['sex'] = 3;
						}
						break;
					case 'bornyear':
						$data['bornyear'] = $row['val'];
						break;
					case 'bornmonth':
						$data['bornmonth'] = $row['val'];
						break;
					case 'bornday':
						$data['bornday'] = $row['val'];
						break;
					case 'address':
						$data['address'] = $row['val'];
						break;
					case 'origin':
						$data['origin'] = $row['val'];
						break;
// 					default:
// 						$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : '';
					
				}
			}

			if($this->_post('paypass') != ''){
				$data['paypass'] = md5($this->_post('paypass'));
			}
			$data['regtime'] = time();
 			//如果会员卡不为空[更新]
 			//写入两个表 Userinfo Member_card_create 
 			if ($cardid == 0) {
 				if ($userinfo) {
 					if (!$userTB->where($where)->save($data)) exit(7);
 					$this->save_attach($userinfo['id'], $attact);
 				} else {
 					if (!($uid = $userTB->add($data))) exit(7);
 					$this->save_attach($uid, $attact);
 				}
 				
 				S('fans_'.$this->token.'_'.$this->wecha_id,NULL);
 				echo 1;exit;
 			} else {
 				if ($cardinfo) { //如果Member_card_create 不为空，说明领过卡，但是可以修改会员信息
 					if (!$userTB->where($where)->save($data)) exit(7);
					$this->save_attach($userinfo['id'], $attact);
					S('fans_'.$this->token.'_'.$this->wecha_id,NULL);
					echo 1;exit;
 				} else {
 					if ($thisCard['is_check'] == '1' && empty($cardinfo)) {
 						$code = $this->_post('code', 'trim,strtolower');
 						if($this->_check_code($code) == false){
 							echo 5;exit;
 						}
 						if ($data['tel'] != session('sms_phone')) {
 							echo 6;exit();
 						}
 					}
 			
 					Sms::sendSms($this->token,'有新的会员领了会员卡');
 					$card = M('Member_card_create')->field('id,number')->where("token='".$this->token."' and cardid=".intval($_POST['cardid'])." and wecha_id = ''")->order('id ASC')->find();
 					//
 					$userScore = 0;
 					if ($userinfo) $userScore = intval($userinfo['total_score']);
 					
 					if (!$card) {
 						echo 3;exit;
 					} else {
 						if (intval($thisCard['miniscore']) == 0 || $userScore > intval($thisCard['minscore'])){
 							$data['getcardtime'] = time();
 							if ($userinfo) {
 								if (!$userTB->where($where)->save($data)) exit(7);
 								$this->save_attach($userinfo['id'], $attact);
 							} else {
								if (!($uid = $userTB->add($data))) exit(7);
 								$this->save_attach($uid, $attact);
 							}

 							$is_card = M('Member_card_create')->where($where)->find();
							$api_item = 0;
							if (empty($is_card)) {
								M('Member_card_create')->where(array('id' => $card['id']))->save(array('wecha_id' => $this->wecha_id));
								$now = time();
								$gwhere = array('token' => $this->token, 'cardid' => $cardid, 'is_open' => '1', 'start' => array('lt', $now), 'end' => array('gt', $now));
								$gifts 	= M('Member_card_gifts')->where($gwhere)->select();
								foreach($gifts as $key => $value){
									if($value['type'] == "1"){
										//赠积分
										$arr = array();
										$arr['itemid']	= 0;
										$arr['token']	= $this->token;
										$arr['wecha_id']= $this->wecha_id;
										$arr['expense']	= 0;
										$arr['time']	= $now;
										$arr['cat']		= 3;
										$arr['staffid']	= 0;
										$arr['notes']	= '开卡赠送积分';
										$arr['score']	= $value['item_value'];
										M('Member_card_use_record')->add($arr);
										M('Userinfo')->where(array('token' => $this->token,'wecha_id' => $this->wecha_id))->setInc('total_score', $arr['score']);
									} else {
										$cinfo = M('Member_card_coupon')->where(array('token' => $this->token, 'id' => $value['item_value']))->find();
										if ($cinfo['is_weixin'] == 0) {
											$data['token']		= $this->token;
											$data['wecha_id']	= $this->wecha_id;
											$data['coupon_id']	= $value['item_value'];
											$data['is_use']		= '0';
											$data['cardid']		= $cardid;
											$data['add_time']	= $now;

											if ($value['item_attr'] == 1) {
												$data['coupon_type'] = '1';
											} elseif ($value['item_attr'] == 2) {
												$data['coupon_type'] = '3';
											} else {
												$data['coupon_type'] = '2';
											}

									        $data['cancel_code']= $this->_create_code(12);
											//赠卷
											M('Member_card_coupon_record')->add($data);
										} else {
											$api_item = 1;
										}
									}
								}
							} else {
								M('Member_card_create')->where(array('token' => $this->token,'wecha_id' => $this->wecha_id))->delete();
								M('Member_card_create')->where(array('id' => $card['id']))->save(array('wecha_id' => $this->wecha_id));
							}
 							S('fans_'.$this->token.'_'.$this->wecha_id, NULL);
 							echo json_encode(array('errCode' => 2,'api_item' => $api_item));
 							exit;
 						} else {
 							echo 4;exit;
 						}
 					}
 				}
 			}
		} else {
			$this->display();	
		}
    }

    private function save_attach($uid, $data)
    {
    	$db = D('Userinfo_attach');
    	$attachs = $db->where(array('uid' => $uid))->select();
    	$list = array();
    	foreach ($attachs as $a) {
    		$list[$a['field_id']] = $a;
    	}
    	foreach ($data as $d) {
    		if (empty($d['id']) || !is_numeric($d['id'])) continue;
    		if ($d['type'] == 'password') $d['val'] = md5($d['val']);
    		if (isset($list[$d['id']])) {
    			$db->where(array('uid' => $uid, 'field_id' => $d['id']))->save(array('uid' => $uid, 'field_id' => $d['id'], 'field_value' => $d['val']));
    			unset($list[$d['id']]);
    		} else {
    			$db->add(array('uid' => $uid, 'field_id' => $d['id'], 'field_value' => $d['val']));
    		}
    	}
    	if ($list) {
    		$ids = array();
    		foreach ($list as $l) {
    			$ids[] = $l['field_id'];
    		}
    		$db->where(array('uid' => $uid, 'field_id' => array('in', $ids)))->delete();
    	}
    }
    
    private function html_layout($token, $userinfo, $is_check = false)
    {
		$fields = M('Member_card_custom_field')->where(array('token' => $token))->order('sort DESC')->select();
		if (empty($fields)) {
			$conf = M('Member_card_custom')->where(array('token' => $token))->find();
			if (empty($conf)) {
				$conf = array('wechaname' => 1, 'is_wechaname' => 1, 'tel' => 1, 'is_tel' => 0, 'portrait' => 1, 'is_portrait' => 0, 'truename' => 1, 'is_truename' => 0, 'qq' => 1, 'is_qq' => 0, 'paypass' => 1, 'is_paypass' => 1, 'sex' => 1, 'is_sex' => 0, 
						'bornyear' => 1, 'is_bornyear' => 0, 'bornmonth' => 1, 'is_bornmonth' => 0, 'bornday' => 1, 'is_bornday' => 0, 'address' => 1, 'is_address' => 0, 'origin' => 1, 'is_origin' => 0
				);
			}
			$fields[] = array('field_id' => 'm1', 'item_name' => 'wechaname', 'field_name' => '微信昵称', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['wechaname'], 'is_empty' => $conf['is_wechaname']);
			$fields[] = array('field_id' => 'm2', 'item_name' => 'tel', 'field_name' => '手机号', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['tel'], 'is_empty' => $conf['is_tel']);
			$fields[] = array('field_id' => 'm3', 'item_name' => 'portrait', 'field_name' => '头像', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['portrait'], 'is_empty' => $conf['is_portrait']);
			$fields[] = array('field_id' => 'm4', 'item_name' => 'truename', 'field_name' => '真实姓名', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['truename'], 'is_empty' => $conf['is_truename']);
			$fields[] = array('field_id' => 'm5', 'item_name' => 'qq', 'field_name' => 'QQ号码', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['qq'], 'is_empty' => $conf['is_qq']);
			//$fields[] = array('field_id' => 'm6', 'item_name' => 'paypass', 'field_name' => '支付密码', 'field_option' => '', 'field_type' => 'password', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['paypass'], 'is_empty' => $conf['is_paypass']);
			$fields[] = array('field_id' => 'm7', 'item_name' => 'sex', 'field_name' => '性别', 'field_option' => '男|女|其他', 'field_type' => 'select', 'field_match' => '', 'is_show' => $conf['sex'], 'is_empty' => $conf['is_sex']);
			$fields[] = array('field_id' => 'm8', 'item_name' => 'bornyear', 'field_name' => '出生年', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornyear'], 'is_empty' => $conf['is_bornyear']);
			$fields[] = array('field_id' => 'm9', 'item_name' => 'bornmonth', 'field_name' => '出生月', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornmonth'], 'is_empty' => $conf['is_bornmonth']);
			$fields[] = array('field_id' => 'm10', 'item_name' => 'bornday', 'field_name' => '出生日', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornday'], 'is_empty' => $conf['is_bornday']);
			$fields[] = array('field_id' => 'm10', 'item_name' => 'address', 'field_name' => '地址', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['address'], 'is_empty' => $conf['is_address']);
			$fields[] = array('field_id' => 'm12', 'item_name' => 'origin', 'field_name' => '来源渠道', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['origin'], 'is_empty' => $conf['is_origin']);
		} else {
			if ($conf = M('Member_card_custom')->where(array('token' => $token))->find()) {
				$old_fields[] = array('field_id' => 'm1', 'item_name' => 'wechaname', 'field_name' => '微信昵称', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['wechaname'], 'is_empty' => $conf['is_wechaname']);
				$old_fields[] = array('field_id' => 'm2', 'item_name' => 'tel', 'field_name' => '手机号', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['tel'], 'is_empty' => $conf['is_tel']);
				$old_fields[] = array('field_id' => 'm3', 'item_name' => 'portrait', 'field_name' => '头像', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['portrait'], 'is_empty' => $conf['is_portrait']);
				$old_fields[] = array('field_id' => 'm4', 'item_name' => 'truename', 'field_name' => '真实姓名', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['truename'], 'is_empty' => $conf['is_truename']);
				$old_fields[] = array('field_id' => 'm5', 'item_name' => 'qq', 'field_name' => 'QQ号码', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['qq'], 'is_empty' => $conf['is_qq']);
				//$old_fields[] = array('field_id' => 'm6', 'item_name' => 'paypass', 'field_name' => '支付密码', 'field_option' => '', 'field_type' => 'password', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['paypass'], 'is_empty' => $conf['is_paypass']);
				$old_fields[] = array('field_id' => 'm7', 'item_name' => 'sex', 'field_name' => '性别', 'field_option' => '男|女|其他', 'field_type' => 'select', 'field_match' => '', 'is_show' => $conf['sex'], 'is_empty' => $conf['is_sex']);
				$old_fields[] = array('field_id' => 'm8', 'item_name' => 'bornyear', 'field_name' => '出生年', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornyear'], 'is_empty' => $conf['is_bornyear']);
				$old_fields[] = array('field_id' => 'm9', 'item_name' => 'bornmonth', 'field_name' => '出生月', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornmonth'], 'is_empty' => $conf['is_bornmonth']);
				$old_fields[] = array('field_id' => 'm10', 'item_name' => 'bornday', 'field_name' => '出生日', 'field_option' => '', 'field_type' => 'text', 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornday'], 'is_empty' => $conf['is_bornday']);
			}
			$old_fields && $fields = array_merge($old_fields, $fields);
		}
    	
    	
    	
    	$list = array();
    	if ($userinfo) {
	    	$db = D('Userinfo_attach');
	    	$attachs = $db->where(array('uid' => $userinfo['id']))->select();
	    	foreach ($attachs as $a) {
	    		$list[$a['field_id']] = $a;
	    	}
    	}

		$str = '';
		$arr = array();
		foreach($fields as $key => $value){
			if (empty($value['is_show'])) continue;
			
			switch ($value['item_name']) {
				case 'wechaname':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['wechaname']) ? $userinfo['wechaname'] : '');
					break;
				case 'tel':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['tel']) ? $userinfo['tel'] : '');
					break;
				case 'portrait':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['portrait']) ? $userinfo['portrait'] : '');
					break;
				case 'truename':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['truename']) ? $userinfo['truename'] : '');
					break;
				case 'qq':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['qq']) ? $userinfo['qq'] : '');
					break;
				case 'paypass':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['paypass']) ? $userinfo['paypass'] : '');
					break;
				case 'sex':
					$t = array(1 => '男', '女', '其他');
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($t[$userinfo['sex']]) ? $t[$userinfo['sex']] : '');
					break;
				case 'bornyear':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['bornyear']) ? $userinfo['bornyear'] : '');
					break;
				case 'bornmonth':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['bornmonth']) ? $userinfo['bornmonth'] : '');
					break;
				case 'bornday':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['bornday']) ? $userinfo['bornday'] : '');
					break;
				case 'address':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['address']) ? $userinfo['address'] : '');
					break;
				case 'origin':
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : (isset($userinfo['origin']) ? $userinfo['origin'] : '');
					break;
				default:
					$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : '';
			}
			
			
			
			if ($value['item_name'] == 'portrait') {
				$str .= '<ul class="round" id="ul_portrait" onclick="check_white(this)">';
				$str .= '<li>';
				if ($value['is_empty']) {
					$str .= '<div style="padding:10px 10px 10px 0;"><font color="red">*</font>请设置头像</div>';
				} else {
					$str .= '<div style="padding:10px 10px 10px 0;">请设置头像</div>';
				}
				$str .= '<input type="hidden" value="'. $v .'" id="portrait" name="portrait" size="80" />';
				$str .= '<input type="hidden" id="field_'.$value['field_id'].'" name="field_'.$value['field_id'].'" value="'.$value['field_id'].'">';
				$str .= '<a href="###" onclick="upyunWapPicUpload(\'portrait\',200,200,\'' . $token . '\')" class="a_upload" style="color:red">点击这里上传</a>';
				if ($v != '') {
					$str .= '<div class="por"><img src="' . $v . '" id="portrait_src" /></div>';
				} else {
					$str .= '<div class="por"><img src="/tpl/User/default/common/images/portrait.jpg" id="portrait_src" /></div>';
				}
				
				$str .= '<div style="clear:both"></div>';
				$str .= '或者选择下面头像';
				$str .= '<div style="margin:10px 0 20px 0" id="pors">';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/1.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/2.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/3.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/4.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/5.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/6.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/7.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/8.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/9.jpg" /></div>';
				$str .= '<div class="por"><img onclick="selectpor(this)" src="/tpl/static/portrait/10.jpg" /></div>';
				$str .= '<div style="clear:both"></div>';
				$str .= '</div>';
				$str .= '</li>';
				$str .= '</ul>';
			} else {
			
				$str .= '<li><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">';
				$str .= '<tr><th>';
				if ($value['is_empty']) {
					$str .= '<font color="red">*</font>';
				}
				$str .= $value['field_name'];
				$str .= '</th>';
				$str .= $this->_getInput($value, $v);
				$str .= '<input type="hidden" id="field_'.$value['field_id'].'" name="field_'.$value['field_id'].'" value="'.$value['field_id'].'"></tr>';
				$str .= '</table></li>';
				
				if ($value['item_name'] == 'tel' && $is_check) {
					$str .= '<li>';
					$str .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">';
					$str .= '<tr>';
					$str .= '<th><font color="red">短信验证</font></th>';
					$str .= '<td><input name="code"  class="code" id="code" value=""  type="text" placeholder="效验码"><a class="is_check" href="javascript:void(0);"><em id="num"></em><b>点击获取效验码</b></a></td>';
					$str .= '</tr>';
					$str .= '</table>';
					$str .= '</li>';
				}
			}
			
			
			
			$arr[] = array('id' => $value['field_id'], 'name' => $value['field_name'], 'type' => $value['field_type'], 'item_name' => $value['item_name'], 'match' => $value['field_match'], 'is_empty' => $value['is_empty']);  //js验证信息
		}
		
		return array('string' => $str, 'verify' => $arr);
    }

	/*获取自定义表单*/
	private function _getInput($value, $v){
		$input 	= '';
		switch($value['field_type']){
			case 'text':
				$class = $value['item_name'] == 'tel' ? ' tel' : '';
				$input 	.= '<td><input type="text" class="px' . $class . '" id="field_id_'.$value['field_id'].'" name="field_id_'.$value['field_id'].'" value="' . $v . '" data-empty="' . $value['is_empty'] . '"></td>';
				break;
			case 'password':
				$input 	.= '<td><input type="password" class="px" id="field_id_'.$value['field_id'].'" name="field_id_'.$value['field_id'].'" value=""  data-empty="' . $value['is_empty'] . '"></td>';
				break;
			case 'textarea':
				$input 	.= '<td><textarea name="field_id_'.$value['field_id'].'" id="field_id_'.$value['field_id'].'" rows="4" cols="25"  data-empty="' . $value['is_empty'] . '">' . $v . '</textarea></td>';
				break;
			case 'checkbox':
				$option = explode('|', $value['field_option']);
				$v_arr = explode('|', $v);
				$input .= '<td height="39">';
				for ($i=0; $i < count($option); $i++) {
					if ($v_arr && in_array($option[$i], $v_arr)) {
						$checked = 'checked=true';
					} else $checked = '';
					
					$input .= '<label><input type="checkbox" name="field_id_'.$value['field_id'].'[]" class="field_id_'.$value['field_id'].'" value="'.$option[$i].'" '.$checked.' />' . $option[$i] . '</label>　';
				}
				$input .= '</td>';
				break;
			case 'radio':
				$option = explode('|', $value['field_option']);
				$input .= '<td height="39">';
				for ($i=0; $i<count($option); $i++) {
					if ($v) {
						$checked = $v == $option[$i] ? 'checked=true' : '';
					} else {
						$checked = $i == 0 ? 'checked=true' : '';
					}
					
					$input .= '<label><input type="radio" name="field_id_'.$value['field_id'].'" class="field_id_'.$value['field_id'].'" value="'.$option[$i].'" '.$checked.' />' . $option[$i] . '</label>　';
				}
				$input .= '</td>';
				break;
			case 'select':
				$input 	.= '<td><select name="field_id_'.$value['field_id'].'" id="field_id_'.$value['field_id'].'" class="dropdown-select"><option value="">请选择..</option>';
				$op_arr	= explode('|',$value['field_option']);
				$num	= count($op_arr);
				if($num > 0){
					for ($i = 0; $i < $num; $i++) {
						if ($v && $v == $op_arr[$i]) {
							$input .= '<option value="' . $op_arr[$i] . '" selected>' . $op_arr[$i] . '</option>';
						} else {
							$input .= '<option value="' . $op_arr[$i] . '">' . $op_arr[$i] . '</option>';
						}
					}
				}
				$input  .='</select></td>';
				break;
			case 'date':
				$v = $v ? $v : date('Y-m-d');
				$input .= '<td><input type="text" class="px" name="field_id_'.$value['field_id'].'" id="field_id_'.$value['field_id'].'" value="'. $v .'" onClick="WdatePicker()"  data-empty="' . $value['is_empty'] . '"/></td>';
		}

		return $input;
	}
    
   	public function getGiftCard($cardid){
		$now 	= time();
		$gwhere = array('token'=>$this->token,'cardid'=>$cardid,'is_open'=>'1' ,'type'=>'2','start'=>array('lt',$now),'end'=>array('gt',$now));
		$gifts 	= M('Member_card_gifts')->where($gwhere)->select();
		
		$coupons    	= new  WechatCoupons($this->wxuser);
		$js_api_item 	= '';
		$api_item 	= '';
		foreach($gifts as $key=>$value){	
			$cinfo 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$value['item_value']))->find();
			if($cinfo['is_weixin'] == 1){
				$js_api_item .= '{cardId:"'.$cinfo['card_id'].'",cardExt:\''.$coupons->cardSign($cinfo['card_id'],$cinfo['cardid']).'\'},';
			}
		}
		$api_item 	= rtrim($js_api_item,',');
		return $api_item;
	}

    function get_code(){
    	$code_db 	= M('Sms_code');
    	$code 		= $this->_create_code();
    	$phone 		= $this->_post('phone');
    	$data['code'] 			= $code;
    	$data['token'] 			= $this->token;
    	$data['wecha_id'] 		= $this->wecha_id;
    	$data['create_time'] 	= time();
    	$data['action'] 		= 'userCard';
    	session('sms_phone', $phone);
    	$result 	= array();
	
    	$where 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'action'=>$data['action']);
    	$last_info 	= $code_db->where($where)->order('create_time desc')->find();
    	if(($last_info['create_time']+60) > time()){
    		$result['error']	= -1;
    		$result['info']		= '请不要频繁获取效验码';
    	}else{
    		$code_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'action'=>$data['action'],'is_use'=>'0'))->save(array('is_use'=>'1'));	
    		if($code_db->add($data)){
    			$msg 	= '您的领卡效验码为：'.$code.'，验证码5分钟内有效，如非本人操作，请无视这条消息。';
    			$result['error']	= 0;
    			$result['info']		= '';
    			
    			Sms::sendSms($this->token,$msg,$phone);
    		}
    		
    	}
    	
    	echo json_encode($result);
    }
    
    /* @param  intval length 效验码长度
     * @param  string type  效验码类型  number数字, string字母, mingle数字、字母混合
     * @return string
     */
	function _create_code($length=4,$type="number"){
		$array = array(
			'number' => '0123456789',
			'string' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'mixed' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		);
		$string = $array[$type];
		$count = strlen($string)-1;
		$rand = '';
		for ($i = 0; $i < $length; $i++) {
			$rand .= $string[mt_rand(0, $count)];
		}
		return $rand;
	}
    /* @param  string code 效验码
     * @param  string time 过期时间
     * @return boolean
     */
    function _check_code($code,$time=300){
    	$code_db 	= M('Sms_code');
    	$action 	= 'userCard';
    	$last_time 	= time()-$time;
    	$where 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'action'=>$action,'is_use'=>'0','create_time'=>array('gt',$last_time));
    	$true_code 	= $code_db->where($where)->getField('code');
    	
    	if(!empty($true_code) && $true_code == $code){
    		return true;
    	}else{
    		return false;
    	}
    }
} // end class UserinfoAction

?>