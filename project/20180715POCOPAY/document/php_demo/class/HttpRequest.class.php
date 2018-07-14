<?php
/**
 * 请求接口类
 */
class HttpRequest {
    private $_request_data;     //请求报文

    private $_gateway_url;      //请求网关地址

    private $_reponse_content;  //返回数据

    private $_reponse_code;     //返回状态码

    private $_timeout = 15;     //请求超时时间 单位为秒


    /**
     * 设置请求报文
     */
    public function setRequestData($data) {
        $this->_request_data = $data;
    }

    /**
     * 设置网关
     */
    public function setGatewayUrl($gateway_url) {
        $this->_gateway_url = $gateway_url;
    }


    /**
     * 设置请求超时时间
     */
    public function setTimeout($timeout) {
        $this->_timeout = $timeout;
    }

    /**
     * 获取请求返回报文
     */
    public function getReponseContent() {
        return $this->_reponse_content;
    }

    /**
     * http请求
     */
    public function curlRequest()
    {
        $data_string = http_build_query($this->_request_data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_gateway_url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Apache-HttpClient/4.5.2 (Java/1.8.0_144)');
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data_string)
        ]);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        if (substr($this->_gateway_url, 0, 5) == 'https') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            // curl_setopt($curl, CURLOPT_CAINFO, '/cacert.pem');
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $this->_reponse_content = curl_exec($curl);

        //记录返回编码
        $this->_reponse_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        $error_info = 'call http error info :'.curl_errno($curl) . '-'.curl_error($curl);

        if (curl_errno($curl)) {
            curl_close($curl);
            return false;
        }
        curl_close($curl);
        return false;
    }
}