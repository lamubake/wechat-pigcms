<?php
class VoteAction extends WapAction{

    public function __construct(){
        parent::_initialize();
    }

	public function index(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$token		= $this->_get('token');
		$wecha_id	= $this->wecha_id;
        $id         = $this->_get('id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $this->assign('id',$id);

		$t_vote	    = M('Vote');
        $t_record   = M('Vote_record');
		$where 		= array('token'=>$token,'id'=>$id,'status'=>'1');
		$vote 	    = $t_vote->where($where)->find();

        if(empty($vote)){
            exit('活动还没开启');
        }

        $success = array();
        $vote_item  = M('Vote_item')->where(array('vid'=>$vote['id']))->order('rank DESC')->select();   
        
  
        //检查是否投票过
        $t_item = M('Vote_item');
        $where = array('wecha_id'=>$wecha_id,'vid'=>$id);
        $vote_record  = $t_record->where($where)->find();
        if($vote_record && $vote_record != NULL){
            $arritem = trim($vote_record['item_id'],',');
            $map['id'] = array('in',$arritem);
            $hasitems = $t_item->where($map)->field('item')->getField('item',true);               
            $success = array('err'=>3,'info'=>'您已经投过票了','hasitems'=>join(',',$hasitems));
        }
      
        $item_count = M('Vote_item')->where('vid='.$id)->order('rank DESC')->select();
        
        $vcount     = M('Vote_item')->where(array('vid'=>$vote['id']))->sum("vcount");
       
        foreach ($item_count as $k=>$value) {
           $vote_item[$k]['per']=(number_format(($value['vcount'] / $vcount),2))*100;
           $vote_item[$k]['pro']=$value['vcount'];
        } 

        if($vote['statdate']>time()){
            $success = array('err'=>1,'info'=>'投票还没有开始');
        }

        if($vote['enddate']<time()){
            $success = array('err'=>2,'info'=>'投票已经结束');
        }

        $this->assign('user_name',$this->wxuser['weixin']);
        $this->assign('success',$success);
        $this->assign('total',$total);
        $this->assign('vote_item', $vote_item);
        $this->assign('vote',$vote);
		$this->display();
	}

	public function add_vote(){	
		$trueip 	= $_SERVER['REMOTE_ADDR'];
		$tid 		= $this->_post('tid');
		$chid 		= rtrim($this->_post('chid'),',');	
		$recdata 	= M('Vote_record');
        $where   	= array('vid'=>$tid,'wecha_id'=>$this->wecha_id,'token'=>$this->token);  
        $recode 	= $recdata->where($where)->find();

        $t_vote = M('Vote'); 
        $vote_info = $t_vote->where(array('token'=>$this->token,'id'=>$tid))->find();             
        
        if($vote_info['statdate']>time()){
            $arr = array('success'=>-1,'info'=>'投票还没有开始');
            echo json_encode($arr);
            exit;
        }

        if($vote_info['enddate']<time()){
            $arr = array('success'=>-2,'info'=>'投票已经结束');
            echo json_encode($arr);
            exit;
        }
        
        if($vote_info['status']==0){
            $arr = array('success'=>-3,'info'=>'投票已经关闭');
            echo json_encode($arr);
            exit;
        }

        if($vote_info['is_reg'] == 1 && empty($this->fans['tel'])){
            $arr = array('success'=>-5,'info'=>'请填写个人信息后再投票');
            echo json_encode($arr);
            exit;
        }

        if(empty($this->wecha_id)){
            $keys   = $this->getKey();
        }else{
            $keys   = $this->wecha_id;
        }

        if($vote_info['refresh'] == '1'){
            $is_voted   = false;
            $cookie     = cookie('Vote');          
            $cookie_arr = json_decode( json_encode( $cookie),true);      

            if(empty($cookie_arr[$vote_info['id']])){
                $cookie_arr[$vote_info['id']]   = array();
            }

            $is_cookie  = $recdata->where(array('token'=>$this->token,'vid'=>$tid,'wecha_id'=>array('in',join(',',$cookie_arr[$vote_info['id']]))))->find();
            if(empty($this->fans)){
                $is_voted   = empty($is_cookie)?false:true;
            }else{
                $is_data    = $recdata->where(array('token'=>$this->token,'vid'=>$tid,'wecha_id'=>$this->wecha_id))->find();
                if($is_data  || $is_cookie){
                    $is_voted   = true;
                }
            }

            if($is_voted) {
                $arr = array('success'=>-4,'info'=>'禁止恶意刷票，换了马甲以为不认识你吗？');
                echo json_encode($arr);
                exit;
            }
        }

        $data = array('item_id'=>$chid,'token'=>$this->token,'vid'=>$tid,'wecha_id'=>$keys,'touch_time'=>time(),'touched'=>1,'trueip'=>$trueip);     
		$ok = $recdata->add($data);

        if($vote_info['refresh'] == '1' && $ok){
            array_push($cookie_arr[$vote_info['id']],$keys);
            cookie('Vote',$cookie_arr,time()+3600*24*365);
        }

        $map['id'] = array('in',$chid);
        $t_item = M('Vote_item');
        $t_item->where($map)->setInc('vcount');       
        $t_vote->where(array('token'=>$this->token,'id'=>$tid))->setInc('count'); //增加投票人数
        $arr=array('success'=>1,'info'=>'投票成功');
        echo json_encode($arr);        
        exit;
	}

    //分享key  最长32
    public function getKey($length=16){
        $str = substr(md5(time().mt_rand(1000,9999)),0,$length);
        return $str;
    }

}?>