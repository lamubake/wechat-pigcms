<?php
/**
 * Created by PhpStorm.
 * User: pigcms-s
 * Date: 2015/5/14
 * Time: 15:34
 */
class FunctionLibrary_Person_card{
    public $sub;
    public $token;
    public $serverUrl = "http://www.meihua.com";
    function __construct($token,$sub) {
        $this->sub=$sub;
        $this->token=$token;
    }
    function index(){
        if(!$this->sub){
            return array(
                'name'=>'微名片',
                'subkeywords'=>1,
                'sublinks'=>1,
            );
        }else{
            $uid = $this->getuid();
            $url = $this->serverUrl.'/index.php?m=Api&c=card&a=lists&uid='.$uid.'&all=1';
            $rt = $this->api_notice_increment($url,array(),'get');
            $items 	=  json_decode($rt,true);
            $arr=array(
                'name'=>'微名片',
                'subkeywords'=>array(
                ),
                'sublinks'=>array(
                ),
            );
            if ($items){
                unset($items['count_number']);
                foreach ($items as $v){
                    $arr['sublinks'][$v['id']]=array('name'=>$v['username'],'link'=>'{siteUrl}/index.php?g=Wap&m=Person_card&a=index&token='.$this->token.'&uid='.$v['uid'].'&id='.$v['id']);
                }
            }

            return $arr;
        }
    }
    public function getuid(){
        $where	= array('token'=>$this->token);
        $config = M('wxuser')->where($where)->order('id DESC')->find();
        $data = $this->config($this->token,$config['wxname'],$config['wxid'],$config['headerpic'],'');
        if($data['status']){
            $uid = $data['data'];
        }else{
            echo $data['data'];
            exit();
        }
        return $uid;
    }
    public function config($token,$wxname,$wxid,$wxlogo,$link){
        $data=array(
            'username'=>trim(C('server_topdomain')).'_'.$token,
            'wxname'=>$wxname,
            'domain'=>$_SERVER['HTTP_HOST'],
            'wxid'=>$wxid,
            'wxlogo'=>urlencode($wxlogo),
            'link'=>urlencode($link)
        );
        $url=$this->serverUrl.'/index.php?m=Api&c=public&a=userInfo';
        $rt=$this->api_notice_increment($url,$data);
        return json_decode($rt,1);
    }
    function api_notice_increment($url,$data,$method='POST'){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if(strtoupper($method) == 'POST'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $errorno=curl_errno($ch);
        if ($errorno) {
            return Http::fsockopenDownload($url);
            return array('rt'=>false,'errorno'=>$errorno);
        }else{
            //$js=json_decode($tmpInfo,1);
            //return $js;
            return $tmpInfo;
        }
    }
}