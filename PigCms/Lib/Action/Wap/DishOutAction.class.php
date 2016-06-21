<?php

/**
 * **外卖前台处理控制器
 * **LiHongShun
 * **2015-01-10
 * */
class DishOutAction extends WapAction {

    public $session_dish_info;
    public $_cid = 0;
    public $company;
    public $mshop;

    public function _initialize() {

        parent::_initialize();


        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($agent, "MicroMessenger")) {
            $this->assign('iswxbrowse', true);
        } else {
            $this->assign('iswxbrowse', false);
        }
        $this->_cid = $_SESSION["session_shop_{$this->token}"];
        $this->_cid = $this->_cid > 0 ? $this->_cid : 0;
        $this->assign('cid', $this->_cid);
        $this->shopmanage = $_SESSION["manage_shop{$this->_cid}_{$this->token}"];
        $this->shopmanage = !empty($this->shopmanage) ? unserialize($this->shopmanage) : false;
        $this->session_dish_info = "session_dish_{$this->_cid}_info_{$this->token}";
    }

    /**
     * 餐厅分布
     */
    public function index() {
        $company = M('Company')->where("token='{$this->token}' AND display=1 AND (`business_type` LIKE '%DishOut%' OR `business_type`='')")->select();
        if (count($company) == 1) {
            $this->redirect(U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $company['0']['id'], 'wecha_id' => $this->wecha_id)));
        } else {
            $nowlat = $this->_get('nowlat') ? floatval($this->_get('nowlat', "trim")) : 0;
            $nowlng = $this->_get('nowlng') ? floatval($this->_get('nowlng', "trim")) : 0;
            if (($nowlat > 0) && ($nowlng > 0)) {
                $tmpe = array();
                foreach ($company as $kk => $vv) {
                    $tmpd = $this->getDistance_map($nowlat, $nowlng, $vv['latitude'], $vv['longitude']);
                    $tmpdstr = $tmpd > 1000 ? (round(floatval($tmpd / 1000), 2) . ' km') : (intval($tmpd) . " m");
                    $vv['distance'] = $tmpd;
                    $vv['distancestr'] = $tmpdstr;
                    $company[$kk] = $vv;
                    $tmpe[$kk] = $tmpd;
                }
                asort($tmpe);
                $newCy = array();
                foreach ($tmpe as $tk => $tv) {
                    $newCy[] = $company[$tk];
                }
                $company = !empty($newCy) ? $newCy : $company;
                $this->assign('is_dwei', true);
            }
            $this->assign('company', $company);
            $this->assign('metaTitle', '餐厅分布');
            $this->display();
        }
    }

    /*     * 计算两经纬度间的距离* */

    private function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b) {
        //R是地球半径（米）
        $R = 6377830;
        $pk = doubleval(180 / 3.1415926);

        $a1 = doubleval($lat_a / $pk);
        $a2 = doubleval($lng_a / $pk);
        $b1 = doubleval($lat_b / $pk);
        $b2 = doubleval($lng_b / $pk);

        $t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
        $t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
        $t3 = doubleval(sin($a1) * sin($b1));
        $tt = doubleval(acos($t1 + $t2 + $t3));

        return round($R * $tt);
    }

    /**
     * 我的门店
     */
    public function MyShop() {
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        if (($cid > 0) && ($cid == $this->_cid)) {
            $outset = $this->outManage($cid);
            $company = $this->getCompany($cid);
            $imgarr = !empty($outset['shopimg']) ? unserialize($outset['shopimg']) : array();
            if (!empty($imgarr) && !empty($imgarr['tourl'])) {
                foreach ($imgarr['tourl'] as $ukk => $uvv) {
                    $imgarr['tourl'][$ukk] = $this->getLink(htmlspecialchars_decode($uvv, ENT_QUOTES));
                }
            }
            $outinfo = array();
            $outinfo['id'] = $cid;
            $timestr = '';
            /* if ($outset['zc_sdate'] > 0) {
              $timestr = date('H:i', $outset['zc_sdate']);
              } else {
              $timestr = '00:00';
              }

              if (($outset['zc_edate'] > 0)) {
              $timestr.=' ~ ' . date('H:i', $outset['zc_edate']);
              } else {
              $timestr.=' ~ 23:59';
              } */

            $timestr = $outset['stimestr'] . ' ~ ' . $outset['etimestr'];
			$timestr=!empty($outset['stime2str']) ? $timestr. '&nbsp;&nbsp;' . $outset['stime2str'] . ' ~ ' . $outset['etime2str']:$timestr;
            $outinfo['timestr'] = $timestr;
            $outinfo['sendtime'] = $outset['sendtime'];
            $outinfo['removing'] = $outset['removing'];
            $outinfo['stype'] = $outset['stype'];
            $outinfo['pricing'] = $outset['pricing'];
            $outinfo['tel'] = $company['tel'];
            $outinfo['address'] = $company['address'];
            $outinfo['latitude'] = $company['latitude'];
            $outinfo['longitude'] = $company['longitude'];
            $outinfo['intro'] = $company['intro'];
            $outinfo['logourl'] = $company['logourl'];
            $outinfo['name'] = $company['name'];
            $outinfo['mp'] = $company['mp'];
            $outinfo['area'] = $outset['area'] ? htmlspecialchars_decode($outset['area'], ENT_QUOTES) : '';
            unset($outset, $company);
            $this->assign('shopinfo', $outinfo);
            $this->assign('shopimg', $imgarr);
            $this->assign('metaTitle', '店面详情');
            $this->display();
        } else {
            $this->exitdisplay('没有获取到相关门店信息');
        }
    }

    /** 获取主店餐饮配置信息* */
    private function getDishMainCompany($cache = true) {
        $mDishC = $_SESSION["session_Maindish_{$this->token}"];
        $mDishC = !empty($mDishC) ? unserialize($mDishC) : false;
        if ($cache && !empty($mDishC)) {
            return $DishC;
        } else {
            $MainC = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
            $m_cid = $MainC['id'];
            unset($MainC);
            $mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
            unset($m_cid);
            if ($cache) {
                $_SESSION["session_Maindish_{$this->token}"] = !empty($mDishC) ? serialize($mDishC) : '';
            } else {
                $_SESSION["session_Maindish_{$this->token}"] = '';
            }
            return $mDishC;
        }
    }

    /*     * **获取打印设置**** */

    private function getPrinter_set($cid, $cache = true) {
        $PsetC = $_SESSION["PrinterSet_{$this->token}_{$cid}"];
        $PsetC = !empty($PsetC) ? unserialize($PsetC) : false;
        if ($cache && !empty($PsetC)) {
            return $PsetC;
        } else {
            $PsetC = M('Orderprinter')->where(array('token' => $this->token, 'companyid' => $cid))->find();
            if ($cache) {
                $_SESSION["PrinterSet_{$this->token}_{$cid}"] = !empty($PsetC) ? serialize($PsetC) : '';
            } else {
                $_SESSION["PrinterSet_{$this->token}_{$cid}"] = '';
            }
            return $PsetC;
        }
    }

    /**
     * 点餐页
     */
    public function dishMenu() {
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        $outset = $this->outManage($cid);
        $nows = strtotime(date('Y-m-d H:i'));
        $timeerrorstr = '';
        if ($outset['zc_sdate'] > 0) {
            $sf = date('H:i', $outset['zc_sdate']);
            $setime = strtotime(date('Y-m-d ') . $sf);
            if ($nows < $setime) {
				 $this->exitdisplay('抱歉！尚未到营业时间！');
            }
        }
		 $fetime=0;
        if ($outset['zc_edate'] > 0) {
            $ef = date('H:i', $outset['zc_edate']);
            $fetime=$setime = strtotime(date('Y-m-d ') . $ef);
            if ($nows > $setime) {
                $timeerrorstr = '抱歉！已经过了营业时间了！';
            } elseif(isset($outset['wc_sdate']) && ($outset['wc_sdate'] > 0)) {
                $timeerrorstr = '';
            }
        }

		if (!empty($timeerrorstr) && (!isset($outset['wc_sdate']) || !($outset['wc_sdate'] > 0))) {
            $this->exitdisplay($timeerrorstr);
        }

        if (isset($outset['wc_sdate']) && ($outset['wc_sdate'] > 0)) {
            $sf = date('H:i', $outset['wc_sdate']);
            $setime = strtotime(date('Y-m-d ') . $sf);
            if ($nows < $setime) {
                $timeerrorstr = '抱歉！尚未到营业时间！';
            }
			if(empty($outset['zc_edate']) && !empty($outset['zc_sdate'])  && ($nows < $setime)){
				$timeerrorstr = '';
			}
			if(empty($outset['zc_sdate']) && ($nows > $setime)){
				$timeerrorstr = '';
			}
			if($nows > $setime){
				$timeerrorstr = '';
			}
			if($nows < $fetime){
				$timeerrorstr = '';
			}	
        }
		
        if (isset($outset['wc_edate']) && ($outset['wc_edate'] > 0)) {
            $ef = date('H:i', $outset['wc_edate']);
            $setime = strtotime(date('Y-m-d ') . $ef);
            if ($nows > $setime) {
                $timeerrorstr = '抱歉！已经过了营业时间了！';
            }
        }
        if (!empty($timeerrorstr)) {
            $this->exitdisplay($timeerrorstr);
        }

        $company = $this->getCompany($cid);
        if ($company && is_array($company)) {
            $_SESSION["session_shop_{$this->token}"] = $cid;
        }
        $DishC = $this->getDishCompany($cid); /*         * 餐厅设置信息* */
        $kconoff = $DishC['kconoff'];
        $Mcompany = $this->getDishMainCompany(false);
        $dishofcid = $cid;
        if (($Mcompany['cid'] != $cid) && ($Mcompany['dishsame'] == 1)) {
            $dishofcid = $Mcompany['cid']; /*             * *开启分店统一主店 菜品功能** */
            $kconoff = $Mcompany['kconoff'];
        }
        /** 分类* */
        $dish_sort = M('Dish_sort')->where(array('cid' => $dishofcid))->order("`sort` ASC")->select();
        /** 菜单* */
        $dish = M('Dish')->where(array('cid' => $dishofcid, 'isopen' => 1, 'istakeout' => 1))->order("`sort` ASC")->select();
        /*         * 统计这个月的销售情况,菜销售的份数**** */
        $starttime = strtotime(date('Y-m') . "-01 00:00:00"); /*         * 月开始时间* */
        $t = date('t');
        $endtime = strtotime(date('Y-m') . "-" . $t . " 23:59:59"); /*         * 月结束时间* */
        $Model = new Model();
        $sqlstr = "select cid,did,sum(nums) as tnums from " . C('DB_PREFIX') . "dishout_salelog where cid=" . $cid . " AND token='" . $this->token . "' AND addtime>=" . $starttime . " AND addtime<=" . $endtime . " group by did";
        $tmp = $Model->query($sqlstr);
        $newtmp = array();
        if (!empty($tmp)) {
            foreach ($tmp AS $vv) {
                $newtmp[$vv['did']] = $vv['tnums'];
            }
        }
        $fenleiarr = array();
        if (is_array($dish_sort)) {
            foreach ($dish_sort as $sk => $sv) {
                $fenleiarr[$sv['id']] = $sv['name'];
            }
        }
        $isHave = $_SESSION[$this->session_dish_info];
        $isHave = $isHave && !empty($isHave) ? unserialize($isHave) : array();
        $isHavePt = !empty($isHave) ? $isHave['pt'] : array();
        $isHaveTj = !empty($isHave) ? $isHave['tj'] : array();
        $disharr = $dztjtmp = array();
        if (is_array($dish)) {
            foreach ($dish as $dk => $dv) {
                $dv['sortname'] = $fenleiarr[$dv['sid']];
                $dv['sortname'] = $dv['sortname'] ? $dv['sortname'] : '无';
                if (array_key_exists($dv['id'], $isHavePt)) {
                    $dv['ornum'] = $isHavePt[$dv['id']]['ornum'];
                }
                if (array_key_exists($dv['id'], $newtmp)) {
                    $dv['m_sale'] = $newtmp[$dv['id']];
                } else {
                    $dv['m_sale'] = 0;
                }
                if (array_key_exists($dv['sid'], $disharr)) {
                    $disharr[$dv['sid']][] = $dv;
                } else {
                    $disharr[$dv['sid']] = array();
                    $disharr[$dv['sid']][] = $dv;
                }
                if ($dv['ishot'] == 1) {
                    if (array_key_exists($dv['id'], $isHaveTj)) {
                        $dv['ornum'] = $isHaveTj[$dv['id']]['ornum'];
                    } else {
                        $dv['ornum'] = 0;
                    }
                    $dztjtmp[] = $dv;
                    $dztj = true;
                }
            }
        }
        $newtmpdisharr = array();
        foreach ($fenleiarr as $ssk => $ssv) {
            $newtmpdisharr[$ssk] = $disharr[$ssk];
        }
        $disharr = $newtmpdisharr;

        $disharr['dztj'] = !empty($dztjtmp) ? $dztjtmp : array();
        $this->assign('kconoff', $kconoff);
        $this->assign('dz_tj', $dztj);
        $this->assign('stype', $outset['stype']);
        $this->assign('pricing', $outset['pricing']);
        $this->assign('cid', $cid);
        $this->assign('fenleiarr', $fenleiarr);
        $this->assign('disharr', $disharr);
        $this->assign('company', $company);
        $this->assign('metaTitle', $company['name']);
        $this->display();
    }

    /**
     * 订单信息确认
     */
    public function sureOrder() {
        $dishtmp = $_POST['dish'];
        $dzdish = $_POST['dzdish'];
        $tmpcid = intval($_POST['mycid']);
        $disharr = array();
        $outset = $this->outManage($tmpcid);
        $tmpdisharr = $dzdisharr = array();
        if (($tmpcid > 0) && ($tmpcid == $this->_cid)) {
            foreach ($dishtmp as $kk => $vv) {
                $vv = $vv ? intval($vv) : 0;
                if ($vv > 0) {
                    $tmpdisharr[$kk] = array('id' => $kk, 'ornum' => $vv);
                }
                $dztjvv = isset($dzdish[$kk]) && !empty($dzdish[$kk]) ? intval($dzdish[$kk]) : 0;
                if ($dztjvv > 0) {
                    $dzdisharr[$kk] = array('id' => $kk, 'ornum' => $dztjvv);
                }
                $vv = $vv + $dztjvv;
                if ($vv > 0) {
                    $disharr[$kk] = array('id' => $kk, 'ornum' => $vv);
                }
            }
            if (empty($disharr)) {
                $this->exitdisplay('您尚未点菜！');
            }
            $DishC = $this->getDishCompany($tmpcid); /*             * 餐厅设置信息* */
            $kconoff = $DishC['kconoff'];
            $Mcompany = $this->getDishMainCompany(false);
            $dishofcid = $tmpcid;
            if (($Mcompany['cid'] != $tmpcid) && ($Mcompany['dishsame'] == 1)) {
                $dishofcid = $Mcompany['cid']; /*                 * *开启分店统一主店 菜品功能** */
                $kconoff = $Mcompany['kconoff'];
            }
            $_SESSION[$this->session_dish_info] = serialize(array('pt' => $tmpdisharr, 'tj' => $dzdisharr));
            unset($tmpdisharr, $dzdisharr);
            $idarr = array_keys($disharr);
            sort($idarr);
            $idstr = implode(',', $idarr);
            $db_dish = M('Dish');
            $dish = $db_dish->where('id in(' . $idstr . ') and cid="' . $dishofcid . '" and isopen="1" and istakeout="1"')->order("`sort` ASC")->select();
            $totl = $ornum = 0;
            foreach ($dish as $val) {
                $index = $val['id'];
                $disharr[$index] = array_merge($disharr[$index], $val);
                $totl+=$disharr[$index]['price'] * $disharr[$index]['ornum'];
                $ornum+=$disharr[$index]['ornum'];
            }

            $permin = $outset['permin'] > 0 ? $outset['permin'] : 15;
            $sendtime = $outset['sendtime'] > 0 ? $outset['sendtime'] : $permin;
            $starttime = $current = time();
            $tomorrowtime = $timearr = $time2arr = array();
            /* if (($outset['zc_sdate'] > 0) && ($current < $outset['zc_sdate'])) {
              $starttime = $outset['zc_sdate'];
              $starttime = date('Y-m-d') . ' ' . date('H:i:s', $outset['zc_sdate']);
              $starttime = strtotime($starttime);
              }
              $endtime = strtotime(date('Y-m-d ') . "23:59:59");
              if ($outset['zc_edate'] > 0) {
              $endtime = $outset['zc_edate'];
              $endtime = date('Y-m-d') . ' ' . date('H:i:s', $outset['zc_edate']);
              $endtime = strtotime($endtime);
              }
              $starttime = $starttime + ($sendtime * 60);
              $t1 = strtotime(date('Y-m-d H', $starttime) . ":00:00");
              $t2 = $starttime - $t1;
              $t3 = $permin * 60;
              $t4 = $sendtime * 60;
              if ($t2 < $t3) {
              $starttime = $t1 + $t3;
              } elseif ($t2 > $t4) {
              $starttime = $t1 + $t4 + $t3;
              } else {
              $starttime = $t1 + (2 * $t3);
              }
              $tmptime = $endtime - $starttime;
              if ($tmptime > 0) {
              $mins = floor($tmptime / 60);
              $timearr[] = date('H:i', $starttime);
              if ($mins > $permin) {
              for ($i = $permin; $i <= $mins; $i = $i + $permin) {
              $timearr[] = date('H:i', ($i * 60 + $starttime));
              }
              }
              } else {
              $timearr[] = date('H:i', $endtime);
              }
             */
            $starttime = date('Y-m-d') . ' ' . $outset['stimestr'];
            $starttime = strtotime($starttime);
            if ($current > $starttime && ($outset['stimestr'] != '00:00') && ($outset['etime2str'] != '23:59')) {
                $starttime = $current;
            }
            $endtime = date('Y-m-d') . ' ' . $outset['etimestr'];
            $endtime = strtotime($endtime) + 60;
            $starttime = $starttime + ($sendtime * 60);
            $t1 = strtotime(date('Y-m-d H', $starttime) . ":00:00");
            $t2 = $starttime - $t1;
            $t3 = $permin * 60;
            $t4 = $sendtime * 60;
            if ($t2 < $t3) {
                $starttime = $t1 + $t3;
            } elseif ($t2 > $t4) {
                $starttime = $t1 + $t4 + $t3;
            } else {
                $starttime = $t1 + (2 * $t3);
            }
            $tmptime = $endtime - $starttime;
            if ($tmptime > 0) {
                $mins = floor($tmptime / 60);
                $timearr[] = date('H:i', $starttime);
                if ($mins > $permin) {
                    for ($i = $permin; $i <= $mins; $i = $i + $permin) {
                        $timearr[] = date('H:i', ($i * 60 + $starttime));
                    }
                }
            } else {
                $timearr[] = date('H:i', $endtime);
            }

            if (!empty($outset['stime2str']) && !empty($outset['etime2str'])) {
                $start2time = date('Y-m-d') . ' ' . $outset['stime2str'];
                $start2time = strtotime($start2time);
                if ($current > $start2time) {
                    $start2time = $current;
                }
                $end2time = date('Y-m-d') . ' ' . $outset['etime2str'];
                $end2time = strtotime($end2time) + 60;
                $start2time = $start2time + ($sendtime * 60);
                $t1 = strtotime(date('Y-m-d H', $start2time) . ":00:00");
                $t2 = $start2time - $t1;
                $t3 = $permin * 60;
                $t4 = $sendtime * 60;
                if ($t2 < $t3) {
                    $start2time = $t1 + $t3;
                } elseif ($t2 > $t4) {
                    $start2time = $t1 + $t4 + $t3;
                } else {
                    $start2time = $t1 + (2 * $t3);
                }
                $tmptime = $end2time - $start2time;
                if ($tmptime > 0) {
                    $mins = floor($tmptime / 60);
                    $time2arr[] = date('H:i', $start2time);
                    if ($mins > $permin) {
                        for ($i = $permin; $i <= $mins; $i = $i + $permin) {
                            $time2arr[] = date('H:i', ($i * 60 + $start2time));
                        }
                    }
                } else {
                    $time2arr[] = date('H:i', $end2time);
                }
            }
            $tomorrowday = date('Y-m-d', strtotime('+1 day'));
            if (($outset['stimestr'] == '00:00') && ($outset['etime2str'] == '23:59')) {
                $tomorrowtime = $timearr;
            }
            if ($current < $endtime) {
                $timearr = array_merge($timearr, $time2arr);
            } else {
                $timearr = $time2arr;
            }
            $lastkey = count($timearr) - 1;
            if (($lastkey > 0) && ($timearr[$lastkey] == '00:00')) {
                $timearr[$lastkey] = $tomorrowday . ' 00:00';
            }
            $Dish_order = M('Dish_order');
            $contact = false;
            if ($this->wecha_id) {
                $orderinfo = $Dish_order->where(array('token' => $this->token, 'cid' => $tmpcid, 'wecha_id' => $this->wecha_id))->order("paid DESC,id DESC ")->find();
                if (!empty($orderinfo)) {
                    $contact['youname'] = $orderinfo['name'];
                    $contact['yousex'] = $orderinfo['sex'];
                    $contact['youtel'] = $orderinfo['tel'];
                    $contact['youaddress'] = $orderinfo['address'];
                }
            }

            $this->assign('kconoff', $kconoff);
            $this->assign('tomorrowday', $tomorrowday);
            $this->assign('tomorrowtime', $tomorrowtime);
            $this->assign('timearr', $timearr);
            $this->assign('contact', $contact);
            $this->assign('stype', $outset['stype']);
            $this->assign('pricing', $outset['pricing']);
            $this->assign('ortotl', $totl);
            $this->assign('ornum', $ornum);
            $this->assign('cid', $tmpcid);
            $this->assign('ordish', $disharr);
            $this->assign('company', $this->company);
            $this->assign('metaTitle', $this->company['name']);
            $this->display();
        } else {
            $this->exitdisplay('提交信息出错');
        }
    }

    /** 错误提醒页面**
     * *$returnurl true是将返回上一url
     * *也可制定一个跳转url
     * */
    private function exitdisplay($tips = "", $returnurl = false) {
        /* //保证输出不受静态缓存影响
          C('HTML_CACHE_ON', false);
          $this->assign('tips', $tips);
          $this->assign('returnurl', $returnurl);
          $this->display('exitdisplay'); */
        $this->error($tips, $returnurl);
        exit;
    }

    /** 获取餐厅配置信息* */
    private function getDishCompany($cid, $cache = true) {
        $DishC = $_SESSION["session_dish{$cid}_{$this->token}"];
        $DishC = !empty($DishC) ? unserialize($DishC) : false;
        if ($cache && !empty($DishC)) {
            return $DishC;
        } else {
            $DishC = M('Dish_company')->where(array('cid' => $cid))->find();
            if ($cache) {
                $_SESSION["session_dish{$cid}_{$this->token}"] = !empty($DishC) ? serialize($DishC) : '';
            }
            return $DishC;
        }
    }

    /** 获取公司信息* */
    private function getCompany($cid, $cache = true) {
        $this->company = $_SESSION["session_shop{$cid}_{$this->token}"];
        $this->company = !empty($this->company) ? unserialize($this->company) : false;
        if ($cache && !empty($this->company)) {
            return $this->company;
        } else {
            $company = M('Company')->where(array('token' => $this->token, 'id' => $cid))->find();
            if (empty($company) || !is_array($company)) {
                $this->redirect(U('DishOut/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
            }
            if ($cache) {
                $this->company = $company;
                $_SESSION["session_shop{$cid}_{$this->token}"] = !empty($company) ? serialize($company) : '';
            }
            return $company;
        }
    }

    /** 获取外卖设置信息* */
    private function outManage($cid) {
        if (!empty($this->shopmanage)) {
            return $this->shopmanage;
        } else {
            $outset = M('Dishout_manage')->where(array('token' => $this->token, 'cid' => $cid))->find();
            $tmp = M('Dish_company')->where(array('cid' => $cid))->find();
            if (is_array($tmp) && ($tmp['istakeaway'] == 1)) { /*             * 支持老用户不设置也能打开* */
                $no_outset = array('token' => $this->token, 'cid' => $cid, 'stype' => 1, 'pricing' => 0, 'sendtime' => 30, 'removing' => 5, 'permin' => 15, 'zc_sdate' => 0, 'zc_edate' => 0, 'wc_sdate' => 0, 'wc_edate' => 0, 'shopimg' => '', 'area' => '');
                $outset = empty($outset) ? $no_outset : $outset;
            }
            if (empty($outset) || !is_array($outset)) {
                $this->exitdisplay('抱歉！此商家还没有设置外卖管理相关信息');
            }
            $stimestr = $outset['zc_sdate'] > 0 ? date('H:i', $outset['zc_sdate']) : '00:00';
            $etimestr = '23:59';
            $stime2str = $etime2str = '';
            if (($outset['wc_sdate'] > 0) && ($outset['zc_edate'] > 0)) {
                $etimestr = date('H:i', $outset['zc_edate']);
                $stime2str = date('H:i', $outset['wc_sdate']);
                $etime2str = $outset['wc_edate'] > 0 ? date('H:i', $outset['wc_edate']) : '23:59';
            }
            if (($outset['wc_sdate'] > 0) && !($outset['zc_edate'] > 0)) {
                if (!($outset['zc_sdate'] > 0)) {
                    $stimestr = date('H:i', $outset['wc_sdate']);
                    $etimestr = $outset['wc_edate'] > 0 ? date('H:i', $outset['wc_edate']) : '23:59';
                } else {
                    $etimestr = date('H:i', $outset['wc_sdate']);
                    $stime2str = date('H:i', $outset['wc_sdate']);
                    $etime2str = $outset['wc_edate'] > 0 ? date('H:i', $outset['wc_edate']) : '23:59';
                }
            }

            if (!($outset['wc_sdate'] > 0)) {
                if ($outset['zc_edate'] > 0) {
                    $etimestr = date('H:i', $outset['zc_edate']);
                } else {
                    $etimestr = $outset['wc_edate'] > 0 ? date('H:i', $outset['wc_edate']) : '23:59';
                }
            }
            $outset['stimestr'] = $stimestr;
            $outset['etimestr'] = $etimestr;
            $outset['stime2str'] = $stime2str;
            $outset['etime2str'] = $etime2str;
            unset($stimestr, $etimestr, $stime2str, $etime2str);
            $this->shopmanage = $outset;
            $_SESSION["manage_shop{$cid}_{$this->token}"] = !empty($outset) ? serialize($outset) : '';
            return $outset;
        }
    }

    /** 地图* */
    public function companyMap() {
        if (C('baidu_map')) {
            $isamap = 0;
        } else {
            $isamap = 1;
        }
        $this->apikey = C('baidu_map_api');
        $this->assign('apikey', $this->apikey);
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        $company = $this->getCompany($cid, false);
        $this->assign('thisCompany', $company);
        if (!$isamap) {
            $this->display();
        } else {
            $this->amap = new amap();
            $link = $this->amap->getPointMapLink($company['longitude'], $company['latitude'], $company['name']);
            header('Location:' . $link);
        }
    }

    /**
     * 保存订餐人的信息到session
     */
    public function OrderPay() {
        $disharr = $_POST['dish']; /*         * 菜品id 数组*id 份数 */
        $shopid = intval($_POST['mycid']);
        $totalmoney = floatval(trim($_POST['totalmoney']));
        $totalnum = intval(trim($_POST['totalnum']));
        $ouserName = htmlspecialchars(trim($_POST['ouserName']), ENT_QUOTES);
        $ouserSex = intval(trim($_POST['ouserSex']));
        $ouserTel = htmlspecialchars(trim($_POST['ouserTel']), ENT_QUOTES);
        $ouserAddres = htmlspecialchars(trim($_POST['ouserAddres']), ENT_QUOTES);
        $oarrivalTime = htmlspecialchars(trim($_POST['oarrivalTime']), ENT_QUOTES);
        $omark = htmlspecialchars(trim($_POST['omark']), ENT_QUOTES);

        if ($shopid > 0) {
            $jumpurl = U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $shopid, 'wecha_id' => $this->wecha_id));
            if (empty($disharr) || !($totalmoney > 0) || !($totalnum > 0)) {
                $this->exitdisplay('订单信息出错！', $jumpurl);
            }
            if (empty($ouserName) || empty($ouserTel) || empty($ouserAddres)) {
                $this->exitdisplay('订单中相关联系地址：姓名或联系电话或送货地址有没写的', $jumpurl);
            }
            if (preg_match('/\-\d{2}\s\d{2}\:/', $oarrivalTime)) {   /** **如果选择了明日的时间 是带上日期的** */
                $oarrivalTime = strtotime($oarrivalTime);
            } else {
                $oarrivalTime = $oarrivalTime ? strtotime(date('Y-m-d ') . $oarrivalTime) : 0;
            }
            $tmparr = array();
            $tmpsubnum = 0;
            $tmpsubmoney = 0;
            
			/**************************************************/
            $disharr && $idarr = array_keys($disharr);
            if ($idarr) {
            	$dishs = M('Dish')->where(array('id' => array('in', $idarr), 'cid' => $shopid, 'isopen' => 1))->select();
            	foreach ($dishs as $dh) {
            		if (isset($disharr[$dh['id']]['num']) && $disharr[$dh['id']]['num']) {
            			$tmpnum = $disharr[$dh['id']]['num'];
            			$discount = trim($disharr[$dh['id']]['discount']);
            			if ($discount > 0) {
            				$tmpprice = ($discount * $dh['price']) / 10;
            			} else {
            				$tmpprice = $dh['price'];
            			}
            			//$tmpprice = $discount > 0 ? $discount * floatval($dv['price']) / 10 : floatval($dv['price']);
            			$tmparr[$dh['id']] = array();
            			$tmparr[$dh['id']]['did'] = $dh['id'];
            			$tmparr[$dh['id']]['num'] = $tmpnum;
            			$tmparr[$dh['id']]['discount'] = $discount;
            			$tmparr[$dh['id']]['price'] = $tmpprice;
            			$tmparr[$dh['id']]['name'] = $dh['name'];
            			$tmparr[$dh['id']]['kitchen_id'] = $dh['kitchen_id'];
            			$tmparr[$dh['id']]['omark'] = htmlspecialchars(trim($disharr[$dh['id']]['omark']), ENT_QUOTES);
            			$tmpsubnum += $tmpnum;
            			$tmpsubmoney += ($tmpprice * $tmpnum);
            		}
            	}
            }
            /**************************************************/
            /*foreach ($disharr as $dk => $dv) {
                if (!empty($dv)) {
                    $tmpnum = intval($dv['num']);
                    if ($tmpnum > 0) {
                        $tmpprice = floatval($dv['price']);
                        $tmparr[$dk] = array();
                        $tmparr[$dk]['did'] = $dk;
                        $tmparr[$dk]['num'] = $tmpnum;
                        $tmparr[$dk]['price'] = $tmpprice;
                        $tmparr[$dk]['name'] = $dv['name'];
                        $tmpsubnum+=$tmpnum;
                        $tmpsubmoney+=($tmpprice * $tmpnum);
                    }
                }
            }*/
            
            if (empty($tmparr)) {
                $this->exitdisplay('没有订单信息', $jumpurl);
            }
			$tmpsubmoney=number_format($tmpsubmoney,2,'.','');/***去掉千位分隔符***/
            $t_tmpsubmoney = (int) ($tmpsubmoney * 1000);

            $t_totalmoney = (int) ($totalmoney * 1000);

            if (($tmpsubnum != $totalnum) || ($t_tmpsubmoney != $t_totalmoney)) {
                $this->error('订单的金额或点的菜的份数不对', $jumpurl);
            }

            $outset = $this->outManage($shopid);
            if (($outset['stype'] == 2) && ($tmpsubmoney < $outset['pricing'])) {
                $tmpsubmoney = $outset['pricing']; /*                 * 处理起步价方式订单金额不足起步价按起步价收取* */
            }
            $wecha_id = $this->wecha_id ? $this->wecha_id : 'DishOutm_' . $ouserTel;
            $orderid = substr($wecha_id, -5) . date("YmdHis");
            $Orderarr = array('cid' => $shopid, 'wecha_id' => $wecha_id, 'token' => $this->token, 'total' => $tmpsubnum,
                'price' => $tmpsubmoney, 'nums' => 1,
                'info' => serialize($tmparr), 'name' => $ouserName,
                'sex' => $ouserSex, 'tel' => $ouserTel,
                'address' => $ouserAddres, 'tableid' => 0,
                'time' => time(), 'reservetime' => $oarrivalTime,
                'stype' => $outset['stype'], 'paid' => 0, 'isuse' => 0,
                'orderid' => $orderid, 'printed' => 0,
                'des' => $omark, 'takeaway' => 1,
                'comefrom' => 'dishout'
            );
            $orid = D('Dish_order')->add($Orderarr);
            if ($orid) {
                $_SESSION[$this->session_dish_info] = '';
                //TODO 短信提示
                $company = $this->getCompany($shopid);
                Sms::sendSms($this->token, "顾客{$ouserName}刚刚叫了一份外卖，订单号：{$orderid}，请您注意查看并处理", $company['mp']);
				//给商家发站内信
				$params = array();
				$params['site'] = array('token'=>$this->token, 'from'=>'微外卖消息','content'=>"顾客{$ouserName}刚刚叫了一份外卖，订单号：{$orderid}，请您注意查看并处理");
				MessageFactory::method($params, 'SiteMessage');
//                 $printer_set = $this->getPrinter_set($shopid);
//                 if (!empty($printer_set) && ($printer_set['paid'] == 0)) {
                    $op = new orderPrint();
                    $msg = array('companyname' => $company['name'], 'des' => trim($_POST['omark']),
                        'companytel' => $company['tel'], 'truename' => trim($_POST['ouserName']),
                        'tel' => trim($_POST['ouserTel']), 'address' => trim($_POST['ouserAddres']),
                        'buytime' => $Orderarr['time'], 'orderid' => $Orderarr['orderid'],
                        'sendtime' => $oarrivalTime > 0 ? $oarrivalTime : '尽快送达', 'price' => $Orderarr['price'],
                        'total' => $Orderarr['total'], 'typename' => '外卖',
                        'list' => $tmparr);
                    $msg = ArrayToStr::array_to_str($msg, 0);
                    $op->printit($this->token, $shopid, 'DishOut', $msg, 0);
                    
                    /*厨房打印菜单*/
                    if ($kitchens_list = D('Dish_kitchen')->where(array('cid' => $this->_cid))->select()) {
	                    $t_list = array();
	                    foreach ($tmparr as $dish) $t_list[$dish['kitchen_id']][] = $dish;

	                    $kitchens = array();
	                    foreach ($kitchens_list as $kit_row) $kitchens[$kit_row['id']] = $kit_row;
	                    
	                    $print_msg = array('des' => trim($_POST['omark']), 'truename' => trim($_POST['ouserName']), 'tel' => trim($_POST['ouserTel']), 'address' => trim($_POST['ouserAddres']), 'buytime' => $Orderarr['time'], 'orderid' => $Orderarr['orderid'], 'sendtime' => $oarrivalTime > 0 ? $oarrivalTime : '尽快送达');
	                    foreach ($t_list as $k => $rowset) {
	                    	if ($k) {
	                    		if (isset($kitchens[$k]) && $kitchens[$k]['status']) {
	                    			for ($i = 0; $i < count($rowset); $i++) {
			                    		$msg = $print_msg;
			                    		$msg['list'][] = $rowset[$i];
					                    $msg = ArrayToStr::array_to_str($msg, 0);
					                    $op->printit($this->token, $this->_cid, 'DishOut', $msg, 0, '', $k);
	                    			}
	                    		} else {
		                    		$msg = $print_msg;
		                    		$msg['list'] = $rowset;
				                    $msg = ArrayToStr::array_to_str($msg, 0);
				                    $op->printit($this->token, $this->_cid, 'DishOut', $msg, 0, '', $k);
	                    		}
	                    	}
	                    }
                    }
                    /*厨房打印菜单*/
                    
                    
//                 }
                $alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();

                if ($alipayConfig['open']) {
                    $this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $wecha_id, 'success' => 1, 'from' => 'DishOut', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $tmpsubmoney)));
                } /* elseif ($this->fans['balance']) {
                  $this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $wecha_id, 'success' => 1, 'from'=> 'DishOut', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $tmpsubmoney)));
                  } */ else {
                    $this->exitdisplay('商家尚未开启支付功能', $jumpurl);
                }
            } else {
                $this->exitdisplay('订单录入系统出错，抱歉给您的带来了不便。请重新下单吧', $jumpurl);
            }
            if (!empty($this->wecha_id)) {
                /* 保存个人信息 */
                $userinfo_model = M('Userinfo');
                $thisUser = $userinfo_model->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
                if (empty($thisUser)) {
                    $userRow = array('tel' => $ouserTel, 'truename' => $ouserName, 'address' => $ouserAddres);
                    $userRow['token'] = $this->token;
                    $userRow['wecha_id'] = $this->wecha_id;
                    $userRow['wechaname'] = '';
                    $userRow['qq'] = 0;
                    $userRow['sex'] = $ouserSex;
                    $userRow['age'] = 0;
                    $userRow['birthday'] = '';
                    $userRow['info'] = '';

                    $userRow['total_score'] = 0;
                    $userRow['sign_score'] = 0;
                    $userRow['expend_score'] = 0;
                    $userRow['continuous'] = 0;
                    $userRow['add_expend'] = 0;
                    $userRow['add_expend_time'] = 0;
                    $userRow['live_time'] = 0;
                    $userinfo_model->add($userRow);
                }
            }
        } else {
            $jumpurl = U('DishOut/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id));
            $this->exitdisplay('订单信息中店面信息出错', $jumpurl);
        }
    }

    /**
     * 订单支付
     */
    public function OrderPayAgain() {
        $orid = $this->_get('orid') ? intval($this->_get('orid', "trim")) : 0;
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        if ($orid > 0 && ($cid > 0)) {
            $Dish_order = M('Dish_order');
            $myorder = $Dish_order->where(array('id' => $orid, 'token' => $this->token, 'cid' => $cid))->find();
            if ($myorder) {
                $price = $myorder['price'] - $myorder['havepaid'];
                $alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
                if ($alipayConfig['open']) {
                    $this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $myorder['wecha_id'], 'success' => 1, 'from' => 'DishOut', 'orderName' => $myorder['orderid'], 'single_orderid' => $myorder['orderid'], 'price' => $price)));
                    exit();
                } else {
                    $this->error('商家尚未开启支付功能');
                }
            }
        }
        $this->error('订单信息出错！');
    }
    /**
     * 我的订单记录
     */
    public function myOrder() {
        $this->_cid = $this->_cid > 0 ? $this->_cid : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
        $_SESSION["session_shop_{$this->token}"] = $this->_cid;
        $where = array('wecha_id' => $this->wecha_id, 'token' => $this->token, 'cid' => $this->_cid, 'isdel' => "0", 'comefrom' => 'dishout');
        $dish_order = M('Dish_order')->where($where)->order('id DESC')->limit(7)->select(); //只查询最近7条记录
        $list = array();
        $payarr = array('alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpaycomputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线');
        foreach ($dish_order as $row) {
            $row['info'] = unserialize($row['info']);
            $paystr = strtolower($row['paytype']);
            $row['paytypestr'] = !empty($paystr) && array_key_exists($paystr, $payarr) ? $payarr[$paystr] : '其他';
            if (!$row['paid'] && ($row['paytype'] != 'daofu') && ($row['paytype'] != 'dianfu')) {
                $row['paystatus'] = '未付款';
            } else {
                $row['paystatus'] = '';
            }
            $list[] = $row;
        }

        $company = $this->getCompany($this->_cid);
        $this->assign('company', $company);
        $this->assign('cid', $this->_cid);
        $this->assign('orderList', $list);
		$this->assign('today', strtotime(date('Y-m-d') . ' 00:00:00'));
        $this->assign('metaTitle', '我的订单');
        $this->display();
    }

    /**
     * 支付成功后的回调函数
     */
    public function payReturn() {
        $orderid = trim($_GET['orderid']);
        if (isset($_GET['nohandle'])) {
            $order = M('dish_order')->where(array('orderid' => $orderid, 'token' => $this->token))->find();
            /*
              $model = new templateNews();
              $siteurl=$_SERVER['HTTP_HOST'];
              $siteurl=strtolower($siteurl);
              if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
              $siteurl=rtrim($siteurl,'/');
              $model->sendTempMsg('OPENTM202521011', array('href' => $siteurl.U('DishOut/myOrder', array('token' => $order['token'], 'wecha_id' => $order['wecha_id'], 'cid' => $order['cid'])), 'wecha_id' => $order['wecha_id'], 'first' => '外卖订餐交易提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '支付成功，感谢您的光临，欢迎下次再次光临！'));
             */
            $this->redirect(U('DishOut/myOrder', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $order['cid'])));
            //已经异步处理过了
        } else {
            ThirdPayDishOut::index($orderid);
        }
        /*         * 原来的代码 先注释 防止有问题 可以随时改回来
          if ($order = M('dish_order')->where(array('orderid' => $orderid, 'token' => $this->token))->find()) {
          //TODO 发货的短信提醒
          if ($order['paid'] || $order['paytype'] == 'daofu' || $order['paytype'] == 'dianfu') {
          $temp = unserialize($order['info']);
          $tmparr = array('token' => $this->token, 'cid' => $order['cid'], 'order_id' => $order['id'], 'paytype' => $order['paytype']);
          $log_db = M('Dishout_salelog');
          if (!empty($temp) && is_array($temp)) {
          foreach ($temp as $kk => $vv) {
          $logarr = array(
          'did' => isset($vv['did']) ? $vv['did'] : $kk, 'nums' => $vv['num'],
          'unitprice' => $vv['price'], 'money' => $vv['num'] * $vv['price'], 'dname' => $vv['name'],
          'addtime' => $order['time'], 'addtimestr' => date('Y-m-d H:i:s', $order['time']),'comefrom'=>0
          );
          $savelogarr = array_merge($tmparr, $logarr);
          $log_db->add($savelogarr);
          }
          }
          $company = $this->getCompany($order['cid']);
          Sms::sendSms($this->token, "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理",$company['mp']);
          $model = new templateNews();
          $model->sendTempMsg('TM00820', array('href' => U('DishOut/myOrder',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $order['cid'])), 'wecha_id' => $this->wecha_id, 'first' => '订餐交易提醒', 'keynote1' => '订单已支付', 'keynote2' => date("Y年m月d日H时i分s秒"), 'remark' => '下单成功，感谢您的光临，欢迎下次再次光临！'));

          $op = new orderPrint();
          $msg = array('companyname' => $company['name'], 'des' => htmlspecialchars_decode($order['des'], ENT_QUOTES),
          'companytel' => $company['tel'], 'truename' => htmlspecialchars_decode($order['name'], ENT_QUOTES),
          'tel' => $order['tel'], 'address' => htmlspecialchars_decode($order['address'], ENT_QUOTES),
          'buytime' => $order['time'], 'orderid' => $order['orderid'],
          'sendtime' => $order['reservetime'], 'price' => $order['price'],
          'total' => $order['total'], 'typename' => '外卖',
          'ptype'=>$thisOrder['paytype'],'list' => $temp);
          $msg = ArrayToStr::array_to_str($msg, $order['paid']);
          $op->printit($this->token, $order['cid'], 'DishOut', $msg, $order['paid']);

          }
          $this->redirect(U('DishOut/myOrder', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $order['cid'])));
          } else {
          $jumpurl = U('DishOut/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id));
          $this->exitdisplay('订单信息中店面信息出错', $jumpurl);
          } */
    }

}

?>