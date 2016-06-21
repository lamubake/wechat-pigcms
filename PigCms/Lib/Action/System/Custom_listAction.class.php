<?php
class Custom_listAction extends BackAction{
	
	public function index(){
		$keyword 	= $this->_post('keyword','trim');
		$where 		= '';

		if($keyword){
			$where 	.= 'site.title LIKE "%'.$keyword.'%" OR wx.wxname LIKE "%'.$keyword.'%"  OR user.username  LIKE "%'.$keyword.'%"';
		}

		$count 		= M('Custom_info')->alias('info')
        		->join('LEFT JOIN '.C('DB_PREFIX').'custom_set site ON info.set_id=site.set_id')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'wxuser wx ON wx.token=info.token')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'users user ON wx.uid=user.id')
            ->where('info.token=wx.token')
            ->count();
		$Page     	= new Page($count,15);

        $list 	= M('Custom_info')->alias('info')
        		->join('LEFT JOIN '.C('DB_PREFIX').'custom_set site ON info.set_id=site.set_id')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'wxuser wx ON wx.token=info.token')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'users user ON wx.uid=user.id')
            ->where($where)
            ->field('info.info_id, info.sub_info, info.user_name, info.phone, info.add_time, info.phone, site.title, wx.wxname, user.username')
            ->order('info.add_time desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

		$this->assign('list',$list);
 		$this->assign('page',$Page->show());
		$this->display();
	}

	public function detail(){
		$info_id 	= $this->_get('info_id','intval');
		$where 		= array('token'=>$this->token,'info_id'=>$info_id);
		$info		= D('Custom_info')->where($where)->find();

		$this->assign('sub_info',unserialize($info['sub_info']));
		$this->assign('set_id',$info['set_id']);
		$this->assign('info',$info);
		$this->display();
	}
	
}