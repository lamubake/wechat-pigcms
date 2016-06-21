<?php 
/**
* 发红包的公共类
* 提供一些公共方法
**/
class Hongbao_common{
	
	public function __construct(){}
	
	//生成随机字符串,长度最长不超过32位
	public function createNoncestr( $length = 32 ){
		$varchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$str = "";
		for($i=0;$i < $length;$i++){
			$str .= $varchars{(mt_rand(0,strlen($varchars)-1))};
		}
		return $str;
	}
	//xml转换为数组
	public function xmlToarray($xml){
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}
	//数组转换为xml
	function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_int($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
	//过滤字符
	public function trimString($value){
		$ret = null;
		if (null != $value) {
			$ret = $value;
			if (strlen($ret) == 0) {
				$ret = null;
			}
		}
		return $ret;
	}
}