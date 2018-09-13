<?php
include 'ApiConfig.php';
require_once 'phpqrcode/phpqrcode.php';
/**
* 
*/
class weixincode
{
	//统一入口
	public function StartExecute($paramdata){

		//实例化配置类
		$config = new ApiConfig($paramdata['acpcode']);
		if(!$config->appId || !$config->secret){
			$return = array('resultCode'=>'9976', 'errMsg'=>'接入商异常', 'payMessage'=>'');
			return json_encode($return, JSON_UNESCAPED_UNICODE);
		}
		//识别支付方式--返回支付代码及url
        $param = $config->GetPayCode($paramdata);//支付方式

		//转换为curl所需参数，并计算sign
		$string = $config->signString($param);
		//echo '<pre>';
		//var_dump($string);exit;
        $data = $this->data_to_xml($string);
        $data = $this->postXmlCurl($data,$config->gatewayUrl);
        $data = $this->xml_to_data($data);

        $return = array('resultCode'=>'0000', 'qr_code'=>$data["code_url"]);
        return json_encode($return, JSON_UNESCAPED_UNICODE);


       // echo '<pre>';
       // var_dump($data);exit;
		//执行curl
		//$data = $config->curl($string);
        //echo $data;exit;
		//执行后续处理
		/*$arrdata = json_decode($data,true);

		//准备返回数据
		if(!is_array($arrdata)){
			$return = array('resultCode'=>'9288', 'errMsg'=>'交易渠道请求失败', 'payMessage'=>'');
		}else{
			$return = $config->requestdata($arrdata,$paramdata['gateway']);
		}
		return json_encode($return, JSON_UNESCAPED_UNICODE);*/
	}


    private function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            //curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            //curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
    /**
     * 输出xml字符
     * @param   $params     参数名称
     * return   string      返回组装的xml
     **/
    public function data_to_xml($params){

        if(!is_array($params)|| count($params) <= 0)
        {
            return false;
        }
        $xml ='';
        $xml .= "<xml>";
        foreach ($params as $key=>$val)
        {
            //var_dump($val);
            if (is_numeric($val)){

                $xml.="<".$key.">".$val."</".$key.">";

            }else{

                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }

        $xml.="</xml>";

        return $xml;
    }
    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    public function xml_to_data($xml){
        if(!$xml){
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }
}