<?php
class GroupAction extends BackAction{
	public function _initialize() {
        parent::_initialize();  //RBAC 验证接口初始化
		$is_admin = M('user')->where(array('is_admin'=>1))->find();
		if($is_admin == ''){
			if($_SESSION['username'] == 'admin'){
				$is_admin_save = M('user')->where(array('username'=>$_SESSION['username']))->save(array('is_admin'=>1));
			}
		}
    }
	public function index(){
		$RoleDB = D('Role');
        $list = $RoleDB->getAllRole();
        $this->assign('list',$list);
        $this->display();
	}
	
	public function add(){
		$RoleDB = D("Role");
        if(isset($_POST['dosubmit'])) {
            //根据表单提交的POST数据创建数据对象
            if($RoleDB->create()){
				$Role_id = $RoleDB->add();
                if($Role_id > 0){
					$roleid = $Role_id;
					$nodeid[] = M('Node')->where(array('pid'=>0,'level'=>1,'status'=>1,'display'=>0))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'Site','pid'=>intval($nodeid[0]),'level'=>0,'status'=>1,'display'=>1))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'System','pid'=>intval($nodeid[1]),'level'=>2,'status'=>1,'display'=>0))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'index','pid'=>intval($nodeid[2]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'menu','pid'=>intval($nodeid[2]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'main','pid'=>intval($nodeid[2]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'SystemIndex','pid'=>intval($nodeid[1]),'level'=>2,'status'=>1,'display'=>2))->getField('id');
					$nodeid[] = M('Node')->where(array('name'=>'index','pid'=>intval($nodeid[6]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
					$AccessDB = D('Access');
					if (is_array($nodeid) && count($nodeid) > 0) {  //提交得有数据，则修改原权限配置
						$AccessDB -> delAccess(array('role_id'=>$roleid));  //先删除原用户组的权限配置
						$NodeDB = D('Node');
						$node = $NodeDB->getAllNode();

						foreach ($node as $_v) $node[$_v[id]] = $_v;
						foreach($nodeid as $k => $node_id){
							$data[$k] = $AccessDB -> get_nodeinfo($node_id,$node);
							$data[$k]['role_id'] = $roleid;
						}
						$AccessDB->addAll($data);   // 重新创建角色的权限配置
					} else {    //提交的数据为空，则删除权限配置
						$AccessDB -> delAccess(array('role_id'=>$roleid));
					}
                    $this->assign("jumpUrl",U('Group/index'));
                    $this->success('添加成功！');
                }else{
                     $this->error('添加失败!');
                }
            }else{
                $this->error($RoleDB->getError());
            }
        }else{
            $this->assign('tpltitle','添加');
            $this->display();
        }
	}
	
	public function edit(){
		$RoleDB = D("Role");
        if(IS_POST) {
        	$update = $RoleDB->where(array('id'=>(intval($_POST['id']))))->save($_POST);
			$this->success('修改成功！',U('Group/index'));
        }else{
            $id = $this->_get('id','intval',0);
            if(!$id)$this->error('参数错误!');
            $info = $RoleDB->getRole(array('id'=>$id));
            $this->assign('tpltitle','编辑');
            $this->assign('info',$info);
            $this->display();
        }
	}
	
	public function del(){
		$id = $this->_get('id','intval',0);
        if(!$id)$this->error('参数错误!');
        $RoleDB = D('Role');
        if($RoleDB->delRole('id='.$id)){
            $this->assign("jumpUrl",U('Group/index'));
            $this->success('删除成功！');
        }else{
            $this->error('删除失败!');
        }
	}
	
	// 排序权重更新
    public function role_sort(){
        $sorts = $this->_post('sort');
        if(!is_array($sorts))$this->error('参数错误!');
        foreach ($sorts as $id => $sort) {
            D('Role')->upRole( array('id' =>$id , 'sort' =>intval($sort) ) );
        }
        $this->assign("jumpUrl",U('Group/index'));
        $this->success('更新完成！');
    }

/* ========权限设置部分======== */    
    //权限浏览
    public function access(){
        $roleid = $this->_get('roleid','intval',0);
		$nodeid[] = M('Node')->where(array('pid'=>0,'level'=>1,'status'=>1,'display'=>0))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'Site','pid'=>intval($nodeid[0]),'level'=>0,'status'=>1,'display'=>1))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'System','pid'=>intval($nodeid[1]),'level'=>2,'status'=>1,'display'=>0))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'index','pid'=>intval($nodeid[2]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'menu','pid'=>intval($nodeid[2]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'main','pid'=>intval($nodeid[2]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'SystemIndex','pid'=>intval($nodeid[1]),'level'=>2,'status'=>1,'display'=>2))->getField('id');
		$nodeid[] = M('Node')->where(array('name'=>'index','pid'=>intval($nodeid[6]),'level'=>3,'status'=>1,'display'=>0))->getField('id');
		$AccessDB = D('Access');
		if (is_array($nodeid) && count($nodeid) > 0) {  //提交得有数据，则修改原权限配置
			//$AccessDB -> delAccess(array('role_id'=>$roleid));  //先删除原用户组的权限配置
			foreach($nodeid as $no){
				$del_Access = M('Access')->where(array('role_id'=>$roleid,'node_id'=>$no))->delete();
			}
			$NodeDB = D('Node');
			$node = $NodeDB->getAllNode();

			foreach ($node as $_v) $node[$_v[id]] = $_v;
			foreach($nodeid as $k => $node_id){
				$data[$k] = $AccessDB -> get_nodeinfo($node_id,$node);
				$data[$k]['role_id'] = $roleid;
			}
			$AccessDB->addAll($data);   // 重新创建角色的权限配置
		} else {    //提交的数据为空，则删除权限配置
			$AccessDB -> delAccess(array('role_id'=>$roleid));
		}
        if(!$roleid) $this->error('参数错误!');

        $Tree = new Tree();
        $Tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $Tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $NodeDB = D('Node');
        $node = $NodeDB->getAllNode();

        $AccessDB = D('Access');
        $access = $AccessDB->getAllAccess('','role_id,node_id,pid,level');
       

        foreach ($node as $n=>$t) {
            $node[$n]['checked'] = ($AccessDB->is_checked($t,$roleid,$access))? ' checked' : '';
            $node[$n]['depth'] = $AccessDB->get_level($t['id'],$node);
            $node[$n]['pid_node'] = ($t['pid'])? ' class="tr lt child-of-node-'.$t['pid'].'"' : '';
			if(in_array($t['id'],$nodeid)){
				$node[$n]['disabled'] = 'onclick="return false;"';
			}else{
				$node[$n]['disabled'] = 'onclick="javascript:checknode(this);"';
			}
			
        }
		
        $str  = "<tr id='node-\$id' \$pid_node>
                    <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='nodeid[]' value='\$id' class='radio' level='\$depth' \$checked \$disabled / > \$title (\$name)</td>
                </tr>";

        $Tree->init($node);
        $html_tree = $Tree->get_tree(0, $str);
        $this->assign('html_tree',$html_tree);

        $this->display();
    }

    //权限编辑
    public function access_edit(){
        $roleid = $this->_post('roleid','intval',0);
        $nodeid = $this->_post('nodeid');
		//dump($nodeid);exit;
        if(!$roleid) $this->error('参数错误!');
        $AccessDB = D('Access');
        if (is_array($nodeid) && count($nodeid) > 0) {  //提交得有数据，则修改原权限配置
            $AccessDB -> delAccess(array('role_id'=>$roleid));  //先删除原用户组的权限配置
            $NodeDB = D('Node');
            $node = $NodeDB->getAllNode();

            foreach ($node as $_v) $node[$_v[id]] = $_v;
            foreach($nodeid as $k => $node_id){
                $data[$k] = $AccessDB -> get_nodeinfo($node_id,$node);
                $data[$k]['role_id'] = $roleid;
            }
            $AccessDB->addAll($data);   // 重新创建角色的权限配置
        } else {    //提交的数据为空，则删除权限配置
            $AccessDB -> delAccess(array('role_id'=>$roleid));
        }
        $this->assign("jumpUrl",U('Group/access',array('roleid'=>$roleid)));
        $this->success('设置成功！');
    }
}