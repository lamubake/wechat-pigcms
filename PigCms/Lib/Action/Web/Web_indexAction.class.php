<?php
class Web_indexAction extends Action{
	public $tpl_path;
	public $now_website;
	public $site_config;
	public $static_url = 'http://webstatic.pigcms.cn';
	public function _initialize(){
		$now_host = $_SERVER['HTTP_HOST'];
		//获取不到主站URL。只能调用缓存文件了
		$version = './Conf/info.php';
		$mainhost = include($version);
		$mainhost = $mainhost['site_url'];
		$this->assign('mainhost',$mainhost);

		//$now_website = S('now_website'.$now_host);
		if(empty($now_website)){
			$database_pc_site = D('Pc_site');
			$condition_pc_site['site'] = $now_host;
			$now_website = $database_pc_site->where($condition_pc_site)->find();
		
			if(!empty($now_website)){
				$database_pc_config = D('Pc_config');
				$condition_pc_config['token'] = $now_website['token'];
				$now_website['config'] = $database_pc_config->field(true)->where($condition_pc_config)->find();
				
				
				if($now_website['config']['site_qq']){
					$tmp_arr = explode(' ',$now_website['config']['site_qq']);
					$now_website['config']['site_qq_array'] = $tmp_arr;
					$now_website['config']['site_qq_main'] = $tmp_arr[0];
				}else{
					$now_website['config']['site_qq_array'] = array();
					$now_website['config']['site_qq_main'] = '';
				}
				
				if($now_website['config']['site_phone']){
					$tmp_arr = explode(' ',$now_website['config']['site_phone']);
					$now_website['config']['site_phone_array'] = $tmp_arr;
					$now_website['config']['site_phone_main'] = $tmp_arr[0];
				}else{
					$now_website['config']['site_phone_array'] = array();
					$now_website['config']['site_phone_main'] = '';
				}
				
				if($now_website['config']['other_info']){
					$other_info = unserialize($now_website['config']['other_info']);
					if(is_array($other_info)){
						foreach($other_info as $key=>$value){
							$now_website['config']['other'][$key] = $value['info'];
						}
					}
				}
				$now_website['config']['site_url'] = 'http://'.$now_host;
				$now_website['config']['main_url'] = $mainhost;

				S('now_website'.$now_host,$now_website);
				
			}else{
				$this->error('非法访问！');
			}
		}
		
		//探测是否在选择模板
		if(!empty($_GET['no_tpl'])){
			unset($_SESSION['tmp_tpl']);
		}
		if(!empty($_GET['tpl']) && $_GET['tpl'] != $_SESSION['tmp_tpl']){
			$_SESSION['tmp_tpl'] = $_GET['tpl'];
		}
		if(!empty($_SESSION['tmp_tpl'])){
			$this->tpl_path = $_SESSION['tmp_tpl'];
			if(empty($now_website['config']['seo_title'])){
				$now_website['config']['seo_title'] = '演示模板';
			}
		}else{
			//if(empty($now_website['config']['site_tpl'])){
			//	$this->error('请先设定模板！');
			//}else{
				$site_tpl = unserialize($now_website['config']['site_tpl']);
				$this->tpl_path = $site_tpl['key'];
				
				if(empty($now_website['config']['seo_title'])){
					$now_website['config']['seo_title'] = $site_tpl['tpl_name'];
				}
			//}
		}
		
		$default_config = C('web_default_set');
		foreach($now_website['config'] as $key=>$value){
			if(empty($value)){
				$now_website['config'][$key] = $default_config[$key];
			}
		}

		/*预留位放置JS统计、客服代码等营销JS。*/
		$now_website['config']['other_content'] = '<div style="display:none;">';
		//统计代码
		if($now_website['config']['site_count']){
			$now_website['config']['other_content'] .= '<script src="http://s5.cnzz.com/stat.php?id='.$now_website['config']['site_count'].'&web_id='.$now_website['config']['site_count'].'" type="text/javascript"></script>';
		}
		$now_website['config']['other_content'] .= '</div>';

		$this->now_website = $now_website;
		$this->site_config = $now_website['config'];
		$this->assign('now_website',$this->now_website);
		$this->assign('site_config',$this->site_config);
		
		
		
		$this->assign($url);
		define('token',$now_website['token']);
		define('tpl_path',$this->tpl_path);
		define('now_host',$now_host);
		
		
	}
	public function index(){
		$this->showTpl();
	}
	public function page(){
		$key = $this->_get('key');
		$condition_pc_page['token'] = token;
		$condition_pc_page['key'] = $key;
		$now_page = D('Pc_page')->field(true)->where($condition_pc_page)->find();
		if(empty($now_page)){
			$this->error('您访问的页面不存在！');
		}
		$now_page['url'] = '/page/'.$key.'.html';
		$this->assign('now_page',$now_page);
		$this->assign('page_key',$key);
		
		$this->site_config['seo_title'] = $now_page['title'].'_'.$this->site_config['site_name'];
		$this->assign('site_config',$this->site_config);

		$this->showTpl();
	}
	public function newscat(){
		$cat_id = $this->_get('cat_id','intval');
		if(!empty($cat_id)){
			$database_pc_news_category = D('Pc_news_category');
			$condition_pc_news_category['cat_id'] = $cat_id;
			$condition_pc_news_category['token'] = token;
			$now_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->find();
			if(empty($now_category)){
				$this->error('您访问的分类不存在！');
			}
			$now_category['url'] = '/newscat/'.$now_category['cat_id'].'.html';
			$this->assign('now_category',$now_category);
			
			$this->site_config['seo_title'] = $now_category['cat_name'].'_'.$this->site_config['site_name'];
			$this->assign('page_key',$now_category['cat_key']);
		}else{
			$this->site_config['seo_title'] = '网站新闻_'.$this->site_config['site_name'];
			$this->assign('page_key','allnews');
		}
		$this->assign('site_config',$this->site_config);
		

		$this->showTpl();
	}
	public function news(){
		//内容
		$id = $this->_get('id','intval');
		$database_pc_news = D('Pc_news');
		$condition_pc_news['token'] = token;
		$condition_pc_news['id'] = $id;
		$now_news = $database_pc_news->field(true)->where($condition_pc_news)->find();
		if(empty($now_news)){
			$this->error('您访问的内容不存在！');
		}
		$database_pc_news->where($condition_pc_news)->setInc('hits');
		$now_news['hits'] += 1;
		$now_news['url'] = '{pigcms:WEB_VISIT_URL}/news/'.$now_news['id'].'.html';
		$this->assign('now_news',$now_news);
		
		//分类
		$database_pc_news_category = D('Pc_news_category');
		$condition_pc_news_category['cat_id'] = $now_news['cat_id'];
		$now_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->find();
		if(empty($now_category)){
			$this->error('您访问的分类不存在！');
		}
		$now_category['url'] = '{pigcms:WEB_VISIT_URL}/newscat/'.$now_category['cat_id'].'.html';
		$this->assign('now_category',$now_category);
		$this->assign('page_key',$now_category['cat_key']);
		
		//上一篇
		$condition_prev_news['id'] = array('lt',$id);
		$condition_prev_news['cat_id'] = $now_news['cat_id'];
		$prev_news = $database_pc_news->field(true)->where($condition_prev_news)->order('`id` DESC')->find();
		if(!empty($prev_news)){
			$prev_news['url'] = '{pigcms:WEB_VISIT_URL}/news/'.$prev_news['id'].'.html';
		}
		$this->assign('prev_news',$prev_news);
		
		//下一篇
		$condition_next_news['id'] = array('gt',$id);
		$condition_next_news['cat_id'] = $now_news['cat_id'];
		$next_news = $database_pc_news->field(true)->where($condition_next_news)->order('`id` ASC')->find();
		if(!empty($next_news)){
			$next_news['url'] = '{pigcms:WEB_VISIT_URL}/news/'.$next_news['id'].'.html';
		}
		$this->assign('next_news',$next_news);
		
		$this->site_config['seo_title'] = $now_news['title'].'_'.$this->site_config['site_name'];
		$this->assign('site_config',$this->site_config);
		
		$this->showTpl();
	}
	
	public function productcat(){
		$cat_id = $this->_get('cat_id','intval');
		if(!empty($cat_id)){
			$database_pc_product_category = D('Pc_product_category');
			$condition_pc_product_category['cat_id'] = $cat_id;
			$condition_pc_product_category['token'] = token;
			$now_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->find();
			if(empty($now_category)){
				$this->error('您访问的分类不存在！');
			}
			$now_category['url'] = '{pigcms:WEB_VISIT_URL}/productcat/'.$now_category['cat_id'].'.html';
			$this->assign('now_category',$now_category);
			
			$this->site_config['seo_title'] = $now_category['cat_name'].'_'.$this->site_config['site_name'];
			$this->assign('page_key',$now_category['cat_key']);
		}else{
			$this->site_config['seo_title'] = '产品中心_'.$this->site_config['site_name'];
			$this->assign('page_key','allproduct');
		}
		$this->assign('site_config',$this->site_config);
		
		$this->showTpl();
	}
	public function product(){
		//内容
		$id = $this->_get('id','intval');
		$database_pc_product = D('Pc_product');
		$condition_pc_product['token'] = token;
		$condition_pc_product['id'] = $id;
		$now_product = $database_pc_product->field(true)->where($condition_pc_product)->find();
		if(empty($now_product)){
			$this->error('您访问的内容不存在！');
		}
		$database_pc_product->where($condition_pc_product)->setInc('hits');
		$now_product['hits'] += 1;
		$now_product['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$now_product['id'].'.html';
		$this->assign('now_product',$now_product);
		
		//分类
		$database_pc_product_category = D('Pc_product_category');
		$condition_pc_product_category['cat_id'] = $now_product['cat_id'];
		$now_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->find();
		if(empty($now_category)){
			$this->error('您访问的分类不存在！');
		}
		$now_category['url'] = '{pigcms:WEB_VISIT_URL}/productcat/'.$now_category['cat_id'].'.html';
		$this->assign('now_category',$now_category);
		$this->assign('page_key',$now_category['cat_key']);
		
		//上一篇
		$condition_prev_product['id'] = array('lt',$id);
		$condition_prev_product['cat_id'] = $now_product['cat_id'];
		$prev_product = $database_pc_product->field(true)->where($condition_prev_product)->order('`id` DESC')->find();
		if(!empty($prev_product)){
			$prev_product['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$prev_product['id'].'.html';
		}
		$this->assign('prev_product',$prev_product);
		
		//下一篇
		$condition_next_product['id'] = array('gt',$id);
		$condition_next_product['cat_id'] = $now_product['cat_id'];
		$next_product = $database_pc_product->field(true)->where($condition_next_product)->order('`id` ASC')->find();
		if(!empty($next_product)){
			$next_news['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$next_product['id'].'.html';
		}
		$this->assign('next_product',$next_product);
		
		$this->site_config['seo_title'] = $now_product['title'].'_'.$this->site_config['site_name'];
		$this->assign('site_config',$this->site_config);
		
		$this->showTpl();
	}
	
	public function downcat(){
		$cat_id = $this->_get('cat_id','intval');
		if(!empty($cat_id)){
			$database_pc_down_category = D('Pc_down_category');
			$condition_pc_down_category['cat_id'] = $cat_id;
			$condition_pc_down_category['token'] = token;
			$now_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->find();
			if(empty($now_category)){
				$this->error('您访问的分类不存在！');
			}
			$now_category['url'] = '{pigcms:WEB_VISIT_URL}/downcat/'.$now_category['cat_id'].'.html';
			$this->assign('now_category',$now_category);
			
			$this->site_config['seo_title'] = $now_category['cat_name'].'_'.$this->site_config['site_name'];
			$this->assign('page_key',$now_category['cat_key']);
		}else{
			$this->site_config['seo_title'] = '下载中心_'.$this->site_config['site_name'];
			$this->assign('page_key','alldown');
		}
		$this->assign('site_config',$this->site_config);

		$this->showTpl();
	}
	public function down(){
		//内容
		$id = $this->_get('id','intval');
		$database_pc_down = D('Pc_down');
		$condition_pc_down['token'] = token;
		$condition_pc_down['id'] = $id;
		$now_down = $database_pc_down->field(true)->where($condition_pc_down)->find();
		if(empty($now_down)){
			$this->error('您访问的内容不存在！');
		}
		$database_pc_down->where($condition_pc_product)->setInc('hits');
		$now_down['hits'] += 1;
		$now_down['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$now_down['id'].'.html';
		$this->assign('now_down',$now_down);
		
		//分类
		$database_pc_down_category = D('Pc_down_category');
		$condition_pc_down_category['cat_id'] = $now_down['cat_id'];
		$now_category = $database_pc_down_category->field(true)->where($condition_pc_down_category)->find();
		if(empty($now_category)){
			$this->error('您访问的分类不存在！');
		}
		$now_category['url'] = '{pigcms:WEB_VISIT_URL}/downcat/'.$now_category['cat_id'].'.html';
		$this->assign('now_category',$now_category);
		$this->assign('page_key',$now_category['cat_key']);
		
		//上一篇
		$condition_prev_down['id'] = array('lt',$id);
		$condition_prev_down['cat_id'] = $now_down['cat_id'];
		$prev_down = $database_pc_down->field(true)->where($condition_prev_down)->order('`id` DESC')->find();
		if(!empty($prev_down)){
			$prev_down['url'] = '{pigcms:WEB_VISIT_URL}/down/'.$prev_down['id'].'.html';
		}
		$this->assign('prev_down',$prev_down);
		
		//下一篇
		$condition_next_down['id'] = array('gt',$id);
		$condition_next_down['cat_id'] = $now_down['cat_id'];
		$next_down = $database_pc_down->field(true)->where($condition_next_down)->order('`id` ASC')->find();
		if(!empty($next_down)){
			$next_news['url'] = '{pigcms:WEB_VISIT_URL}/down/'.$next_down['id'].'.html';
		}
		$this->assign('next_down',$next_down);
		
		$this->site_config['seo_title'] = $now_product['title'].'_'.$this->site_config['site_name'];
		$this->assign('site_config',$this->site_config);
		
		$this->showTpl();
	}
	
	public function books(){
		$this->site_config['seo_title'] = '网站留言_'.$this->site_config['site_name'];
		
		$this->assign('site_config',$this->site_config);
		$this->assign('page_key','books');
		
		$this->showTpl();
	}
	//万能表单提交项
	public function universal_form_modify(){
		$set_id = $_POST['id'];
		if(empty($set_id)){
			$this->error('表单ID必须填写！');
		}
		$condition_universal_form_set['set_id'] = $set_id;
		$condition_universal_form_set['token'] = token;
		$universal_form_set = D('Custom_set')->field(true)->where($condition_universal_form_set)->find();
		if(empty($universal_form_set)){
			$this->error('您提交的表单没有找到！');
		}
	
		$data['token']		= token;
		$data['wecha_id']	= '';
		$data['set_id']		= $set_id;
		$data['add_time']	= time();		
		$data['user_name']	= '电脑网站访客';
		$data['phone']	= '匿名';
		$data['sub_info']	= $this->_serializeSubInfo($_POST,$set_id);
		if(D('Custom_info')->add($data)){
			Sms::sendSms(token, '你的表单“'.$universal_form_set['title'].'”中有新的信息'); //发送商家短信
			$this->success($universal_form_set['succ_info']);
		}else{
			$this->error($universal_form_set['err_info']);
		}
	}
	/*创建序列化提交信息*/
	private function _serializeSubInfo($post,$set_id){
		$field_info =  D('Custom_field')->where(array('token'=>token,'set_id'=>$set_id))->field('field_name,item_name,field_type')->order('`sort` desc')->select();
		$info 		= array();
		foreach($field_info as $key=>$value){
			$info[$key]['name'] 	= $value['field_name'];
			if($value['field_type'] == 'checkbox'){
				$info[$key]['value']	= implode(',', $post[$value['item_name']]);
			}else{
				$info[$key]['value']	= $post[$value['item_name']];
			}
		}
		return serialize($info);
	}
	//删除测试模板时的session
	public function unset_tpl_session(){
		header('Content-type: text/javascript');
		unset($_SESSION['tmp_tpl']);
	}
}