<?php
define('APP_IS_WEB',true);

/*
 * 获取图片列表
 */
function web_flash_list($attr=array()){
	if(isset($attr['key'])){
		$database_pc_flash_cat  = D('Pc_flash_cat');
		$condition_pc_flash_cat['cat_key'] = $attr['key'];
		$condition_pc_flash_cat['token'] = token;
		$pc_flash_cat = $database_pc_flash_cat->field(true)->where($condition_pc_flash_cat)->find();
		if(empty($pc_flash_cat)){
			if($attr['default_img']){
				for($i=0;$i<$attr['default_img'];$i++){
					$flash_list[$i] = array(
										'name' => '演示图片-'.($i+1),
										'pic'  => '{pigcms:WEB_STATIC_URL}images/banner'.($i+1).'.jpg',
									);
				}
			}
			return $flash_list;
		}
		$condition_pc_flash['cat_id'] = $pc_flash_cat['cat_id'];
	}
	if(isset($attr['order'])){
		$order_pc_flash = $attr['order'];
	}else{
		$order_pc_flash = '`id` DESC';
	}
	if(isset($attr['limit'])){
		$limit_pc_flash = intval($attr['limit']);
	}else{
		$limit_pc_flash = '';
	}
	$condition_pc_flash['token'] = token;
	$flash_list = D('Pc_flash')->field(true)->where($condition_pc_flash)->order($order_pc_flash)->limit($limit_pc_flash)->select();

	return $flash_list;
}

/*
 * 获取单个图片
 */
function web_one_image($attr=array()){
	if(!isset($attr['key'])){
		return array();
	}
	$condition_pc_flash['key'] = $attr['key'];
	$condition_pc_flash['token'] = token;
	$one_image = D('Pc_flash')->field(true)->where($condition_pc_flash)->find();
	if(empty($one_image)){
		return array();
	}
	return $one_image;
}

/*
 * 获取导航列表
 */
function web_nav_list($attr=array()){
	$database_pc_nav = D('Pc_nav');
	if(isset($attr['flimit'])){
		$flimit = $attr['flimit'];
	}else{
		$flimit = '';
	}
	if(isset($attr['limit'])){
		$limit = $$attr['limit'];
	}else{
		$limit = '';
	}
	
	if(isset($attr['all'])){
		$condition_pc_nav['token'] = token;
		$condition_pc_nav['fid'] = 0;
		$pc_nav = $database_pc_nav->field(true)->where($condition_pc_nav)->order('`sort` DESC,`id` ASC')->limit($flimit)->select();
		foreach($pc_nav as $key=>$value){
			$condition_pc_nav['fid'] = $value['id'];
			$pc_nav[$key]['nav_list'] = $database_pc_nav->field(true)->where($condition_pc_nav)->order('`sort` DESC,`id` ASC')->limit($limit)->select();
		}
	}else if(isset($attr['key'])){
		$condition_f_pc_nav['key'] = $attr['key'];
		$condition_f_pc_nav['token'] = token;
		$pc_nav = $database_pc_nav->field(true)->where($condition_f_pc_nav)->find();
		if(empty($pc_nav)){
			return array();
		}
		$condition_pc_nav['fid'] = $pc_nav['id'];
		$pc_nav['nav_list'] = $database_pc_nav->field(true)->where($condition_pc_nav)->order('`sort` DESC,`id` ASC')->limit($limit)->select();
	}else{
		$condition_pc_nav['token'] = token;
		$condition_pc_nav['fid'] = 0;
		$pc_nav = $database_pc_nav->field(true)->where($condition_pc_nav)->order('`sort` DESC,`id` ASC')->limit($flimit)->select();
	}
	return $pc_nav;
}
/*
 * 获取产品分类列表
 */
function web_productcat_list($attr=array()){
	if(isset($attr['limit'])){
		$limit = intval($attr['limit']);
	}else{
		$limit = '';
	}
	$condition_pc_product_category['token'] = token;
	$productcat_list = D('Pc_product_category')->field(true)->where($condition_pc_product_category)->order('`cat_sort` DESC,`cat_id` ASC')->limit($limit)->select();
	if($productcat_list){
		foreach($productcat_list as $key=>$value){
			$productcat_list[$key]['url'] = '{pigcms:WEB_VISIT_URL}/productcat/'.$value['cat_id'].'.html';
		}
	}
	if($attr['limit']){
		if(empty($productcat_list)) $productcat_list=array();
		$count = count($productcat_list);
		for($i=$count;$i<$limit;$i++){
			$productcat = array(
							'cat_name'=>'产品分类-'.($i+1),
							'url'=>'javascript:alert(\'演示分类是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个产品，演示分类将会消失！\');',
						);
			array_push($productcat_list,$productcat);
		}
	}
	
	return $productcat_list;
}

/*
 * 获取产品列表
 */
function web_product_list($attr=array()){
	if(isset($attr['limit'])){
		$limit = intval($attr['limit']);
	}else{
		$limit = '';
	}
	
	if(isset($attr['order'])){
		$order = $attr['order'];
	}else{
		$order = '`time` DESC';
	}

	if(isset($attr['key'])){
		$condition_pc_product_category['cat_key'] = $attr['key'];
		$condition_pc_product_category['token'] = token;
		$pc_product_category = D('Pc_product_category')->field(true)->where($condition_pc_product_category)->find();

		if(empty($pc_product_category)){
			return array();
		}
		$pc_product_category['cat_info']['url'] = '{pigcms:WEB_VISIT_URL}/productcat/'.$pc_product_category['cat_id'].'.html';
		$product_list['cat_info'] = $pc_product_category;
		$condition_pc_product['cat_id'] = $pc_product_category['cat_id'];
		$product_list['product_list'] = D('Pc_product')->field(true)->where($condition_pc_product)->order($order)->limit($limit)->select();
		if($product_list['product_list']){
			foreach($product_list['product_list'] as $key=>$value){
				$product_list['product_list'][$key]['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$value['id'].'.html';
			}
		}

		if($attr['limit']){
			$product_list['product_list'] = add_product_rows($product_list['product_list'],$attr['limit']);
		}
		
		return $product_list;
	}else if(isset($attr['cat_id'])){
		$condition_pc_product['cat_id'] = $attr['cat_id'];
		$condition_pc_product['token'] = token;
		$product_list = D('Pc_product')->field(true)->where($condition_pc_product)->order($order)->limit($limit)->select();
		
		$where = "`pc`.`token`='".token."' AND `p`.`cat_id`=`pc`.`cat_id`";
		if(!empty($attr['cat_id'])){
			$cat_id = $attr['cat_id'];
			$where .= " AND `pc`.`cat_id`='$cat_id'";
		}
		if(isset($attr['order'])){
			$order = '`p`.'.$attr['order'];
		}else{
			$order = '`p`.`time` DESC';
		}
		if($attr['has_page']){
			$page_rows = !empty($attr['rows']) ? intval($attr['rows']) : 10;
			$count = D()->Table(array(C('DB_PREFIX').'pc_product_category'=>'pc',C('DB_PREFIX').'pc_product'=>'p'))->where($where)->count();
			$page_total = $count>0 ? ceil($count/$page_rows) : 1;
			$now_page = $_GET['page']>0 ? ($_GET['page']>$page_total ? $page_total : $_GET['page']) : 1;
			$product_list['product_list'] = D()->field(true)->Table(array(C('DB_PREFIX').'pc_product_category'=>'pc',C('DB_PREFIX').'pc_product'=>'p'))->where($where)->order($order)->limit(($now_page-1)*$page_rows,$page_rows)->select();
			$product_list['pagebar'] = get_page('productcat',intval($cat_id),$page_total,$now_page);
		}else{
			$product_list['product_list'] = D()->field(true)->Table(array(C('DB_PREFIX').'pc_product_category'=>'pc',C('DB_PREFIX').'pc_product'=>'p'))->where($where)->order($order)->limit($limit)->select();
		}
		if($product_list['product_list']){
			foreach($product_list['product_list'] as $key=>$value){
				$product_list['product_list'][$key]['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$value['id'].'.html';
				$product_list['product_list'][$key]['cat_url'] = '{pigcms:WEB_VISIT_URL}/productcat/'.$value['cat_id'].'.html';
			}
		}
		if(empty($product_list['product_list']) && $attr['has_page']){
			$limit = isset($attr['default']) ? $attr['default'] : 5 ;
			$product_list['product_list'] = add_product_rows($product_list['product_list'],$limit);
		}
		return $product_list;
	}
	
	$condition_pc_product['token'] = token;
	$product_list = D('Pc_product')->field(true)->where($condition_pc_product)->order($order)->limit($limit)->select();

	if(!empty($product_list)){
		foreach($product_list as $key=>$value){
			$product_list[$key]['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$value['id'].'.html';
		}
		if($attr['limit']){
			$product_list = add_product_rows($product_list,$attr['limit']);
		}
		return $product_list;
	}else if($attr['limit']){
		return add_product_rows(array(),$attr['limit']);
	}else{
		return array();
	}
}

/*
 * 填充产品行数
 */
function add_product_rows($product_list,$limit){
	if(empty($product_list)) $product_list=array();
	$count = count($product_list);
	for($i=$count;$i<$limit;$i++){
		$product = array(
						'title'=>'演示产品-'.($i+1),
						'pic'=>'{pigcms:WEB_STATIC_URL}images/pro'.($i+1).'.jpg',
						'info'=>'演示产品是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个产品，演示产品将会自动消失！',
						'url'=>'javascript:alert(\'演示产品是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个产品，演示产品将会自动消失！\');',
						'time'=> time(),
						'cat_name'=>'演示分类',
						'cat_url'=>'javascript:alert(\'演示分类是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个产品，演示分类将会消失！\');',
					);
		array_push($product_list,$product);
	}

	return $product_list;
}

/*
 * 获取下载分类列表
 */
function web_downcat_list($attr=array()){
	if(isset($attr['limit'])){
		$limit = intval($attr['limit']);
	}else{
		$limit = '';
	}
	$condition_pc_down_category['token'] = token;
	$downcat_list = D('Pc_down_category')->field(true)->where($condition_pc_down_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
	if(empty($downcat_list)){
		return array();
	}
	foreach($downcat_list as $key=>$value){
		$downcat_list[$key]['url'] = '{pigcms:WEB_VISIT_URL}/downcat/'.$value['cat_id'].'.html';
	}
	
	if($attr['limit']){
		if(empty($downcat_list)) $downcat_list=array();
		$count = count($downcat_list);
		for($i=$count;$i<$limit;$i++){
			$downcat = array(
							'cat_name'=>'下载分类-'.($i+1),
							'url'=>'javascript:alert(\'演示分类是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个分类，演示分类将会消失！\');',
						);
			array_push($downcat_list,$downcat);
		}
	}
	
	return $downcat_list;
}

/*
 * 获取下载列表
 */
function web_down_list($attr=array()){
	if(isset($attr['limit'])){
		$limit = intval($attr['limit']);
	}else{
		$limit = '';
	}
	
	if(isset($attr['order'])){
		$order = $attr['order'];
	}else{
		$order = '`time` DESC';
	}

	if(isset($attr['key'])){
		$condition_pc_down_category['cat_key'] = $attr['key'];
		$condition_pc_down_category['token'] = token;
		$pc_down_category = D('Pc_down_category')->field(true)->where($condition_pc_down_category)->find();

		if(empty($pc_down_category)){
			return array();
		}
		$pc_down_category['cat_info']['url'] = '{pigcms:WEB_VISIT_URL}/downcat/'.$pc_down_category['cat_id'].'.html';
		$down_list['cat_info'] = $pc_down_category;
		$condition_pc_down['cat_id'] = $pc_down_category['cat_id'];
		$down_list['down_list'] = D('Pc_down')->field(true)->where($condition_pc_down)->order($order)->limit($limit)->select();
		if($down_list['down_list']){
			foreach($down_list['down_list'] as $key=>$value){
				$down_list['down_list'][$key]['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$value['id'].'.html';
			}
		}

		if($attr['limit']){
			$down_list['down_list'] = add_down_rows($down_list['down_list'],$attr['limit']);
		}
		
		return $down_list;
	}else if(isset($attr['cat_id'])){
		$condition_pc_down['cat_id'] = $attr['cat_id'];
		$condition_pc_down['token'] = token;
		$down_list = D('Pc_down')->field(true)->where($condition_pc_down)->order($order)->limit($limit)->select();
		
		$where = "`dc`.`token`='".token."' AND `d`.`cat_id`=`dc`.`cat_id`";
		if(!empty($attr['cat_id'])){
			$cat_id = $attr['cat_id'];
			$where .= " AND `dc`.`cat_id`='$cat_id'";
		}
		if(isset($attr['order'])){
			$order = '`d`.'.$attr['order'];
		}else{
			$order = '`d`.`time` DESC';
		}
		if($attr['has_page']){
			$page_rows = !empty($attr['rows']) ? intval($attr['rows']) : 10;
			$count = D()->Table(array(C('DB_PREFIX').'pc_down_category'=>'dc',C('DB_PREFIX').'pc_down'=>'d'))->where($where)->count();
			$page_total = $count>0 ? ceil($count/$page_rows) : 1;
			$now_page = $_GET['page']>0 ? ($_GET['page']>$page_total ? $page_total : $_GET['page']) : 1;
			$down_list['down_list'] = D()->field(true)->Table(array(C('DB_PREFIX').'pc_down_category'=>'dc',C('DB_PREFIX').'pc_down'=>'d'))->where($where)->order($order)->limit(($now_page-1)*$page_rows,$page_rows)->select();
			$down_list['pagebar'] = get_page('downcat',intval($cat_id),$page_total,$now_page);
		}else{
			$down_list['down_list'] = D()->field(true)->Table(array(C('DB_PREFIX').'pc_down_category'=>'dc',C('DB_PREFIX').'pc_down'=>'d'))->where($where)->order($order)->limit($limit)->select();
		}
		if($down_list['down_list']){
			foreach($down_list['down_list'] as $key=>$value){
				$down_list['down_list'][$key]['url'] = '{pigcms:WEB_VISIT_URL}/down/'.$value['id'].'.html';
				$down_list['down_list'][$key]['cat_url'] = '{pigcms:WEB_VISIT_URL}/downcat/'.$value['cat_id'].'.html';
			}
		}
		
		if(empty($down_list['down_list']) && $attr['has_page']){
			$limit = isset($attr['default']) ? $attr['default'] : 5 ;
			$down_list['down_list'] = add_down_rows($down_list['down_list'],$limit);
		}
		
		return $down_list;
	}
	
	$condition_pc_product['token'] = token;
	$down_list = D('Pc_down')->field(true)->where($condition_pc_product)->order($order)->limit($limit)->select();

	if(!empty($down_list)){
		foreach($down_list as $key=>$value){
			$down_list[$key]['url'] = '{pigcms:WEB_VISIT_URL}/product/'.$value['id'].'.html';
		}
		if($attr['limit']){
			$down_list = add_down_rows($down_list,$attr['limit']);
		}
		return $down_list;
	}else if($attr['limit']){
		return add_down_rows(array(),$attr['limit']);
	}else{
		return array();
	}
}
/*
 * 填充下载行数
 */
function add_down_rows($down_list,$limit){
	if(empty($down_list)) $down_list=array();
	$count = count($down_list);
	for($i=$count;$i<$limit;$i++){
		$down = array(
						'title'=>'演示下载-'.($i+1),
						'url'=>'javascript:alert(\'演示下载内容是为了填充模板使用的，请您在网站后台加满 '.$limit.'个下载内容，演示下载内容将会自动消失！\');',
						'file'=>'javascript:alert(\'演示下载内容是为了填充模板使用的，请您在网站后台加满 '.$limit.'个下载内容，演示下载内容将会自动消失！\');',
						'time'=> time(),
						'cat_name'=>'下载分类-'.($i+1),
						'cat_url'=>'javascript:alert(\'演示分类是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个分类，演示分类将会消失！\');',
					);
		array_push($down_list,$down);
	}
	return $down_list;
}

/*
 * 获取文章分类列表
 */
function web_newscat_list($attr=array()){
	if(isset($attr['limit'])){
		$limit = intval($attr['limit']);
	}else{
		$limit = '';
	}
	$condition_pc_news_category['token'] = token;
	$newscat_list = D('Pc_news_category')->field(true)->where($condition_pc_news_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
	if(empty($newscat_list)){
		return array();
	}
	foreach($newscat_list as $key=>$value){
		$newscat_list[$key]['url'] = '{pigcms:WEB_VISIT_URL}/newscat/'.$value['cat_id'].'.html';
	}
	
	if($attr['limit']){
		if(empty($newscat_list)) $newscat_list=array();
		$count = count($newscat_list);
		for($i=$count;$i<$limit;$i++){
			$newscat = array(
							'cat_name'=>'文章分类-'.($i+1),
							'url'=>'javascript:alert(\'演示分类是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个分类，演示产品将会消失！\');',
						);
			array_push($newscat_list,$newscat);
		}
	}
	
	return $newscat_list;
}

/*
 * 获取文章列表
 */
function web_news_list($attr=array()){
	if(isset($attr['limit'])){
		$limit = intval($attr['limit']);
	}else{
		$limit = '';
	}
	
	if(isset($attr['order'])){
		$order = $attr['order'];
	}else{
		$order = '`time` DESC';
	}

	if(isset($attr['key'])){
		$condition_pc_news_category['cat_key'] = $attr['key'];
		$condition_pc_news_category['token'] = token;
		$pc_news_category = D('Pc_news_category')->field(true)->where($condition_pc_news_category)->find();

		if(empty($pc_news_category)){
			return array();
		}
		$pc_news_category['cat_info']['url'] = '{pigcms:WEB_VISIT_URL}/newscat/'.$pc_news_category['cat_id'].'.html';
		$news_list['cat_info'] = $pc_news_category;
		$condition_pc_news['cat_id'] = $pc_news_category['cat_id'];
		$news_list['news_list'] = D('Pc_news')->field(true)->where($condition_pc_news)->order($order)->limit($limit)->select();
		if($news_list['news_list']){
			foreach($news_list['news_list'] as $key=>$value){
				$news_list['news_list'][$key]['url'] = '{pigcms:WEB_VISIT_URL}/news/'.$value['id'].'.html';
			}
		}
		if($attr['limit']){
			$news_list['news_list'] = add_news_rows($news_list['news_list'],$attr['limit']);
		}
		return $news_list;
	}else if(isset($attr['cat_id'])){
		$where = "`nc`.`token`='".token."' AND `n`.`cat_id`=`nc`.`cat_id`";
		if(!empty($attr['cat_id'])){
			$cat_id = $attr['cat_id'];
			$where .= " AND `nc`.`cat_id`='$cat_id'";
		}
		if(isset($attr['order'])){
			$order = '`n`.'.$attr['order'];
		}else{
			$order = '`n`.`time` DESC';
		}
		if($attr['has_page']){
			$page_rows = !empty($attr['rows']) ? intval($attr['rows']) : 10;
			$count = D()->Table(array(C('DB_PREFIX').'pc_news_category'=>'nc',C('DB_PREFIX').'pc_news'=>'n'))->where($where)->count();
			$page_total = $count>0 ? ceil($count/$page_rows) : 1;
			$now_page = $_GET['page']>0 ? ($_GET['page']>$page_total ? $page_total : $_GET['page']) : 1;
			$news_list['news_list'] = D()->field(true)->Table(array(C('DB_PREFIX').'pc_news_category'=>'nc',C('DB_PREFIX').'pc_news'=>'n'))->where($where)->order($order)->limit(($now_page-1)*$page_rows,$page_rows)->select();
			$news_list['pagebar'] = get_page('newscat',intval($cat_id),$page_total,$now_page);
		}else{
			$news_list['news_list'] = D()->field(true)->Table(array(C('DB_PREFIX').'pc_news_category'=>'nc',C('DB_PREFIX').'pc_news'=>'n'))->where($where)->order($order)->limit($limit)->select();
		}
		if($news_list['news_list']){
			foreach($news_list['news_list'] as $key=>$value){
				$news_list['news_list'][$key]['url'] = '{pigcms:WEB_VISIT_URL}/news/'.$value['id'].'.html';
				$news_list['news_list'][$key]['cat_url'] = '{pigcms:WEB_VISIT_URL}/newscat/'.$value['cat_id'].'.html';
			}
		}
		if(empty($news_list['news_list']) && $attr['has_page']){
			$limit = isset($attr['default']) ? $attr['default'] : 5 ;
			$news_list['news_list'] = add_news_rows($news_list['news_list'],$limit);
		}
		return $news_list;
	}
	
	$condition_pc_news['token'] = token;
	$news_list = D('Pc_news')->field(true)->where($condition_pc_news)->order($order)->limit($limit)->select();


	if(!empty($news_list)){
		foreach($news_list as $key=>$value){
			$news_list[$key]['url'] = '{pigcms:WEB_VISIT_URL}/news/'.$value['id'].'.html';
		}
		if($attr['limit']){
			$news_list = add_news_rows($news_list,$attr['limit']);
		}
		return $news_list;
	}elseif($attr['limit']){
		$news_list = add_news_rows($news_list,$attr['limit']);
		return $news_list;
	}else{
		return array();
	}
}

/*
 * 填充文章行数
 */
function add_news_rows($news_list,$limit){
	if(empty($news_list)) $news_list=array();
	$count = count($news_list);
	for($i=$count;$i<$limit;$i++){
		$news = array(
						'title'=>'演示文章-'.($i+1),
						'pic'=>'{pigcms:WEB_STATIC_URL}images/news_default.jpg',
						'info'=>'演示文章是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个文章，演示文章将会自动消失！',
						'url'=>'javascript:alert(\'演示文章是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个文章，演示文章将会自动消失！\');',
						'time'=> time(),
						'cat_name'=>'演示分类',
						'cat_url'=>'javascript:alert(\'演示分类是为了填充模板内容使用的，请您在网站后台加满 '.$limit.'个文章，演示产品将会消失！\');',
					);
		array_push($news_list,$news);
	}
	return $news_list;
}

/*
 * 分页
 */
function get_page($type,$cat_id,$total,$now){
	$url = '{pigcms:WEB_VISIT_URL}/'.$type.'/'.$cat_id.'-';
	$str = '<div class="list">';
	if ($now >= 2){
		$str .= '<a href="'.$url.($now-1).'.html" class="prev pager-item" title="上一页"><span>上一页</span></a>';
	}else{
		$str .= '<span class="pager-nolink"><span>上一页</span></span>';
	}
	for($i=1;$i<=5;$i++){
		if($now <= 1){
			$page = $i;
		}elseif($now > $total-1){
			$page = $total-5+$i;
		}else{
			$page = $now-3+$i;
		}
		if($page != $now  && $page>0){
			if($page<=$total){
				$str .= '<a href="'.$url.$page.'.html" title="第'.$page.'页" class="pager-item"><span>'.$page.'</span></a>';
			}else{
				break;
			}
		}else{
			if($page == $now) $str.='<span class="pager-current"><span>'.$page.'</span></span>';
		}
	}
	if ($now != $total){
		$str .= '<a href="'.$url.($now+1).'.html" class="next pager-item"><span>下一页</span></a>';
	}else{
		$str .= '<span class="pager-item"><span>下一页</span></span>';
	}
	$str .= '</div>';
	return $str;
}

function pagelink($key){
	echo '{pigcms:WEB_VISIT_URL}/page/'.$key.'.html';
} 

/*
 * 获取单个自定义页面
 */
function web_one_page($attr=array()){
	if(!isset($attr['key'])){
		return array();
	}
	$condition_pc_page['key'] = $attr['key'];
	$condition_pc_page['token'] = token;
	$one_page = D('Pc_page')->field(true)->where($condition_pc_page)->find();
	if(empty($one_page)){
		return array();
	}
	$one_page['url'] = '{pigcms:WEB_VISIT_URL}/page/'.$one_page['key'].'.html';
	return $one_page;
}

/*
 * 对接后台的万能表单项
 */
function web_universal_form($attr=array()){
	if(!isset($attr['key'])){
		return '';
	}
	$condition_universal_form_set['keyword'] = $attr['key'];
	$condition_universal_form_set['token'] = token;
	$universal_form_set = D('Custom_set')->field(true)->where($condition_universal_form_set)->find();
	
	if(empty($universal_form_set)){
		return '';
	}
	
	$condition_universal_custom_field['token'] = token;
	$condition_universal_custom_field['set_id'] = $universal_form_set['set_id'];
	$condition_universal_custom_field['is_show'] = '1';
	$forms 	= D('Custom_field')->where($condition_universal_custom_field)->order('`sort` desc')->select();
	$str	= '<form action="'.U('Web_index/universal_form_modify').'" method="post" onSubmit="return universal_form_Submit()">';
	$str   .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="'.$attr['table_class'].'">';
	$arr 	= array();
	foreach($forms as $key=>$value){
		$str	.= '<tr><th>';
		$str	.= $value['field_name'].'：';
		$str 	.= '</th><td>';
		$str	.= _formGetInput($value);
		$str	.= '</td></tr>';

		$arr[] 	 = array('id'=>$value['item_name'],'name'=>$value['field_name'],'type'=>$value['field_type'],'match'=>$value['field_match'],'is_empty'=>$value['is_empty'],'err_info'=>$value['err_info']);  //js验证信息
	}
	$str .= '<tr><th>&nbsp;</th><td><input type="submit" class="'.$attr['btn_class'].'" value="提交" id="button"/></td></tr>';
	$str	.= '</table>';
	$str	.= '<input type="hidden" name="id" value="'.$universal_form_set['set_id'].'"/>';
	$str	.= '</form>';
	$str	.= '<script type="text/javascript">';
	$str	.= 'function universal_form_Submit(){';
	foreach($arr as $key=>$value){
		
		if($value['type'] == 'checkbox'){
 			if($value['is_empty'] == 1){
				$str .= 'if($("#'.$value['id'].':checked").length == 0){alert("'.$value['name'].'不能为空");return false;}';
			}		
 		}elseif($value['type'] == 'radio'){
		
 		}elseif($value['type'] == 'textarea'){
 			if($value['is_empty'] == 1){
				$str .= 'if($.trim($("#'.$value['id'].'").val()) == ""){alert("'.$value['name'].'不能为空");return false;}';
			}
 		}else{
			$str .= 'var values = $("#'.$value['id'].'").val();';
 			if($value['is_empty'] == 1){
				$str .= 'if($.trim(values) == ""){alert("'.$value['name'].'不能为空");return false;}'."\r\n";
			}
			if($value['match'] !== ''){
				if($value['err_info']){
					$str .= 'var regu = /'.$value['match'].'/;var re = new RegExp(regu);if(!re.test($.trim(values))){alert("'.$value['err_info'].'");return false;}';
				}else{
					$str .= 'var regu = /'.$value['match'].'/;var re = new RegExp(regu);if(!re.test($.trim(values))){alert("'.$value['name'].'输入错误");return false;}';
				}
			}
 		}
	}
	$str    .= '}';
	$str    .= '</script>';
	return array('form'=>$str,'verify'=>$arr);
}

function _formGetInput($value){
	$input 	= '';
	switch($value['field_type']){
		case 'text':
			$input 	.= '<input type="text" class="input" id="'.$value['item_name'].'" name="'.$value['item_name'].'" value=""/>';
			break;
		case 'textarea':
			$input 	.= '<textarea class="textarea" name="'.$value['item_name'].'" id="'.$value['item_name'].'" rows="4" cols="25"  ></textarea>';
			break;
		case 'checkbox':
			$option = explode('|', $value['filed_option']);
			for($i=0;$i<count($option);$i++){
				$input 	.= '<input type="checkbox" name="'.$value['item_name'].'[]" id="'.$value['item_name'].'" value="'.$option[$i].'"  />'.$option[$i];
			}
			break;
		case 'radio':
			$option = explode('|', $value['filed_option']);
			for($i=0;$i<count($option);$i++){
				$checked = $i==0?'checked=true':'';
				$input 	.= '<input type="radio" name="'.$value['item_name'].'" id="'.$value['item_name'].'" value="'.$option[$i].'" '.$checked.' />'.$option[$i];
			}
			break;
		case 'select':
			$input 	.= '<select name="'.$value['item_name'].'" id="'.$value['item_name'].'"><option value="">请选择..</option>';
			$op_arr	= explode('|',$value['filed_option']);
			$num	= count($op_arr);
			if($num > 0){
				for($i=0;$i<$num;$i++){
					$input 	.= '<option value="'.$op_arr[$i].'">'.$op_arr[$i].'</option>';
				}
			}
			$input  .='</select>';
			break;
		case 'date':
			$input 	.= '<input type="text" class="input" name="'.$value['item_name'].'" id="'.$value['item_name'].'" value="'.date('Y-m-d',time()).'" onClick="WdatePicker()"/>';
	}
	if($value['is_empty']){
		$input .= '&nbsp;<span class="red">*（必填）</span>';
	}

	return $input;
}

/*
 * 中文字符串截取
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $ret = iconv_substr($str,$start,$length,$charset);
        //for iconv_substr:
        //If str is shorter than offset characters long, FALSE will be returned.
        if (empty($ret)){    
            $ret = '';
        }
        return $ret;
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}
?>