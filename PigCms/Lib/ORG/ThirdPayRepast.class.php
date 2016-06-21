<?php

class ThirdPayRepast {
    /*     * *tmporderid处理多次付款问题*** */

    public function index($orderid, $paytype = '', $third_id = '') {
        $wecha_id = '';
        $token = '';
        $cid = 0;
        $dish_order_db = M('dish_order');
        if ($order = $dish_order_db->where(array('tmporderid' => $orderid))->find()) {
            //TODO 发货的短信提醒
            $token = $order['token'];
            $wecha_id = $order['wecha_id'];
            $cid = $order['cid'];
            $company = M('Company')->where(array('token' => $token, 'id' => $cid))->find();
            $Dishcompany = M('Dish_company')->where(array('cid' => $cid))->find();
            $kconoff = $Dishcompany['kconoff'];
            $orderid = $order['orderid'];
			$price = $order['price'] - $order['havepaid']; //获取支付价格;方便下面增加计算;
            if (empty($company) || !is_array($company)) {
                header('Location:' . U('Repast/myOrders', array('token' => $token, 'wecha_id' => $wecha_id)));
            }
            $temp = !empty($order['info']) ? unserialize($order['info']) : array();
            $temp = isset($temp['list']) ? $temp['list'] : $temp;
            //$order['paid'] = 1;
            if ($order['paid']) {
                if (!empty($temp) && is_array($temp)) {
                    $log_db = M('Dishout_salelog');
                    $tmparr = array('token' => $token, 'cid' => $order['cid'], 'order_id' => $order['id'], 'paytype' => $order['paytype']);
                    $DishDb = M('Dish');
                    $mDishSet = ThirdPayRepast::getDishMainCompany($token);
                    foreach ($temp as $kk => $vv) {
                        $did = isset($vv['did']) ? $vv['did'] : $kk;
                        if ($did > 0) {
                            $flag = isset($vv['flag']) ? $vv['flag'] : '';
                            $newk = $flag . 'jc' . $did;
                            if (!($order['paycount'] > 0) || ($kk == $newk)) {

                                $dishofcid = $cid;
                                if (($mDishSet['cid'] != $cid) && ($mDishSet['dishsame'] == 1)) {
                                    $dishofcid = $mDishSet['cid'];
                                    $kconoff = $mDishSet['kconoff'];
                                }
                                $tmpdish = $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->find();
                                if ($kconoff && !empty($tmpdish) && ($tmpdish['instock'] > 0)) {
                                    $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->setDec('instock', $vv['num']);
                                }
                                $logarr = array(
                                    'did' => $did, 'nums' => $vv['num'],
                                    'unitprice' => $vv['price'], 'money' => $vv['num'] * $vv['price'], 'dname' => $vv['name'],
                                    'addtime' => $order['time'], 'addtimestr' => date('Y-m-d H:i:s', $order['time']), 'comefrom' => 1
                                );
                                $savelogarr = array_merge($tmparr, $logarr);
                                $log_db->add($savelogarr);
                            }
                        }
                    }
                    $dish_order_db->where(array('id' => $order['id'], 'cid' => $order['cid']))->setInc('paycount', 1);
					$dish_order_db->where(array('id' => $order['id'], 'cid' => $order['cid']))->setInc('havepaid', $price);//此行自行增加 注意此处
                }
                if (($order['takeaway'] == 2) && ($order['isover'] == 2)) {
                    M('Dining_table')->where(array('id' => $order['tableid'], 'cid' => $order['cid']))->save(array('status' => 0));
                } elseif (($order['takeaway'] == 2) && ($order['isover'] == 1)) {
                    $dish_order_db->where(array('id' => $order['id'], 'cid' => $order['cid']))->save(array('paid' => 0));
                }
                if ((empty($temp) || ((count($temp) == 1) && isset($temp['table']))) && ($temp['total'] == 0)) {
                    $temp = false;
                    $order['total'] = 1;
                    $bookTable = $order['price'];
                } elseif (($order['takeaway'] == 2) && ($order['advancepay'] > 0)) {
                    $bookTable = false;
                    if ($order['paycount'] == 1) {
                        $realpay = $myorder['price'] - $myorder['havepaid'];
                    } elseif ($order['paycount'] == 0) {
                        $advancepay = $order['advancepay'];
                    }
                    $dish_order_db->where(array('id' => $order['id'], 'cid' => $order['cid']))->save(array('havepaid' => $order['advancepay'], 'advancepay' => 0));
                } else {
                    $bookTable = false;
                    if (isset($temp['table']) && !empty($temp['table'])) {
                        $bookTable = $temp['table']['price'];
                        unset($temp['table']);
                    }
                    $realpay = $myorder['price'] - $myorder['havepaid'];
                }
                $op = new orderPrint();
                $msg = array('companyname' => $company['name'], 'des' => $order['des'], 'companytel' => $company['tel'], 'truename' => $order['name'], 'tel' => $order['tel'], 'address' => $order['address'], 'buytime' => $order['time'], 'orderid' => $order['orderid'], 'bookTable' => $bookTable, 'price' => $order['price'], 'total' => $order['total'], 'ptype' => $order['paytype'],'list' => $temp, 'advancepay' => isset($advancepay) ? $advancepay : false, 'realpay' => isset($realpay) ? $realpay : false);
                $msg['typename'] = $order['takeaway'] == 2 ? '现场点餐' : '预约点餐';
                if ($order['takeaway'] == 1) {
                    $msg['sendtime'] = $order['reservetime'];
                    $msg['typename'] = '外卖';
                } elseif ($order['takeaway'] == 0) {
                    $tmpstr = ThirdPayRepast::GetCanTimeByoid($cid, $order['id'], $order['tableid']);
                    $msg['reservestr'] = $tmpstr ? date('Y-m-d', $order['reservetime']) .' '. $tmpstr : date('Y-m-d H:i', $order['reservetime']);
                }
                if ($order['tableid']) {
                    $t_table = M("Dining_table")->where(array('id' => $order['tableid']))->find();
                    $msg['tablename'] = isset($t_table['name']) ? $t_table['name'] : '';
                }
                $print_msg = $msg;
                $msg = ArrayToStr::array_to_str($msg, 1);
                $op->printit($token, $cid, 'Repast', $msg, 1);

                /*厨房打印菜单*/
                if ($kitchens_list = D('Dish_kitchen')->where(array('cid' => $cid))->select()) {
	                $j_list = $t_list = array();
	                foreach ($temp as $dish) {
	                	if (isset($dish['j_c']) && $dish['j_c']) $j_list[$dish['kitchen_id']][] = $dish;
	                	$t_list[$dish['kitchen_id']][] = $dish;
	                }
	                
	                $kitchens = array();
	                foreach ($kitchens_list as $kit_row) $kitchens[$kit_row['id']] = $kit_row;
	                
	                unset($print_msg['companyname'], $print_msg['companytel'], $print_msg['price'], $print_msg['total'], $print_msg['list']);
	                $t_list = $j_list ? $j_list : $t_list;
	                foreach ($t_list as $k => $rowset) {
	                	if ($k) {
	                		if (isset($kitchens[$k]) && $kitchens[$k]['status']) {
	                			for ($i = 0; $i < count($rowset); $i++) {
			                		$msg = $print_msg;
			                		$msg['list'][] = $rowset[$i];
			                		$msg = ArrayToStr::array_to_str($msg, 1);
			                		$op->printit($token, $order['cid'], 'Repast', $msg, 1, '', $k);
	                			}
	                		} else {
		                		$msg = $print_msg;
		                		$msg['list'] = $rowset;
		                		$msg = ArrayToStr::array_to_str($msg, 1);
		                		$op->printit($token, $order['cid'], 'Repast', $msg, 1, '', $k);
	                		}
	                	}
	                }
                }
                /*厨房打印菜单*/
                
                Sms::sendSms($token . "_" . $cid, "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
                $siteurl = $_SERVER['HTTP_HOST'];
                $siteurl = strtolower($siteurl);
                if (strpos($siteurl, "http:") === false && strpos($siteurl, "https:") === false)
                    $siteurl = 'http://' . $siteurl;
                $siteurl = rtrim($siteurl, '/');
                $model = new templateNews();
                /*$model->sendTempMsg('OPENTM202521011', array('href' =>U('Repast/myOrders', array('token' => $token, 'wecha_id' => $wecha_id, 'cid' => $cid), true, false, true), 'wecha_id' => $wecha_id, 'first' => '餐饮订餐交易提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '支付成功，感谢您的光临，欢迎下次再次光临！'));*/
				$model->sendTempMsg('OPENTM202521011', array('href' =>$siteurl.'/index.php?g=Wap&m=Repast&a=myOrders&token='.$token.'&wecha_id='.$wecha_id.'&cid='. $cid, 'wecha_id' => $wecha_id, 'first' => '餐饮订餐交易提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '支付成功，感谢您的光临，欢迎下次再次光临！'));
            }
            header('Location:' . U('Repast/myOrders', array('token' => $token, 'wecha_id' => $wecha_id, 'cid' => $cid)));
        } else {
            exit('订单不存在');
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

    /** 获取预定餐的时间描述* */
    private function GetCanTimeByoid($cid, $oid, $tableid) {
        $tmp = M('Dish_table')->where(array('orderid' => $oid, 'cid' => $cid, 'tableid' => $tableid))->find();
        if (!empty($tmp) && ($tmp['dn_id'] > 0)) {
            $NameC = M('Dish_name')->where(array('id' => $tmp['dn_id'], 'cid' => $cid))->find();
            if (!empty($NameC)) {
                return $NameC['name'];
            }
        }
        return false;
    }

}
?>

