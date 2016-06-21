<?php
class WebAction extends UserAction{
	public $token;
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Website');
		$this->token = $this->_session('token');
	}
	
	//电脑网站配置
	public function set(){
		$database_pc_config = D('Pc_config');
		$condition_pc_config['token'] = $this->token;
		$pc_config = $database_pc_config->field(true)->where($condition_pc_config)->find();
		if($pc_config['other_info']){
			$pc_config['other_info'] = unserialize($pc_config['other_info']);
		}else{
			unset($pc_config['other_info']);
		}
		if($pc_config['site_tpl']){
			$pc_config['site_tpl'] = unserialize($pc_config['site_tpl']);
		}else{
			unset($pc_config['site_tpl']);
		}
		$this->assign('pc_config',$pc_config);
		
		$database_pc_site = D('Pc_site');
		$condition_pc_site['token'] = $this->token;
		$pc_site = $database_pc_site->field(true)->where($condition_pc_site)->order('`id` ASC')->select();
		if(is_array($pc_site)){
			foreach($pc_site as $key=>$value){
				$pc_site_arr[] = $value['site'];
			}
			$pc_site = htmlspecialchars(implode(PHP_EOL,$pc_site_arr));
			$this->assign('pc_site',$pc_site);		
			
			$first_pc_site = $pc_site_arr[0];
			$this->assign('first_pc_site',$first_pc_site);
		}else {
			if ($_SERVER['HTTP_HOST']=='demo.pigcms.cn'){
			$this->assign('pc_site',$this->token.'.maopan.com');
			}
		}
		if(IS_POST){
			if (!$_POST['site_logo']){
				$this->error('请设置网站logo');
			}
			$pc_site_arr_new = explode(PHP_EOL,$_POST['pc_site']);
			//判断如果有过修改绑定域名，则删除原来的，添加后加的
			$token = $this->token;
			$pc_domain = M('pc_site')->where($token)->find();
			if(!empty($pc_domain) && ($token != $pc_domain['token'])){
				$this->error('该域名已经存在了！');
			}
			if($_POST['pc_site'] != $pc_site){
				$database_pc_site->where($condition_pc_site)->delete();
				if(is_array($pc_site_arr_new)){
					$data_pc_site['token'] = $this->token;
					foreach($pc_site_arr_new as $key=>$value){
						if(!empty($value)){
							$data_pc_site['site'] = $value;
							$database_pc_site->data($data_pc_site)->add();
						}
					}
				}
			}
		
			//删除每个域名的缓存。
			if(is_array($pc_site_arr)){
				foreach($pc_site_arr as $key=>$value){
					if(!empty($value)){
						S('now_website'.$value,NULL);
					}
				}
			}
			
			
			if($pc_config['other_info'] && !empty($_POST['other_info'])){
				foreach($pc_config['other_info'] as $key=>$value){
					$pc_config['other_info'][$key]['info'] = $_POST['other_info'][$key];
				}
				$_POST['other_info'] = serialize($pc_config['other_info']);
			}else{
				unset($_POST['other_info']);
			}
			
			unset($_POST['pc_site'],$_POST[C('TOKEN_NAME')]);
			$_POST['site_count'] = stripslashes(htmlspecialchars_decode($_POST['site_count']));
			if($pc_config){
				$database_pc_config->where(array('token'=>$this->token))->data($_POST)->save();
			}else{
				$_POST['token'] = $this->token;
				$database_pc_config->data($_POST)->add();
				
				//插入导航表每个号默认的数据。
				$database_pc_nav = D('Pc_nav');
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'公司介绍','s_name'=>'Contact Us','url'=>'/page/about.html','key'=>'about','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'产品中心','s_name'=>'Products','url'=>'/productcat/all.html','key'=>'allproduct','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'网站新闻','s_name'=>'News','url'=>'/newscat/all.html','key'=>'allnews','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'人才招聘','s_name'=>'Jobs','url'=>'/page/jobs.html','key'=>'jobs','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'网站留言','s_name'=>'Books','url'=>'/books.html','key'=>'books','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'友情链接','s_name'=>'Friend Links','url'=>'/page/links.html','key'=>'links','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'下载专区','s_name'=>'Down','url'=>'/downcat/all.html','key'=>'alldown','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				$data_pc_nav = array('fid'=>0,'token'=>$this->token,'name'=>'联系我们','s_name'=>'Contact Us','url'=>'/page/contact.html','key'=>'contact','sort'=>0);
				$database_pc_nav->data($data_pc_nav)->add();
				
				//插入自定义页面每个号默认的数据。
				$database_pc_page = D('Pc_page');
				$data_pc_page = array('token'=>$this->token,'title'=>'关于我们','s_title'=>'About Us','key'=>'about','pic'=>'','info'=>'','content'=>'关于我们');
				$database_pc_page->data($data_pc_page)->add();
				$data_pc_page = array('token'=>$this->token,'title'=>'人才招聘','s_title'=>'Jobs','key'=>'jobs','pic'=>'','info'=>'','content'=>'人才招聘');
				$database_pc_page->data($data_pc_page)->add();
				$data_pc_page = array('token'=>$this->token,'title'=>'友情链接','s_title'=>'Friend Links','key'=>'links','pic'=>'','info'=>'','content'=>'友情链接');
				$database_pc_page->data($data_pc_page)->add();
				$data_pc_page = array('token'=>$this->token,'title'=>'联系我们','s_title'=>'Contact Us','key'=>'contact','pic'=>'','info'=>'','content'=>'联系我们');
				$database_pc_page->data($data_pc_page)->add();
				
				//插入万能表单表默认的数据
				$database_custom_limit = D('Custom_limit');
				$data_custom_limit = array('enddate'=>'','sub_total'=>'0','today_total'=>'0','type'=>'0');
				if($limit_id = $database_custom_limit->data($data_custom_limit)->add()){
					$database_custom_set = D('Custom_set');
					$data_custom_set = array('title'=>'电脑版网上预约','keyword'=>'电脑版网上预约','intro'=>'','addtime'=>$_SERVER['REQUEST_TIME'],'top_pic'=>'','succ_info'=>'提交成功','err_info'=>'提交失败','detail'=>'电脑版网上预约，删除此表单将可能导致电脑版网站不完整。','limit_id'=>$limit_id,'token'=>$this->token,'tel'=>'','address'=>'电脑版网上预约','longitude'=>'0','latitude'=>'0');
					if($set_id = $database_custom_set->data($data_custom_set)->add()){
						$database_custom_field = D('Custom_field');
						$data_custom_field = array('field_name'=>'昵称','filed_option'=>'','field_type'=>'text','item_name'=>'apb6pm_4','field_match'=>'','is_show'=>'1','is_empty'=>'1','sort'=>'50','err_info'=>'昵称不能为空！','set_id'=>$set_id,'token'=>$this->token);
						$database_custom_field->data($data_custom_field)->add();
						$data_custom_field = array('field_name'=>'留言内容','filed_option'=>'','field_type'=>'textarea','item_name'=>'muyxab_4','field_match'=>'','is_show'=>'1','is_empty'=>'1','sort'=>'50','err_info'=>'留言内容必填！','set_id'=>$set_id,'token'=>$this->token);
						$database_custom_field->data($data_custom_field)->add();
						$data_custom_field = array('field_name'=>'联系电话','filed_option'=>'','field_type'=>'text','item_name'=>'iitde_4','field_match'=>'','is_show'=>'1','is_empty'=>'0','sort'=>'50','err_info'=>'','set_id'=>$set_id,'token'=>$this->token);
						$database_custom_field->data($data_custom_field)->add();
						$data_custom_field = array('field_name'=>'性别','filed_option'=>'男|女','field_type'=>'select','item_name'=>'d5xaw3_4','field_match'=>'','is_show'=>'1','is_empty'=>'1','sort'=>'50','err_info'=>'','set_id'=>$set_id,'token'=>$this->token);
						$database_custom_field->data($data_custom_field)->add();
					}
				}
				
			}
			file_get_contents('http://'.$_POST['pc_site']);
			$this->success('修改成功！');
		}else{
			
			
			$this->display();
		}
	}
	
	//导航菜单
	public function nav(){
		$database_pc_nav = D('Pc_nav');
		if($_GET['act'] == 'nav_add'){
			$condition_pc_nav['token'] = $this->token;
			$condition_pc_nav['fid'] = 0;
			$pc_nav = $database_pc_nav->field(true)->where($condition_pc_nav)->select();
			$this->assign('pc_nav',$pc_nav);
			
			$this->display('nav_add');
		}else if($_GET['act'] == 'nav_add_modify'){
			if(empty($_POST['name'])){
				$this->error('请填写导航名称！');
			}
			$data_pc_nav['token'] = $this->token;
			$data_pc_nav['name'] = $this->_post('name');
			$data_pc_nav['s_name'] = $this->_post('s_name');
			$data_pc_nav['fid'] = $this->_post('fid');
			$data_pc_nav['url'] = $this->_post('url');
			$data_pc_nav['key'] = $this->_post('key');
			$data_pc_nav['sort'] = $this->_post('sort');
			if($database_pc_nav->data($data_pc_nav)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}else if($_GET['act'] == 'nav_edit'){
			$condition_pc_nav['token'] = $this->token;
			$condition_pc_nav['fid'] = 0;
			$pc_nav = $database_pc_nav->field(true)->where($condition_pc_nav)->select();
			$this->assign('pc_nav',$pc_nav);
			
			$condition_now_nav['id'] = $this->_get('id');
			$condition_now_nav['token'] = $this->token;
			$now_nav = $database_pc_nav->field(true)->where($condition_now_nav)->find();
			$this->assign('now_nav',$now_nav);
			
			$this->display('nav_edit');
		}else if($_GET['act'] == 'nav_add_amend'){
			if(empty($_POST['name'])){
				$this->error('请填写导航名称！');
			}
			$condition_pc_nav['id'] = $this->_post('id');
			$condition_pc_nav['token'] = $this->token;
			$data_pc_nav['name'] = $this->_post('name');
			$data_pc_nav['s_name'] = $this->_post('s_name');
			$data_pc_nav['fid'] = $this->_post('fid');
			$data_pc_nav['url'] = $this->_post('url');
			$data_pc_nav['key'] = $this->_post('key');
			$data_pc_nav['sort'] = $this->_post('sort');
			if($database_pc_nav->where($condition_pc_nav)->data($data_pc_nav)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！');
			}
		}else if($_GET['act'] == 'nav_del'){
			$condition_pc_nav['id'] = $this->_get('id');
			$condition_pc_nav['token'] = $this->token;
			$pc_nav = $database_pc_nav->field('`id`,`fid`')->where($condition_pc_nav)->find();
			if(!empty($pc_nav)){
				$condition_del_pc_nav['id'] = $pc_nav['id'];
				if($database_pc_nav->where($condition_del_pc_nav)->delete()){
					if(empty($pc_nav['fid'])){
						$condition_del_pc_pnav['fid'] = $pc_nav['id'];
						$database_pc_nav->where($condition_del_pc_pnav)->delete();
					}
					$this->success('导航删除成功！');
				}else{
					$this->error('导航删除失败！');
				}
			}else{
				$this->error('导航不存在！');
			}
		}else{
			$condition_pc_nav['fid'] = 0;
			$condition_pc_nav['token'] = $this->token;
			$pc_nav = $database_pc_nav->field(true)->where($condition_pc_nav)->order('`sort` DESC,`id` ASC')->select();
			foreach($pc_nav as $key=>$value){
				$condition_pc_nav['fid'] = $value['id'];
				$pc_nav[$key]['nav_list'] = $database_pc_nav->field(true)->where($condition_pc_nav)->order('`sort` DESC,`id` ASC')->select();
			}
			$this->assign('pc_nav',$pc_nav);

			$this->display();
		}
	}
	
	//根据模型选择链接
	public function get_nav_list(){
		$type = $this->_get('type');
		if($type == 'page'){
			$condition_page['token'] = $this->token;
			$page_list = D('Pc_page')->field('`id`,`title`,`key`')->where($condition_page)->order('`id` DESC')->select();
			if($page_list){
				foreach($page_list as $key=>$value){
					$page_list[$key]['name'] = $value['title'];
					$page_list[$key]['url'] = '/page/'.$value['key'].'.html';
				}
				exit(json_encode(array('error'=>0,'list'=>$page_list)));
			}else{
				exit(json_encode(array('error'=>1,'msg'=>'您需要先添加自定义页面！')));
			}
		}else if($type == 'news_cat'){
			$condition_news_category['token'] = $this->token;
			$news_category = D('Pc_news_category')->where($condition_news_category)->field(true)->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$first_li = array(
							'name'=>'顶级分类',
							'key'=>'allnews',
							'url'=>'/newscat/all.html',
						);
			if($news_category){
				foreach($news_category as $key=>$value){
					$news_category[$key]['name'] = $value['cat_name'];
					$news_category[$key]['key'] = $value['cat_key'];
					$news_category[$key]['url'] = '/newscat/'.$value['cat_id'].'.html';
				}
				array_unshift($news_category,$first_li);
			}else{
				$news_category[0] = $first_li;
			}
			exit(json_encode(array('error'=>0,'list'=>$news_category)));
		}else if($type == 'product_cat'){
			$condition_product_category['token'] = $this->token;
			$product_category = D('Pc_product_category')->where($condition_product_category)->field(true)->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$first_li = array(
							'name'=>'顶级分类',
							'key'=>'allproduct',
							'url'=>'/productcat/all.html',
						);
			if($product_category){
				foreach($product_category as $key=>$value){
					$product_category[$key]['name'] = $value['cat_name'];
					$product_category[$key]['key'] = $value['cat_key'];
					$product_category[$key]['url'] = '/productcat/'.$value['cat_id'].'.html';
				}
				array_unshift($product_category,$first_li);
			}else{
				$product_category[0] = $first_li;
			}
			exit(json_encode(array('error'=>0,'list'=>$product_category)));
		}else if($type == 'down_cat'){
			$condition_down_category['token'] = $this->token;
			$down_category = D('Pc_down_category')->where($condition_down_category)->field(true)->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$first_li = array(
							'name'=>'顶级分类',
							'key'=>'alldown',
							'url'=>'/downcat/all.html',
						);
			if($down_category){
				foreach($down_category as $key=>$value){
					$down_category[$key]['name'] = $value['cat_name'];
					$down_category[$key]['key'] = $value['cat_key'];
					$down_category[$key]['url'] = '/downcat/'.$value['cat_id'].'.html';
				}
				array_unshift($down_category,$first_li);
			}else{
				$down_category[0] = $first_li;
			}
			exit(json_encode(array('error'=>0,'list'=>$down_category)));
		}else if($type == 'single_page'){
			$return_array[0] = array(
									'name'=>'网站留言',
									'key'=>'books',
									'url'=>'/books.html',
								);
			exit(json_encode(array('error'=>0,'list'=>$return_array)));
		}else{
			exit(json_encode(array('error'=>1,'msg'=>'暂不存在此模型！')));
		}
	}

	//页面
	public function page(){
		$database_pc_page = D('Pc_page');
		if($_GET['act'] == 'page_add'){	//添加文章分类
			if(IS_POST){
				if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['key'])){
					$this->error('标题、内容、关键词必填！');
				}
				$data_pc_page['token'] = $this->token;
				$data_pc_page['cat_id'] = $this->_post('cat_id');
				$data_pc_page['title'] = $this->_post('title');
				$data_pc_page['s_title'] = $this->_post('s_title');
				$data_pc_page['pic'] = $this->_post('pic');
				$data_pc_page['key'] = $this->_post('key');
				$data_pc_page['info'] = $this->_post('info');
				$data_pc_page['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				
				if($database_pc_page->data($data_pc_page)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$this->display('page_add');
			}
		}else if($_GET['act'] == 'page_edit'){	//添加文章分类
			if(IS_POST){
				if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['key'])){
					$this->error('标题、内容、关键词必填！');
				}
				$condition_pc_page['id'] = $this->_post('id');
				$condition_pc_page['token'] = $this->token;
				$data_pc_page['cat_id'] = $this->_post('cat_id');
				$data_pc_page['title'] = $this->_post('title');
				$data_pc_page['s_title'] = $this->_post('s_title');
				$data_pc_page['pic'] = $this->_post('pic');
				$data_pc_page['key'] = $this->_post('key');
				$data_pc_page['info'] = $this->_post('info');
				$data_pc_page['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				
				if($database_pc_page->where($condition_pc_page)->data($data_pc_page)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}else{
				$condition_pc_page['id'] = $this->_get('id');
				$condition_pc_page['token'] = $this->token;
				$now_page = $database_pc_page->field(true)->where($condition_pc_page)->find();
				$this->assign('now_page',$now_page);
				
				$this->display('page_edit');
			}
		}else if($_GET['act'] == 'page_del'){
			$condition_page['id'] = $this->_get('id');
			$condition_page['token'] = $this->token;
			if($database_pc_page->where($condition_page)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else{
			$condition_page['token'] = $this->token;
			$page_list = $database_pc_page->field(true)->where($condition_page)->order('`id` DESC')->select();
			$this->assign('page_list',$page_list);
			
			$this->display();
		}
	}
	
	//文章
	public function news(){
		$database_pc_news_category = D('Pc_news_category');
		if($_GET['act'] == 'news_cat_add'){	//添加文章分类
			$this->display('news_cat_add');
		}else if($_GET['act'] == 'news_cat_modify'){	//添加文章分类提交
			if(!empty($_POST['cat_name'])){
				$data_pc_news_category['token'] = $this->token;
				$data_pc_news_category['cat_name'] = $this->_post('cat_name');
				$data_pc_news_category['cat_key'] = $this->_post('cat_key');
				$data_pc_news_category['cat_sort'] = $this->_post('cat_sort');
				if($database_pc_news_category->data($data_pc_news_category)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$this->error('请填写分类名称！');
			}
		}else if($_GET['act'] == 'news_cat_edit'){	//编辑文章分类
			$condition_pc_news_category['cat_id'] = $this->_get('cat_id');
			$condition_pc_news_category['token'] = $this->token;
			$news_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->find();
			if(empty($news_category)){
				$this->error('分类不存在！');
			}
			$this->assign('news_category',$news_category);
			
			$this->display('news_cat_edit');
		}else if($_GET['act'] == 'news_cat_amend'){		//编辑文章分类提交
			if(!empty($_POST['cat_name'])){
				$condition_pc_news_category['cat_id'] = $this->_post('cat_id');
				$condition_pc_news_category['token'] = $this->token;
				$data_pc_news_category['cat_name'] = $this->_post('cat_name');
				$data_pc_news_category['cat_key'] = $this->_post('cat_key');
				$data_pc_news_category['cat_sort'] = $this->_post('cat_sort');
				if($database_pc_news_category->where($condition_pc_news_category)->data($data_pc_news_category)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}else{
				$this->error('请填写分类名称！');
			}
		}else if($_GET['act'] == 'news_cat_del'){		//删除文章分类
			$cat_id = $this->_get('cat_id');
			$condition_pc_news_category['cat_id'] = $cat_id;
			$condition_pc_news_category['token'] = $this->token;
			if($database_pc_news_category->where($condition_pc_news_category)->delete()){
				$database_pc_news = D('Pc_news');
				$condition_pc_news['cat_id'] = $cat_id;
				$database_pc_news->where($condition_pc_news)->delete();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else if($_GET['act'] == 'news_list'){	//文章列表
			$condition_pc_news_category['cat_id'] = $this->_get('cat_id');
			$condition_pc_news_category['token'] = $this->token;
			$now_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->find();
			$this->assign('now_category',$now_category);
			
			$database_pc_news = D('Pc_news');
			$condition_pc_news['cat_id'] = $this->_get('cat_id');
			$condition_pc_news['token'] = $this->token;
			$news_list = $database_pc_news->where($condition_pc_news)->order('`id` DESC')->select();
			$this->assign('news_list',$news_list);
			
			$this->display('news_list');
		}else if($_GET['act'] == 'news_add'){	//添加文章
			if(IS_POST){
				$database_pc_news = D('Pc_news');
				if(empty($_POST['title']) || empty($_POST['content'])){
					$this->error('标题和内容必填！');
				}
				$data_pc_news['cat_id'] = $this->_post('cat_id');
				$data_pc_news['token'] = $this->token;
				$data_pc_news['title'] = $this->_post('title');
				$data_pc_news['key'] = $this->_post('key');
				$data_pc_news['info'] = $this->_post('info');
				$data_pc_news['pic'] = $this->_post('pic');
				$data_pc_news['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				$data_pc_news['time'] = $_SERVER['REQUEST_TIME'];
				
				if($database_pc_news->data($data_pc_news)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$condition_pc_news_category['token'] = $this->token;
				$news_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
				$this->assign('news_category',$news_category);
				if(empty($news_category)){
					$this->error('请先添加一个文章分类！');
				}
				
				$this->display('news_add');
			}
		}else if($_GET['act'] == 'news_edit'){	//编辑文章
			if(IS_POST){
				$database_pc_news = D('Pc_news');
				if(empty($_POST['title']) || empty($_POST['content'])){
					$this->error('标题和内容必填！');
				}
				$condition_pc_news['token'] = $this->token;
				$condition_pc_news['id'] = $this->_post('id');
				$data_pc_news['cat_id'] = $this->_post('cat_id');
				$data_pc_news['title'] = $this->_post('title');
				$data_pc_news['key'] = $this->_post('key');
				$data_pc_news['info'] = $this->_post('info');
				$data_pc_news['pic'] = $this->_post('pic');
				$data_pc_news['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				if($_POST['update_time']){
					$data_pc_news['time'] = $_SERVER['REQUEST_TIME'];
				}
				
				if($database_pc_news->where($condition_pc_news)->data($data_pc_news)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！请检查是否有过修改再重新提交。');
				}
			}else{
				$database_pc_news = D('Pc_news');
				$condition_pc_news['id'] = $this->_get('id');
				$condition_pc_news['token'] = $this->token;
				$now_news = $database_pc_news->field(true)->where($condition_pc_news)->find();
				if(empty($now_news)){
					$this->error('文章不存在！');
				}
				$this->assign('now_news',$now_news);
				
				$condition_pc_news_category['token'] = $this->token;
				$news_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
				$this->assign('news_category',$news_category);
				
				$this->display('news_edit');
			}
		}else if($_GET['act'] == 'news_del'){
			$database_pc_news = D('Pc_news');
			$condition_pc_news['id'] = $this->_get('id');
			$condition_pc_news['token'] = $this->token;
			if($database_pc_news->where($condition_pc_news)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else{
			$condition_pc_news_category['token'] = $this->token;
			$news_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$this->assign('news_category',$news_category);
			
			$this->display();
		}
	}
	
	//产品
	public function product(){
		$database_pc_product_category = D('Pc_product_category');
		if($_GET['act'] == 'product_cat_add'){	//添加分类
			$this->display('product_cat_add');
		}else if($_GET['act'] == 'product_cat_modify'){	//添加分类提交
			if(!empty($_POST['cat_name'])){
				$data_pc_product_category['token'] = $this->token;
				$data_pc_product_category['cat_name'] = $this->_post('cat_name');
				$data_pc_product_category['cat_key'] = $this->_post('cat_key');
				$data_pc_product_category['cat_sort'] = $this->_post('cat_sort');
				if($database_pc_product_category->data($data_pc_product_category)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$this->error('请填写分类名称！');
			}
		}else if($_GET['act'] == 'product_cat_edit'){	//编辑分类
			$condition_pc_product_category['cat_id'] = $this->_get('cat_id');
			$condition_pc_product_category['token'] = $this->token;
			$product_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->find();
			if(empty($product_category)){
				$this->error('分类不存在！');
			}
			$this->assign('product_category',$product_category);
			
			$this->display('product_cat_edit');
		}else if($_GET['act'] == 'product_cat_amend'){		//编辑分类提交
			if(!empty($_POST['cat_name'])){
				$condition_pc_product_category['cat_id'] = $this->_post('cat_id');
				$condition_pc_product_category['token'] = $this->token;
				$data_pc_product_category['cat_name'] = $this->_post('cat_name');
				$data_pc_product_category['cat_key'] = $this->_post('cat_key');
				$data_pc_product_category['cat_sort'] = $this->_post('cat_sort');
				if($database_pc_product_category->where($condition_pc_product_category)->data($data_pc_product_category)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}else{
				$this->error('请填写分类名称！');
			}
		}else if($_GET['act'] == 'product_cat_del'){		//删除分类
			$cat_id = $this->_get('cat_id');
			$condition_pc_product_category['cat_id'] = $cat_id;
			$condition_pc_product_category['token'] = $this->token;
			if($database_pc_product_category->where($condition_pc_product_category)->delete()){
				$database_pc_product = D('Pc_product');
				$condition_pc_product['cat_id'] = $cat_id;
				$database_pc_product->where($condition_pc_product)->delete();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else if($_GET['act'] == 'product_list'){	//添加文章分类
			$condition_pc_product_category['cat_id'] = $this->_get('cat_id');
			$condition_pc_product_category['token'] = $this->token;
			$now_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->find();
			$this->assign('now_category',$now_category);
			
			$database_pc_product = D('Pc_product');
			$condition_pc_product['cat_id'] = $this->_get('cat_id');
			$condition_pc_product['token'] = $this->token;
			$product_list = $database_pc_product->where($condition_pc_product)->order('`id` DESC')->select();
			$this->assign('product_list',$product_list);
			
			$this->display('product_list');
		}else if($_GET['act'] == 'product_add'){	//添加产品
			if(IS_POST){
				$database_pc_product = D('Pc_product');
				if(empty($_POST['title']) || empty($_POST['content'])){
					$this->error('标题和内容必填！');
				}
				$data_pc_product['cat_id'] = $this->_post('cat_id');
				$data_pc_product['token'] = $this->token;
				$data_pc_product['title'] = $this->_post('title');
				$data_pc_product['key'] = $this->_post('key');
				$data_pc_product['info'] = $this->_post('info');
				$data_pc_product['pic'] = $this->_post('pic');
				$data_pc_product['price'] = $this->_post('price');
				$data_pc_product['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				$data_pc_product['time'] = $_SERVER['REQUEST_TIME'];
				
				if($database_pc_product->data($data_pc_product)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$condition_pc_product_category['token'] = $this->token;
				$product_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
				$this->assign('product_category',$product_category);
				if(empty($product_category)){
					$this->error('请先添加一个产品分类！');
				}
				
				$this->display('product_add');
			}
		}else if($_GET['act'] == 'product_edit'){	//编辑产品
			if(IS_POST){
				$database_pc_product = D('Pc_product');
				if(empty($_POST['title']) || empty($_POST['content'])){
					$this->error('标题和内容必填！');
				}
				$condition_pc_product['token'] = $this->token;
				$condition_pc_product['id'] = $this->_post('id');
				$data_pc_product['cat_id'] = $this->_post('cat_id');
				$data_pc_product['title'] = $this->_post('title');
				$data_pc_product['key'] = $this->_post('key');
				$data_pc_product['info'] = $this->_post('info');
				$data_pc_product['pic'] = $this->_post('pic');
				$data_pc_product['price'] = $this->_post('price');
				$data_pc_product['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				if($_POST['update_time']){
					$data_pc_product['time'] = $_SERVER['REQUEST_TIME'];
				}
				
				if($database_pc_product->where($condition_pc_product)->data($data_pc_product)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！请检查是否有过修改再重新提交。');
				}
			}else{
				$database_pc_product = D('Pc_product');
				$condition_pc_product['id'] = $this->_get('id');
				$condition_pc_product['token'] = $this->token;
				$now_news = $database_pc_product->field(true)->where($condition_pc_product)->find();
				if(empty($now_news)){
					$this->error('文章不存在！');
				}
				$this->assign('now_news',$now_news);
				
				$condition_pc_product_category['token'] = $this->token;
				$product_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
				$this->assign('product_category',$product_category);
				
				$this->display('product_edit');
			}
		}else if($_GET['act'] == 'product_del'){
			$database_pc_product = D('Pc_product');
			$condition_pc_product['id'] = $this->_get('id');
			$condition_pc_product['token'] = $this->token;
			if($database_pc_product->where($condition_pc_product)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else{
			$condition_pc_product_category['token'] = $this->token;
			$news_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$this->assign('news_category',$news_category);
			
			$this->display();
		}
	}
	
	//下载
	public function down(){
		$database_pc_down_category = D('Pc_down_category');
		if($_GET['act'] == 'down_cat_add'){	//添加分类
			$this->display('down_cat_add');
		}else if($_GET['act'] == 'down_cat_modify'){	//添加分类提交
			if(!empty($_POST['cat_name'])){
				$data_pc_down_category['token'] = $this->token;
				$data_pc_down_category['cat_name'] = $this->_post('cat_name');
				$data_pc_down_category['cat_key'] = $this->_post('cat_key');
				$data_pc_down_category['cat_sort'] = $this->_post('cat_sort');
				if($database_pc_down_category->data($data_pc_down_category)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$this->error('请填写分类名称！');
			}
		}else if($_GET['act'] == 'down_cat_edit'){	//编辑分类
			$condition_pc_down_category['cat_id'] = $this->_get('cat_id');
			$condition_pc_down_category['token'] = $this->token;
			$down_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->find();
			if(empty($down_category)){
				$this->error('分类不存在！');
			}
			$this->assign('down_category',$down_category);
			
			$this->display('down_cat_edit');
		}else if($_GET['act'] == 'down_cat_amend'){		//编辑分类提交
			if(!empty($_POST['cat_name'])){
				$condition_pc_down_category['cat_id'] = $this->_post('cat_id');
				$condition_pc_down_category['token'] = $this->token;
				$data_pc_down_category['cat_name'] = $this->_post('cat_name');
				$data_pc_down_category['cat_key'] = $this->_post('cat_key');
				$data_pc_down_category['cat_sort'] = $this->_post('cat_sort');
				if($database_pc_down_category->where($condition_pc_down_category)->data($data_pc_down_category)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}else{
				$this->error('请填写分类名称！');
			}
		}else if($_GET['act'] == 'down_cat_del'){		//删除分类
			$cat_id = $this->_get('cat_id');
			$condition_pc_down_category['cat_id'] = $cat_id;
			$condition_pc_down_category['token'] = $this->token;
			if($database_pc_down_category->where($condition_pc_down_category)->delete()){
				$database_pc_down = D('Pc_down');
				$condition_pc_down['cat_id'] = $cat_id;
				$database_pc_down->where($condition_pc_down)->delete();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else if($_GET['act'] == 'down_list'){	//内容列表
			$condition_pc_down_category['cat_id'] = $this->_get('cat_id');
			$condition_pc_down_category['token'] = $this->token;
			$now_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->find();
			$this->assign('now_category',$now_category);
			
			$database_pc_down = D('Pc_down');
			$condition_pc_down['cat_id'] = $this->_get('cat_id');
			$condition_pc_down['token'] = $this->token;
			$down_list = $database_pc_down->where($condition_pc_down)->order('`id` DESC')->select();
			$this->assign('down_list',$down_list);
			
			$this->display('down_list');
		}else if($_GET['act'] == 'down_add'){	//添加内容
			if(IS_POST){
				$database_pc_down = D('Pc_down');
				if(empty($_POST['title']) || empty($_POST['file'])){
					$this->error('标题和内容必填！');
				}
				$data_pc_down['cat_id'] = $this->_post('cat_id');
				$data_pc_down['token'] = $this->token;
				$data_pc_down['title'] = $this->_post('title');
				$data_pc_down['key'] = $this->_post('key');
				$data_pc_down['info'] = $this->_post('info');
				$data_pc_down['pic'] = $this->_post('pic');
				$data_pc_down['file'] = $this->_post('file');
				$data_pc_down['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				$data_pc_down['time'] = $_SERVER['REQUEST_TIME'];
				
				if($database_pc_down->data($data_pc_down)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$condition_pc_down_category['token'] = $this->token;
				$down_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
				$this->assign('down_category',$down_category);
				if(empty($down_category)){
					$this->error('请先添加一个下载分类！');
				}
				
				$this->display('down_add');
			}
		}else if($_GET['act'] == 'down_edit'){	//编辑产品
			if(IS_POST){
				$database_pc_down = D('Pc_down');
				if(empty($_POST['title']) || empty($_POST['content'])){
					$this->error('标题和内容必填！');
				}
				$condition_pc_down['token'] = $this->token;
				$condition_pc_down['id'] = $this->_post('id');
				$data_pc_down['cat_id'] = $this->_post('cat_id');
				$data_pc_down['title'] = $this->_post('title');
				$data_pc_down['key'] = $this->_post('key');
				$data_pc_down['info'] = $this->_post('info');
				$data_pc_down['pic'] = $this->_post('pic');
				$data_pc_down['file'] = $this->_post('file');
				$data_pc_down['content'] = $this->_post('content','stripslashes,htmlspecialchars_decode');
				if($_POST['update_time']){
					$data_pc_down['time'] = $_SERVER['REQUEST_TIME'];
				}
				
				if($database_pc_down->where($condition_pc_down)->data($data_pc_down)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！请检查是否有过修改再重新提交。');
				}
			}else{
				$database_pc_down = D('Pc_down');
				$condition_pc_down['id'] = $this->_get('id');
				$condition_pc_down['token'] = $this->token;
				$now_news = $database_pc_down->field(true)->where($condition_pc_down)->find();
				if(empty($now_news)){
					$this->error('文章不存在！');
				}
				$this->assign('now_news',$now_news);
				
				$condition_pc_down_category['token'] = $this->token;
				$down_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
				$this->assign('down_category',$down_category);
				
				$this->display('down_edit');
			}
		}else if($_GET['act'] == 'down_del'){
			$database_pc_down = D('Pc_down');
			$condition_pc_down['id'] = $this->_get('id');
			$condition_pc_down['token'] = $this->token;
			if($database_pc_down->where($condition_pc_down)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else{
			$condition_pc_down_category['token'] = $this->token;
			$news_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$this->assign('news_category',$news_category);
			
			$this->display();
		}
	}
	
	//轮播图
	public function flash(){
		$database_pc_flash_cat = D('Pc_flash_cat');

		if($_GET['act'] == 'cat_add'){	//添加轮播图分类
			$this->display('flash_cat_add');
		}elseif($_GET['act'] == 'cat_add_modify'){	//添加轮播图提交
			if(empty($_POST['cat_name']) || empty($_POST['cat_key'])){
				$this->error('所有项均必填！');
			}else{
				$data_pc_flash_cat['token'] = $this->token;
				$data_pc_flash_cat['cat_name'] = $this->_post('cat_name');
				$data_pc_flash_cat['cat_key'] = $this->_post('cat_key');
				if($database_pc_flash_cat->data($data_pc_flash_cat)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}
		}elseif($_GET['act'] == 'cat_edit'){	//编辑轮播图分类
			$condition_pc_flash_cat['cat_id'] = $this->_get('cat_id');
			$condition_pc_flash_cat['token'] = $this->token;
			$pc_flash_cat = $database_pc_flash_cat->field(true)->where($condition_pc_flash_cat)->find();
			if(empty($pc_flash_cat)){
				$this->error('没找到您要编辑的内容！');
			}
			$this->assign('pc_flash_cat',$pc_flash_cat);
			
			$this->display('flash_cat_edit');
		}elseif($_GET['act'] == 'cat_edit_amend'){	//编辑轮播图分类提交
			if(empty($_POST['cat_name']) || empty($_POST['cat_key'])){
				$this->error('所有项均必填！');
			}else{
				$condition_pc_flash_cat['cat_id'] = $this->_post('cat_id');
				$condition_pc_flash_cat['token'] = $this->token;
				$data_pc_flash_cat['cat_name'] = $this->_post('cat_name');
				$data_pc_flash_cat['cat_key'] = $this->_post('cat_key');
				if($database_pc_flash_cat->where($condition_pc_flash_cat)->data($data_pc_flash_cat)->save()){
					$this->success('保存成功！');
				}else{
					$this->error('编辑失败！请确认是否修改过内容。');
				}
			}
		}elseif($_GET['act'] == 'cat_del'){	//删除轮播图提交
			$condition_pc_flash_cat['cat_id'] = $this->_get('cat_id');
			$condition_pc_flash_cat['token'] = $this->token;
			
			if($database_pc_flash_cat->where($condition_pc_flash_cat)->delete()){
				$database_pc_flash = D('Pc_flash');
				$condition_pc_flash['cat_id'] = $this->_get('cat_id');
				$condition_pc_flash['token'] = $this->token;
				$database_pc_flash->where($condition_pc_flash)->delete();	
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
			
		}elseif($_GET['act'] == 'flash_list'){	//轮播图列表
			$condition_pc_flash_cat['cat_id'] = $this->_get('cat_id');
			$condition_pc_flash_cat['token'] = $this->token;
			$pc_flash_cat = $database_pc_flash_cat->field(true)->where($condition_pc_flash_cat)->find();
			$this->assign('pc_flash_cat',$pc_flash_cat);
			if(empty($pc_flash_cat)){
				$this->error('分类不存在！');
			}

			$database_pc_flash = D('Pc_flash');
			$condition_pc_flash['cat_id'] = $this->_get('cat_id');
			$condition_pc_flash['token'] = $this->token;
			$pc_flash = $database_pc_flash->where($condition_pc_flash)->order('`id` DESC')->select();
			$this->assign('pc_flash',$pc_flash);

			$this->display('flash_list');
		}elseif($_GET['act'] == 'flash_add'){	//添加图片
			if(IS_POST){
				if(empty($_POST['cat_id'])){
					$this->error('请填写分类ID');
				}
				if(empty($_POST['name'])){
					$this->error('请填写图片名称');
				}
				if(empty($_POST['pic'])){
					$this->error('请填写图片');
				}
				$database_pc_flash = D('Pc_flash');
				$data_pc_flash['cat_id'] = $this->_post('cat_id');
				$data_pc_flash['token'] = $this->token;
				$data_pc_flash['name'] = $this->_post('name');
				$data_pc_flash['pic'] = $this->_post('pic');
				$data_pc_flash['key'] = $this->_post('key');
				$data_pc_flash['url'] = $this->_post('url');
				if($database_pc_flash->data($data_pc_flash)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！');
				}
			}else{
				$condition_pc_flash_cat['cat_id'] = $this->_get('cat_id');
				$condition_pc_flash_cat['token'] = $this->token;
				$pc_flash_cat = $database_pc_flash_cat->field(true)->where($condition_pc_flash_cat)->find();
				if(empty($pc_flash_cat)){
					$this->error('分类不存在！');
				}
				$this->assign('pc_flash_cat',$pc_flash_cat);

				$this->display('flash_add');
			}
		}elseif($_GET['act'] == 'flash_edit'){	//添加图片
			if(IS_POST){
				if(empty($_POST['id'])){
					$this->error('ID错误！');
				}
				if(empty($_POST['name'])){
					$this->error('请填写图片名称');
				}
				if(empty($_POST['pic'])){
					$this->error('请填写图片');
				}
				$database_pc_flash = D('Pc_flash');
				$condition_pc_flash['id'] = $this->_post('id');
				$condition_pc_flash['token'] = $this->token;
				$data_pc_flash['name'] = $this->_post('name');
				$data_pc_flash['pic'] = $this->_post('pic');
				$data_pc_flash['key'] = $this->_post('key');
				$data_pc_flash['url'] = $this->_post('url');
				if($database_pc_flash->where($condition_pc_flash)->data($data_pc_flash)->save()){
					$this->success('编辑成功！');
				}else{
					$this->error('编辑失败！');
				}
			}else{
				$database_pc_flash = D('Pc_flash');
				$condition_pc_flash['id'] = $this->_get('id');
				$condition_pc_flash['token'] = $this->token;
				$pc_flash = $database_pc_flash->field(true)->where($condition_pc_flash)->find();		
				$this->assign('pc_flash',$pc_flash);
				
				$this->display('flash_edit');
			}
		}elseif($_GET['act'] == 'flash_del'){
			$database_pc_flash = D('Pc_flash');
			$condition_pc_flash['id'] = $this->_get('id');
			$condition_pc_flash['token'] = $this->token;
			if($database_pc_flash->where($condition_pc_flash)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}else{	//分类列表
			$conditon_pc_flash_cat['token'] = $this->token;
			$pc_flash_cat = $database_pc_flash_cat->field(true)->where($conditon_pc_flash_cat)->order('`cat_id` ASC')->select();
			$this->assign('pc_flash_cat',$pc_flash_cat);
			$this->display();
		}
	}
	
	public function choose_tpl(){
		$database_pc_config = D('Pc_config');
		$condition_pc_config['token'] = $this->token;
		$pc_config = $database_pc_config->field(true)->where($condition_pc_config)->find();
		if(empty($pc_config)){
			$this->error('未设置站点信息！');
		}else{
			//$where['token'] = $this->token;
			$where['id'] = $pc_config['tplid'];
			$tplinfo = D('pc_tpl')->where($where)->find();
		}
		$this->assign('tplinfo',$tplinfo);
		$this->assign('pc_config',$pc_config);
		//dump($tplinfo);//exit;
		if(IS_POST){
				if(empty($_POST['id'])){
					$this->error('ID错误！');
				}
				$pc_tpl['tplid'] = $this->_post('id');
				$where['token'] = $this->token;
				if(D('Pc_config')->where($where)->data($pc_tpl)->save()){
					$this->success('切换模版成功！');
				}else{
					$this->error('切换模版失败！');
				}
		}else{
			$database_pc_site = D('Pc_site');
			$condition_pc_site['token'] = $this->token;
			$pc_site = $database_pc_site->field(true)->where($condition_pc_site)->order('`id` ASC')->find();
			$first_pc_site = $pc_site['site'];
			$this->assign('first_pc_site',$first_pc_site);
			
			$where2['token'] = $this->token;
			//$tpllist = D('Pc_tpl')->where($where2)->select();
			$tpllist = D('Pc_tpl')->select();
			$this->assign('tpllist',$tpllist);

			$this->display();
		}
		
	}
	public function location_tpl(){
		header('Location: '.C('web_default_set.static_domain').'/tpl.php?key='.$_GET['key'].'&site_url='.$_GET['site_url']);
	}
	//选择模板后返回处
	public function save_tpl(){
		if(!empty($_GET['key']) && !empty($_GET['tpl_name'])){
			$database_pc_config = D('Pc_config');
			$condition_pc_config['token'] = $this->token;
			$data_pc_config['site_tpl'] = serialize(array('key'=>$_GET['key'],'tpl_name'=>$_GET['tpl_name']));
			//$dedata_pc_config= unserialize($data_pc_config['site_tpl']);
			dump($data_pc_config);
			die;
			if($database_pc_config->where($condition_pc_config)->data($data_pc_config)->save()){
				$database_pc_site = D('Pc_site');
				$condition_pc_site['token'] = $this->token;
				$pc_site = $database_pc_site->field(true)->where($condition_pc_site)->order('`id` ASC')->select();
				//删除每个域名的缓存。
				if(is_array($pc_site)){
					foreach($pc_site as $key=>$value){
						if(!empty($value['site'])){
							S('now_website'.$value['site'],NULL);
						}
					}
				}
				S('web_index_html_'.$this->token,$tpl_arr,NULL);
				
				exit('<html><head><script type="text/javascript">parent.parent.setTpl("'.$_GET['tpl_name'].'");</script></head></html>');
			}else{
				exit('<html><head><script type="text/javascript">alert("模板未保存成功！请检查是否变更过模板再重试。");</script></head></html>');
			}
		}else{
			exit('<html><head><script type="text/javascript">alert("参数非法！");</script></head></html>');
		}
	}
}

?>