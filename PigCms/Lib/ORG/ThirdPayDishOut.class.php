<?php

class ThirdPayDishOut {

    public function index($orderid, $paytype = '', $third_id = '') {
        $wecha_id = '';
        $token = '';
        if ($order = M('dish_order')->where(array('orderid' => $orderid))->find()) {
            //TODO 发货的短信提醒
            $token = $order['token'];
            $wecha_id = $order['wecha_id'];
            //$order['paid']=1;
            $Dishcompany = M('Dish_company')->where(array('cid' => $order['cid']))->find();
            $kconoff = $Dishcompany['kconoff'];
            if ($order['paid']) {
                $temp = unserialize($order['info']);
                $tmparr = array('token' => $token, 'cid' => $order['cid'], 'order_id' => $order['id'], 'paytype' => $order['paytype']);
                $log_db = M('Dishout_salelog');
                if (!empty($temp) && is_array($temp)) {
                    $DishDb = M('Dish');
					$mDishSet = ThirdPayDishOut::getDishMainCompany($token);
                    foreach ($temp as $kk => $vv) {
                        $did = isset($vv['did']) ? $vv['did'] : $kk;
                        $dishofcid = $order['cid'];
                        if (($mDishSet['cid'] != $order['cid']) && ($mDishSet['dishsame'] == 1)) {
                            $dishofcid = $mDishSet['cid'];
                            $kconoff = $mDishSet['kconoff'];
                        }
                        $tmpdish = $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->find();
                        if ($kconoff && !empty($tmpdish) && ($tmpdish['instock'] > 0)) {
                            $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->setDec('instock', $vv['num']);
                        }
                        $logarr = array(
                            'did' => isset($vv['did']) ? $vv['did'] : $kk, 'nums' => $vv['num'],
                            'unitprice' => $vv['price'], 'money' => $vv['num'] * $vv['price'], 'dname' => $vv['name'],
                            'addtime' => $order['time'], 'addtimestr' => date('Y-m-d H:i:s', $order['time']), 'comefrom' => 0
                        );
                        $savelogarr = array_merge($tmparr, $logarr);
                        $log_db->add($savelogarr);
                    }
                }
                $company = M('Company')->where(array('token' => $token, 'id' => $order['cid']))->find();
                if (empty($company) || !is_array($company)) {
                    header('Location:' . U('DishOut/index', array('token' => $token, 'wecha_id' => $wecha_id)));
                }

                Sms::sendSms($token, "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理", $company['mp']);
                $model = new templateNews();
				$siteurl=$_SERVER['HTTP_HOST'];
				$siteurl=strtolower($siteurl);
				if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
				$siteurl=rtrim($siteurl,'/');
                /*$model->sendTempMsg('OPENTM202521011', array('href' =>U('DishOut/myOrder', array('token' => $token, 'wecha_id' => $wecha_id, 'cid' => $order['cid']), true, false, true), 'wecha_id' => $wecha_id, 'first' => '外卖订餐交易提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '支付成功，感谢您的光临，欢迎下次再次光临！'));*/
				$model->sendTempMsg('OPENTM202521011', array('href' =>				$siteurl.'/index.php?g=Wap&m=DishOut&a=myOrder&token='.$token.'&wecha_id='.$wecha_id.'&cid='. $order['cid'], 'wecha_id' => $wecha_id, 'first' => '外卖订餐交易提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '支付成功，感谢您的光临，欢迎下次再次光临！'));

                $op = new orderPrint();
                $msg = array('companyname' => $company['name'], 'des' => htmlspecialchars_decode($order['des'], ENT_QUOTES),
                    'companytel' => $company['tel'], 'truename' => htmlspecialchars_decode($order['name'], ENT_QUOTES),
                    'tel' => $order['tel'], 'address' => htmlspecialchars_decode($order['address'], ENT_QUOTES),
                    'buytime' => $order['time'], 'orderid' => $order['orderid'],
                    'sendtime' => $order['reservetime'] > 0 ? $order['reservetime'] : '尽快送达', 'price' => $order['price'],
                    'total' => $order['total'], 'typename' => '外卖',
                    'ptype' => $order['paytype'], 'list' => $temp);
                $msg = ArrayToStr::array_to_str($msg, $order['paid']);
                $op->printit($token, $order['cid'], 'DishOut', $msg, $order['paid']);


                /*厨房打印菜单*/
                if ($kitchens_list = D('Dish_kitchen')->where(array('cid' => $order['cid']))->select()) {
	                $t_list = array();
	                foreach ($temp as $dish) $t_list[$dish['kitchen_id']][] = $dish;

                    $kitchens = array();
                    foreach ($kitchens_list as $kit_row) $kitchens[$kit_row['id']] = $kit_row;
	                
	                $print_msg = array('des' => htmlspecialchars_decode($order['des'], ENT_QUOTES), 
	                		'truename' => htmlspecialchars_decode($order['name'], ENT_QUOTES), 
	                		'tel' => $order['tel'], 
	                		'address' => htmlspecialchars_decode($order['address'], ENT_QUOTES), 
	                		'buytime' => $order['time'], 
	                		'orderid' => $order['orderid'], 
	                		'sendtime' => $order['reservetime'] > 0 ? $order['reservetime'] : '尽快送达', 'typename' => '外卖',
	                    	'ptype' => $order['paytype']);
	                
	                foreach ($t_list as $k => $rowset) {
	                	if ($k) {
	                		if (isset($kitchens[$k]) && $kitchens[$k]['status']) {
	                			for ($i = 0; $i < count($rowset); $i++) {
			                		$msg = $print_msg;
			                		$msg['list'][] = $rowset[$i];
			                		$msg = ArrayToStr::array_to_str($msg, $order['paid']);
			                		$op->printit($token, $order['cid'], 'DishOut', $msg, $order['paid'], '', $k);
	                			}
	                		} else {
		                		$msg = $print_msg;
		                		$msg['list'] = $rowset;
		                		$msg = ArrayToStr::array_to_str($msg, $order['paid']);
		                		$op->printit($token, $order['cid'], 'DishOut', $msg, $order['paid'], '', $k);
	                			
	                		}
	                	}
	                }
                }
                /*厨房打印菜单*/
            }
            header('Location:' . U('DishOut/myOrder', array('token' => $token, 'wecha_id' => $wecha_id, 'cid' => $order['cid'])));
        } else {
            exit('抱歉,订单信息出错');
        }
    }
    /** 获取主餐厅配置信息* */
    private function getDishMainCompany($token) {
        $MainC = M('Company')->where(array('token' => $token, 'isbranch' => 0))->find();
        $m_cid = $MainC['id'];
		
        unset($MainC);
        $mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
        unset($m_cid);
        return $mDishC;
    }

}
?>

