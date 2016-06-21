<?php
class UseAction extends BackAction{

    public static function getParams($array = array()) {
        $arr = $_GET;
        foreach ($array as $key => $value) {
            $arr[$key] = $value;
        }
        return $arr;
    }

    public function index(){
        if ($this->_get('token') == 'alltoken') {
            $this->assign('pc','1');
        }
        if ($this->_get('token')) {
            if ($this->_get('is_what') == 'public_hao') {
                $group = 'token';
                $where['token'] = array('neq','alltoken');
            }else{
                $where['token'] = $this->_get('token');
                $group = 'controller';
            }
        }else{
            if ($this->_get('is_what') == 'public_hao') {
                $group = 'token';
                $where['token'] = array('neq','alltoken');
            }else{
                $group = 'controller';
                $where['token'] = 'alltoken';
            }
        }
        if ($this->_get('module')) {
            $where['module'] = $this->_get('module');
        }else{
            $where['module'] = 'wap';
        }
        if (strtotime('last Monday') == strtotime("last Sunday +1 day")) {
            $ben_week = strtotime('last Monday');
        }else{
            if (strtotime('last Monday') == strtotime("last Saturday +2 day")) {
                $ben_week = strtotime('last Monday');
            }else{
                $ben_week = strtotime("last Sunday +1 day");
            }
        }
        if ($this->_get('lasttime') == 'last') {
            if ($this->_get('time') == '') {
                $where['create_time'] = array(array('gt',strtotime(date('Y-m-d',strtotime('-1 day')))),array('lt',strtotime(date('Y-m-d')))) ;
            }else{
                if ($this->_get('time') == 'day') {
                    $where['create_time'] = array(array('gt',strtotime(date('Y-m-d',strtotime('-1 day')))),array('lt',strtotime(date('Y-m-d')))) ;
                }
                if ($this->_get('time') == 'week') {
                    $where['create_time'] = array(array('gt',$ben_week-3600*7*24),array('lt',$ben_week)) ;
                }
                if ($this->_get('time') == 'month') {
                    $where['create_time'] = array(array('gt',strtotime(date('Y-m-1',strtotime('-1 month')))),array('lt',strtotime(date('Y-m-1')))) ;
                }
            }
        }else{
            if ($this->_get('time') == '') {
                $where['create_time'] = array(array('gt',strtotime(date('Y-m-d'))),array('lt',strtotime(date('Y-m-d'))+86399)) ;
            }else{
                if ($this->_get('time') == 'day') {
                    $where['create_time'] = array(array('gt',strtotime(date('Y-m-d'))),array('lt',strtotime(date('Y-m-d'))+86399)) ;
                }
                if ($this->_get('time') == 'week') {
                    $where['create_time'] = array(array('gt',$ben_week),array('lt',$ben_week+86400*7)) ;
                }
                if ($this->_get('time') == 'month') {
                    $where['create_time'] = array(array('gt',strtotime(date('Y-m-1'))),array('lt',strtotime(date('Y-m-1',strtotime('+1 month'))))) ;
                }
            }
        }
        $this->assign('wxuser',$this->_get('wxname'));
        $this->assign('get',$_GET);
        $index = D('AccessCount');
        $data = $index->getData(array(
         'where' => $where,
         'group' => $group,
         'limit' => 60,
        ));
        $bing = array_slice($data,0,10);
        if ($this->_get('is_what') !== 'public_hao') {
            foreach ($data as $key => $value) {
                $value['controller'] = $this->strToChinese($value['controller']);
                $data[$key] = $value;      
            }
            $this->assign('pc','1');
        }
        if ($group == 'token') {
            if ($bing) {
                foreach ($bing as $key => $value) {
                    $wapJson .= "{value:".$value['count'].", name:'".$value['wxname']."'},";
                }
                $wapJson = rtrim($wapJson,',');
                $pie = array('series' => "$wapJson");
            }else{
                $pie = array(
                        'ifnull' => 1,
                        'series' => "{value:".rand(1,100).", name:'公众1号'},{value:".rand(1,100).", name:'公众2号'},{value:".rand(1,100).", name:'公众3号'},{value:".rand(1,100).", name:'公众4号'},{value:".rand(1,100).", name:'公众5号'},{value:".rand(1,100).", name:'公众6号'},{value:".rand(1,100).", name:'公众7号'},{value:".rand(1,100).", name:'公众8号'},{value:".rand(1,100).", name:'公众9号'},{value:".rand(1,100).", name:'公众10号'}");
            }
        }else{
            foreach ($data as $key => $value) {
                if(eregi("[^\x80-\xff]",$this->strToChinese($value['controller']))){
                    unset($data[$key]);
                }
            }
            $bing = array_slice($data,0,10);
            if ($bing) {
                foreach ($bing as $key => $value) {
                    $wapJson .= "{value:".$value['count'].", name:'".$this->strToChinese($value['controller'])."'},";
                }
                $wapJson = rtrim($wapJson,',');
                $pie = array('series' => "$wapJson");
            }else{
                $pie = array(
                        'ifnull' => 1,
                        'series' => "{value:".rand(1,100).", name:'万能表单'},{value:".rand(1,100).", name:'商城'},{value:".rand(1,100).", name:'全景'},{value:".rand(1,100).", name:'关注'},{value:".rand(1,100).", name:'文本请求'},{value:".rand(1,100).", name:'图文消息'},{value:".rand(1,100).", name:'通用订单'},{value:".rand(1,100).", name:'投票'},{value:".rand(1,100).", name:'婚庆喜帖'},{value:".rand(1,100).", name:'会员卡'},{value:".rand(1,100).", name:'推广活动'}");
            }
        }
        $this->assign('data',$data);
        $this->assign('pie',$pie);
        $this->display();
    }

    public function strToChinese($str){
        $array = array(
      
            'collectword'=>'集字游戏',
            'frontpage'=>'我要上头条',
            'shakelottery'=>'摇一摇抽奖',
            'cointree'=>'摇钱树',
            'sentiment'=>'谁是情圣',
            'liaotian'=>'聊天',
            'test'=>'趣味测试',
            'cardpay'=>'会员卡支付',
            'scene_member'=>'微现场个人信息',
            'scene_vote'=>'微现场投票',
            'userinfo'=>'用户信息',
            'catemenu'=>'底部导航',
            'classify'=>'分类管理',
            'live'=>'微场景',
            'reservation'=>'楼盘预约',
            'link'=>'功能库',
            'drpstore'=>'微店店铺配置',
            'flash'=>'幻灯片背景图',
            'tmpls'=>'静态模板管理',
            'text'=>'文本回复',
            'voiceresponse'=>'语音回复',
            'other'=>'回答不上来设置',
            'web'=>'电脑网站',
            'business'=>'行业应用',
            'fujin'=>'附近周边信息查询',
            'lottery'=>'抽奖',
            'sms'=>'短信接口',
            'api'=>'第三方接口',
            'choujiang'=>'幸运大转盘',
            'index'=>'微网站',
            'phone'=>'手机站',
            'attachment'=>'素材',
            'serviceuser'=>'客服',
            'function'=>'功能管理',
            'home'=>'首页',
            'member_card'=>'会员卡',
            'host_kev'=>'通用预订系统',
            'diymen'=>'自定义表单',
            'repast'=>'微订餐',
            'dx'=>'无线网络订餐',
            'store'=>'在线商城',
            'groupon'=>'在线团购系统',
            'diymen_set'=>'自定义菜单',
            'guajiang'=>'刮刮卡',
            'panorama'=>'全景',
            'wedding'=>'婚庆喜帖',
            'vote'=>'微投票',
            'estate'=>'房产',
            'alipay_config'=>'在线支付设置',
            'photo'=>'相册',
            'micrstore'=>'微店',
            'coupon'=>'优惠券',
            'upyun'=>'上传文件',
            'yundabao'=>'云打包',
            'wechat_behavior'=>'粉丝行为分析',
            'goldenegg'=>'砸金蛋',
            'luckyfruit'=>'水果机',
            'reply'=>'留言板',
            'company'=>'商家连锁',
            'car'=>'微汽车',
            'wall'=>'微信墙',
            'shake'=>'摇一摇',
            'forum'=>'微论坛',
            'medical'=>'微医疗',
            'message'=>'群发消息',
            'share'=>'分享统计',
            'hotels'=>'酒店宾馆',
            'host'=>'微信酒店宾馆',
            'tenpay'=>'财付通',
            'school'=>'微教育',
            'autumn'=>'中秋吃月饼',
            'lovers'=>'摁死小情侣游戏',
            'applegame'=>'七夕走鹊桥',
            'live'=>'微场景',
            'research'=>'微调研',
            'problem'=>'一战到底',
            'signin'=>'微信签到',
            'fanssign'=>'微信签到',
            'scene'=>'现场活动',
            'market'=>'微商圈',
            'custom'=>'微预约',
            'greeting_card'=>'祝福贺卡',
            'beauty'=>'微美容',
            'fitness'=>'微健身',
            'gover'=>'微政务',
            'food'=>'微食品',
            'travel'=>'微旅游',
            'flower'=>'微花店',
            'property'=>'微物业',
            'ktv'=>'微KTV',
            'bar'=>'微酒吧',
            'fitment'=>'微装修',
            'buswedd'=>'微婚庆',
            'affections'=>'微宠物',
            'housekeeper'=>'微家政',
            'lease'=>'微租赁',
            'game'=>'微游戏',
            'zhida'=>'百度直达号',
            'red_packet'=>'微信红包',
            'punish'=>'惩罚台',
            'invite'=>'邀请函',
            'autumns'=>'拆礼盒',
            'auth'=>'网页授权',
            'areply'=>'关注时回复与帮助',
            'helping'=>'分享助力',
            'popularity'=>'人气冲榜',
            'jiugong'=>'幸运九宫格',
            'microbroker'=>'全民经纪人',
            'unitary'=>'一元购',
            'crowdfunding'=>'微众筹',
            'bargain'=>'微砍价',
            'hongbao'=>'合体红包',
            'service'=>'聊天交友',
            'seckill'=>'秒杀',
            'sicrstore'=>'微店',
            'alipay'=>'微信支付',
            'card'=>'微贺卡',
            'voteimg'=>'图文投票',
            'weixin'=>'微信',
            'fuwu'=>'服务窗',
            'img'=>'图文回复',
            'seniorscene'=>'高级场景',
            'dishout'=>'微外卖',
            'shakearound'=>'摇一摇，周边',
            'serviceUser'=>'人工客服',
            'cutprice'=>'降价拍',
            'person_card'=>'微名片',
            'numqueue'=>'微排号',
            'templatemsg'=>'模板消息',
            'map'=>'地图',
            'wechat_group'=>'粉丝管理',
            'customtmpls'=>'动态自定义模板',
        );
        if(isset($array[$str])){
            return $array[$str];
        }else{
            return $str;
        }   
    }
}







