<?php

class MicroRepastReply {

    public $item;
    public $wechat_id;
    public $siteUrl;
    public $token;

    public function __construct($token, $wechat_id, $data, $siteUrl) {
        $this->item = M('Dish_reply')->where(array('id' => $data['pid']))->find();
        $this->wechat_id = $wechat_id;
        $this->siteUrl = $siteUrl;
        $this->token = $token;
    }

    public function index() {
        $thisItem = $this->item;
        $tmpdata = array();
        if ($thisItem) {
            $this->item = M('Dish_reply')->where(array('id' => $data['pid']))->find();
            if ($thisItem['type'] == 1) {
                $tmpdata = array(
                    array(
                        array(
                            '欢迎您光顾本餐厅！',
                            '欢迎您光顾本餐厅！',
                            $this->siteUrl . '/tpl/static/repastnew/image/canting.jpg',
                            $this->siteUrl . U('Wap/Repast/ShopPage', array('token' => $this->token, 'cid' => $thisItem['cid'], 'wecha_id' => $this->wechat_id))
                        )
                    ), 'news');
            } elseif ($thisItem['type'] == 2) {
                $tmpdata = array(
                    array(
                        array(
                            '后台餐桌使用状况管理',
                            '后台餐桌使用状况管理',
                            $this->siteUrl . '/tpl/static/repastnew/image/canting.jpg',
                            $this->siteUrl . U('User/RepastStaff/mtlogin', array('token' => $this->token, 'cid' => $thisItem['cid'], 'wecha_id' => $this->wechat_id))
                        )
                    ), 'news');
            } else {
                $tmpdata = array(
                    array(
                        array(
                            '欢迎您光顾本餐厅！',
                            '欢迎您光顾本餐厅！',
                            $this->siteUrl . '/tpl/static/repastnew/image/canting.jpg',
                            $this->siteUrl . U('Wap/Repast/dishMenu', array('token' => $this->token, 'cid' => $thisItem['cid'], 'tid' => $thisItem['tableid'], 'wecha_id' => $this->wechat_id))
                        )
                    ), 'news');
            }
        }
        return $tmpdata;
    }

}

?>