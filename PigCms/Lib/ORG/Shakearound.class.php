<?php 
/**
* 摇一摇周边类
**/
class Shakearound {
	public $myToken;
	public $apiurl;
	function __construct(){
		$this->myToken = session('token') ? session('token') : session('wap_token');
		$this->apiurl = 'https://api.weixin.qq.com/shakearound/';
	}
	/**
	 * 添加设备
	 * 
	 * @access public
	 * @param mixed $quantity 设备ID的数量
	 * @param mixed $apply_reason 申请理由 不超过100字
	 * @param mixed $comment 设备名称 
	 * @param mixed $poi_id 设备关联的门店ID 可以不填
	 */
	public function apply_device($quantity = 1 , $apply_reason = '测试' ,$comment = '' , $poi_id = ''){
		if(empty($apply_reason) || strlen($apply_reason) > 200){
			return $this->print_error('申请理由不能为空,且不超过200个英文字母');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$params = array();
		$params['quantity'] = (int)$quantity;
		$params['apply_reason'] = (string)$apply_reason;
		//设备的备注信息
		if(empty($comment)){
			return $this->print_error('设备名称不能为空');
		}elseif(strlen($comment) > 30){
			return $this->print_error('设备名称不超过30个英文字母');
		}
		$params['comment'] = (string)$comment;
		if($poi_id) $params['poi_id'] = (int)$poi_id;
		$requestUrl = $this->apiurl.'device/applyid?access_token='.$access_token;
		$sendData = json_encode($params);
		$respon_json = $this->postCurl($requestUrl,$sendData);
		return $respon_json;
	}

	/**
	 * 编辑设备信息
	 * 
	 * @access public
	 * @param mixed $device_id 设备编号 
	 * @param mixed $UUID 
	 * @param mixed $major 
	 * @param mixed $minor 设备编号和(UUID、major、major)组合任选其一,二者都填以设备编号优先
	 * @param mixed $comment 设备的名称 不超过15个字或30个字母
	 */
	public function update_device($device_id = '',$UUID = '',$major = '',$minor = '',$comment = ''){
		//没填写设备ID
		if(empty($device_id)){
			if(empty($UUID) || empty($major) || empty($minor)){
				return $this->print_error('UUID、major、minor三个信息需填写完整');
			}
		}
		//UUID、major、minor三个信息不完整
		if(empty($UUID) || empty($major) || empty($minor)){
			if(empty($device_id)){
				return $this->print_error('设备编号不能为空');
			}
		} 
		//设备的备注信息
		if(empty($comment)){
			return $this->print_error('设备名称不能为空');
		}elseif(strlen($comment) > 30){
			return $this->print_error('设备名称不超过30个英文字母');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$params = array();
		$device_identifier = array();
		$device_identifier['device_id'] = (int)$device_id;
		$device_identifier['uuid'] = $UUID;
		$device_identifier['major'] = $major;
		$device_identifier['minor'] = $minor;
		$params['device_identifier'] = $device_identifier;
		$params['comment'] = (string)$comment;
		$requestUrl = $this->apiurl.'device/update?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 配置设备与门店关联
	 * 
	 * @access public
	 * @param mixed $device_id 设备编号 
	 * @param mixed $UUID 
	 * @param mixed $major 
	 * @param mixed $minor 设备编号和(UUID、major、major)组合任选其一,二者都填以设备编号优先
	 * @param mixed $poi_id 待关联的门店ID
	 */
	public function bindlocation($device_id = '',$UUID = '',$major = '',$minor = '',$poi_id  = ''){
		//没填写设备ID
		if(empty($device_id)){
			if(empty($UUID) || empty($major) || empty($minor)){
				return $this->print_error('UUID、major、minor三个信息需填写完整');
			}
		}
		//UUID、major、minor三个信息不完整
		if(empty($UUID) || empty($major) || empty($minor)){
			if(empty($device_id)){
				return $this->print_error('设备编号不能为空');
			}
		} 
		//设备的备注信息
		if(empty($poi_id )){
			return $this->print_error('待关联的门店ID不能为空');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$params = array();
		$device_identifier = array();
		$device_identifier['device_id'] = (int)$device_id;
		$device_identifier['uuid'] = $UUID;
		$device_identifier['major'] = $major;
		$device_identifier['minor'] = $minor;
		$params['device_identifier'] = $device_identifier;
		$params['poi_id'] = (string)$poi_id;
		$requestUrl = $this->apiurl.'device/bindlocation?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 查询设备列表
	 * 
	 * @access public
	 * @param mixed $device_id 设备编号 
	 * @param mixed $UUID 
	 * @param mixed $major 
	 * @param mixed $minor 设备编号和(UUID、major、major)组合任选其一,二者都填以设备编号优先
	 * @param mixed $apply_id 批次ID，申请设备ID超出500个时所返回批次ID
	 * @param mixed $begin 设备列表的起始索引值 
	 * @param mixed $count 待查询的设备个数
	 */
	public function search_device($device_id = '',$UUID = '',$major = '',$minor = '',$apply_id  = '',$begin = '',$count = ''){
		$params = array();
		//批量查询
		if((int)$begin >= 0 && !empty($count)){
			if(!empty($apply_id)){  //批量查询时指定批次ID
				$params['apply_id'] = (int)$apply_id;
			}
			$params['begin'] = (int)$begin;
			$params['count'] = (int)$count;
		}else{
			//没填写设备ID
			if(empty($device_id)){
				if(empty($UUID) || empty($major) || empty($minor)){
					return $this->print_error('UUID、major、minor三个信息需填写完整');
				}
			}
			//UUID、major、minor三个信息不完整
			if(empty($UUID) || empty($major) || empty($minor)){
				if(empty($device_id)){
					return $this->print_error('设备编号不能为空');
				}
			} 
			$device_identifier = array();
			$device_identifier['device_id'] = (int)$device_id;
			$device_identifier['uuid'] = $UUID;
			$device_identifier['major'] = $major;
			$device_identifier['minor'] = $minor;
			$params['device_identifier'] = $device_identifier;
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$requestUrl = $this->apiurl.'device/search?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 新增页面
	 * 
	 * @access public
	 * @param mixed $title 页面标题 
	 * @param mixed $description 页面副标题
	 * @param mixed $icon_url 页面跳转地址
	 * @param mixed $icon_url 页面图片链接
	 * @param mixed $comment 页面备注信息
	 */
	public function add_page($title = '',$description = '',$page_url = '',$icon_url = '',$comment = ''){
		if(empty($title) || strlen($title) > 12){
			return $this->print_error('标题不能为空,并且不超过12个英文字母');
		}
		if(empty($description) || strlen($description) > 14){
			return $this->print_error('副标题不能为空,并且不超过14个英文字母');
		}
		if(empty($page_url)){
			return $this->print_error('页面跳转地址不能为空');
		}
		if(empty($icon_url)){
			return $this->print_error('图片链接不能为空');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$params = array();
		$params['title'] = (string)trim($title);
		$params['description'] = (string)trim($description);
		$params['page_url'] = html_entity_decode($page_url);
		$params['icon_url'] = html_entity_decode($icon_url);
		if(!empty($comment)){
			if(strlen($comment) > 30){
				return $this->print_error('备注信息不超过30个英文字母');
			}
			$params['comment'] = trim($comment);
		}
		$requestUrl = $this->apiurl.'page/add?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 编辑页面信息
	 * 
	 * @access public
	 * @param mixed $page_id 要编辑的页面ID 
	 * @param mixed $title 页面标题 
	 * @param mixed $description 页面副标题
	 * @param mixed $icon_url 页面跳转地址
	 * @param mixed $icon_url 页面图片链接
	 * @param mixed $comment 页面备注信息
	 */
	public function update_page($page_id = '',$title = '',$description = '',$page_url = '',$icon_url = '',$comment = ''){
		if(empty($page_id)){
			return $this->print_error('页面ID不能为空');
		}
		if(empty($title) || strlen($title) > 12){
			return $this->print_error('标题不能为空,并且不超过12个英文字母');
		}
		if(empty($description) || strlen($description) > 14){
			return $this->print_error('副标题不能为空,并且不超过14个英文字母');
		}
		if(empty($page_url)){
			return $this->print_error('页面跳转地址不能为空');
		}
		if(empty($icon_url)){
			return $this->print_error('图片链接不能为空');
		}
		$params = array();
		$params['page_id'] = (int)$page_id;
		$params['title'] = (string)trim($title);
		$params['description'] = (string)trim($description);
		$params['page_url'] = html_entity_decode($page_url);
		$params['icon_url'] = html_entity_decode($icon_url);
		if(!empty($comment)){
			if(strlen($comment) > 30){
				return $this->print_error('备注信息不超过30个英文字母');
			}
			$params['comment'] = trim($comment);
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$requestUrl = $this->apiurl.'page/update?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 查询页面列表
	 * 
	 * @access public
	 * @param mixed $page_ids 要查询的页面ID列表 可以是数组或者字符串
	 * @param mixed $begin 设备列表的起始索引值 
	 * @param mixed $count 待查询的设备个数 列表查询和指定ID查询两者选其一
	 */
	public function search_page($page_ids = '',$begin = '',$count = ''){
		$params = array();
		//批量查询
		if((int)$begin >= 0 && !empty($count)){
			$params['begin'] = (int)$begin;
			$params['count'] =	(int)$count;
		}else{
			if(!empty($page_ids)){
				if(is_array($page_ids)){
					$params['page_ids'] = $page_ids;
				}else{
					$ids = explode(",",$page_ids);
					$params['page_ids'] = $ids;
				}
			}else{
				return $this->print_error('查询的页面ID不能为空');
			}
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$requestUrl = $this->apiurl.'page/search?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 删除页面
	 * 
	 * @access public
	 * @param mixed $page_ids 要删除的页面ID列表 可以是数组或者字符串
	 */
	public function delete_page($page_ids = ''){
		if(empty($page_ids)){
			$this->print_error('删除的页面ID列表不能为空');
		}
		$params = array();
		if(is_array($page_ids)){
			foreach($page_ids as $k=>$id){
				$page_ids[$k] = (int)$id;
			}
			$params['page_ids'] = $page_ids;
		}else{
			$params['page_ids'] = array((int)$page_ids);
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$requestUrl = $this->apiurl.'page/delete?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	//素材管理
	public function add_material($img_url = ''){
		if(empty($img_url)){
			$this->print_error('上传的图片地址不能为空');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$imgStr = file_get_contents($img_url);
		$imgName = substr(md5($img_url.rand(1000,2000)),0,5);
		file_put_contents('./uploads/'.'img_'.$imgName.'.jpg',$imgStr);
		$media = '';
		$media = './uploads/'.'img_'.$imgName.'.jpg';
		$media = $_SERVER['DOCUMENT_ROOT'].str_replace(array('./'),array('/'),$media);
		$requestUrl = "https://api.weixin.qq.com/shakearound/material/add?access_token=".$access_token;
		$respon_json = $this->postCurl($requestUrl,array('media'=>'@'.$media));
		return $respon_json;
	}
	
	/**
	 * 配置设备与页面关联
	 * 
	 * @access public
	 * @param mixed $page_ids 	被关联的页面ID 若设备配置多个页面，则随机出现页面信息。  
	 * @param mixed $device_id 	设备编号 
	 * @param mixed $UUID 
	 * @param mixed $major 
	 * @param mixed $minor 	设备编号和(UUID、major、major)组合任选其一,二者都填以设备编号优先
	 * @param mixed $bind 	关联操作标志位， 0为解除关联关系，1为建立关联关系
	 * @param mixed $append 新增操作标志位， 0为覆盖，1为新增 
	 */
	public function bindpage($page_ids = '',$device_id  = '',$UUID = '',$major = '',$minor = '',$bind ='',$append = 1){
		if(empty($page_ids)){
			$this->print_error('需要被绑定的页面不能为空');
		}
		//没填写设备ID
		if(empty($device_id)){
			if(empty($UUID) || empty($major) || empty($minor)){
				return $this->print_error('UUID、major、minor三个信息需填写完整');
			}
		}
		//UUID、major、minor三个信息不完整
		if(empty($UUID) || empty($major) || empty($minor)){
			if(empty($device_id)){
				return $this->print_error('设备编号不能为空');
			}
		} 
		if(!in_array($bind,array(0,1))){
			return $this->print_error('关联关系指定错误,0表示解除关联，1表示建立关联');
		}
		if(!in_array($append,array(0,1))){
			return $this->print_error('新增操作方式指定错误,0表示覆盖，1表示新增');
		}
		$params = array();
		$device_identifier = array();
		$device_identifier['device_id'] = (int)$device_id;
		$device_identifier['uuid'] = $UUID;
		$device_identifier['major'] = $major;
		$device_identifier['minor'] = $minor;
		$params['device_identifier'] = $device_identifier;
		if(is_array($page_ids)){
			foreach($page_ids as $k=>$id){
				$page_ids[$k] = (int)$id;
			}
			$params['page_ids'] = $page_ids;
		}else{
			$params['page_ids'] = (int)$page_ids;
		}
		$params['bind'] = $bind;
		$params['append'] = $append;
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$requestUrl = $this->apiurl.'device/bindpage?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 获取摇周边的设备信息及用户信息
	 * 
	 * @access public
	 * @param mixed $ticket 摇周边业务的ticket,可在摇到的URL中得到,ticket生效时间为30分钟
	 */
	public function getshakeinfo($ticket = ''){
		if(empty($ticket)){
			return $this->print_error('ticket不能为空');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$params = array();
		$params['ticket'] = $ticket;
		$requestUrl = $this->apiurl.'user/getshakeinfo?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 以设备为维度的数据统计
	 * 
	 * @access public
	 * @param mixed $device_id 设备编号 
	 * @param mixed $UUID 
	 * @param mixed $major 
	 * @param mixed $minor 设备编号和(UUID、major、major)组合任选其一,二者都填以设备编号优先
	 * @param mixed $begin_date 统计起始时间
	 * @param mixed $end_date 统计结束时间 最长时间跨度为30天
	 */
	public function statistics_device($device_id = '',$UUID = '',$major = '',$minor = '',$begin_date  = '',$end_date =''){
		//没填写设备ID
		if(empty($device_id)){
			if(empty($UUID) || empty($major) || empty($minor)){
				return $this->print_error('UUID、major、minor三个信息需填写完整');
			}
		}
		//UUID、major、minor三个信息不完整
		if(empty($UUID) || empty($major) || empty($minor)){
			if(empty($device_id)){
				return $this->print_error('设备编号不能为空');
			}
		} 
		if(($end_date - $begin_date) > 30*24*3600){
			return $this->print_error('最长时间跨度为30天');
		}
		$params = array();
		$device_identifier = array();
		$device_identifier['device_id'] = (int)$device_id;
		$device_identifier['uuid'] = $UUID;
		$device_identifier['major'] = $major;
		$device_identifier['minor'] = $minor;
		$params['device_identifier'] = $device_identifier;
		$params['begin_date'] = $begin_date;
		$params['end_date'] = $end_date;
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$requestUrl = $this->apiurl.'statistics/device?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	
	/**
	 * 以页面为维度的数据统计
	 * 
	 * @access public
	 * @param mixed $page_id 页面ID 
	 * @param mixed $begin_date 统计起始时间
	 * @param mixed $end_date 统计结束时间 最长时间跨度为30天
	 */
	public function statistics_page($page_id = '',$begin_date  = '',$end_date =''){
		if(!$page_id){
			return $this->print_error('页面ID不能为空');
		}
		if(($end_date - $begin_date) > 30*24*3600){
			return $this->print_error('最长时间跨度为30天');
		}
		$access_token 	= $this->get_access_token();
		if(!$access_token){
			return $this->print_error('access_token获取失败');
		}
		$params = array();
		$params['page_id'] = (int)$page_id;
		$params['begin_date'] = $begin_date;
		$params['end_date'] = $end_date;
		$requestUrl = $this->apiurl.'statistics/page?access_token='.$access_token;
		$respon_json = $this->postCurl($requestUrl,json_encode($params));
		return $respon_json;
	}
	//发送请求
	function postCurl($url, $data){
		$ch = curl_init();
		//$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,40);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$exec = curl_exec($ch);
		if($exec){
			curl_close($ch);
			return $exec;
		}else{
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			return json_encode(array('errcode'=>$errno,'errmsg'=>$error));
		}
	}
	
	//获取access_token,默认有缓存
	private function get_access_token(){
		if(empty($this->myToken)){
			return $this->print_error('token获取失败');
		}
		$wxUser = M('Wxuser')->where(array('token'=>$this->myToken))->field('appid,appsecret')->find();
		if(empty($wxUser['appid'])){
			return $this->print_error('appid获取失败');
		}
		$apiOauth 	= new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($wxUser['appid']);
		if($access_token){
			//S('access_token',$access_token,5*3600);
			return $access_token;
		}else{
			return false;
		}
	}
	//错误提示
	private function print_error($errmsg = ''){
		$error_msg = array();
		$error_msg['errcode'] = rand(1000,2000);
		$error_msg['errmsg'] = !empty($errmsg) ? (string)$errmsg : '请求失败';
		return json_encode($error_msg);
	}
}
?>