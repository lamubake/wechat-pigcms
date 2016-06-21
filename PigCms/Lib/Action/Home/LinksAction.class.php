<?php
class LinksAction extends BaseAction{
	public $token;
	public $link;
	private $homeAuth;
	public function _initialize() {
		parent::_initialize();
		$this->token = $this->_post('token','trim,htmlspecialchars');
		$return['status'] = 0;
		if(!$this->token){
			$return['msg'] = 'Token不存在';
			exit(json_encode($return));
		}
		$user = M('Wxuser')->field('id,uid')->where(array('token'=>$this->token))->find();
		if(!$user){
			$return['msg'] = '请使用有效的Token';
			exit(json_encode($return));
		}
		$this->user = $user;
		session('uid',$user['uid']);
		session('token',$this->token);
		$this->link = A('User/Link');
	}
	public function index(){
		$modules = $this->link->modules();
		if(isset($_GET['auth'])){
			$this->homeAuth = A('Home/Auth');
			$allowModules = isset($_POST['modules']) ? $_POST['modules'] : $this->homeAuth->_accessListAction;
			foreach($modules as $key=>$val){
				if(!in_array(strtolower($val['module']),$allowModules)){
					unset($modules[$key]);
				}
			}
		}
		$return['data'] = $this->urlformat($this->link->_getModules($modules));
		$return['status'] = 1;
		$_SESSION = array();
		echo json_encode($return);exit;
	}
	public function detailed(){
		$module = $this->_post('module','trim,htmlspecialchars');
		if($_POST['method']) $_GET['module'] = $this->_post('method','trim,htmlspecialchars');
		if($_POST['p']) $_GET['p'] = $this->_post('p','trim,intval');
		if($_POST['pid']) $_GET['pid'] = $this->_post('pid','trim,intval');
		$this->link->$module();
		$_SESSION = array();
	}
	public function urlformat($data){
		foreach($data as $key=>$val){
			foreach($val as $ke=>$va){
				if($va['linkcode'] && $va['linkcode'] != ''){
					$data[$key][$ke]['linkcode'] = str_replace(array('{siteUrl}','&wecha_id={wechat_id}'),array($this->siteUrl,""),$va['linkcode']);
				}
				if($va['linkurl'] && $va['linkurl'] != ''){
					$url = $this->parseUrl($va['linkurl']);
					if($url){
						$data[$key][$ke]['linkurl'] = $url;
					}
				}
			}
		}
		return $data;
	}
	public function parseUrl($url){
		$data = parse_url($url);
		$return = array();
		if(isset($data['host'])){
			$return['host'] = $data['scheme'].'://'.$data['host'];
		}
		if(isset($data['query'])){
			foreach(explode('&',$data['query']) as $key=>$val){
				$data = explode('=',$val);
				$return[$data[0]] = $data[1];
			}
		}
		return $return;
	}
}

?>