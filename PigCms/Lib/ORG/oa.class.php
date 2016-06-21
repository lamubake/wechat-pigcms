<?php
class oa
{
	public $wxuser;
	public $topdomain;
	public function __construct($wxuser)
	{
		$this->wxuser=$wxuser;
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
	}
	public function url(){
		return 'http://oa.maopan.com/index.php?m=home&c=index&a=pigcmsSignin&id=4000371640' . $this->wxuser['id'] . '&domain=pigcms.cn&key=59e60f384e7f91c66f5a1a4c5a5bced2,a3052d0a01c1c83d57142453124f5cd6&createtime=' . $this->wxuser['createtime'];
	}
	function getTopDomain(){
		$host=$_SERVER['HTTP_HOST'];
		$host=strtolower($host);
		if(strpos($host,'/')!==false){
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str='';
		foreach($topleveldomaindb as $v){
			$str.=($str ? '|' : '').$v;
		}
		$matchstr="[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if(preg_match("/".$matchstr."/ies",$host,$matchs)){
			$domain=$matchs['0'];
		}else{
			$domain=$host;
		}
		return $domain;
	}
}
?>

