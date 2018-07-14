<?php

require_once 'class/HttpRequest.class.php';
require_once 'class/HttpRequestHandle.class.php';
require_once 'class/ResultHandle.class.php';


class Request {
    private $_request_handle;
    private $_http_request;
    private $_result_handle;
    private $_config;
    private $_public_config;        //公共的参数

    public function __construct()
    {
        $this->_request_handle = new HttpRequestHandle();
        $this->_result_handle  = new ResultHandle();
        $this->_http_request   = new HttpRequest();
        $this->_config         = require_once 'config/config.php';
        $this->_public_config  = [
            'merchant_no'   =>  $this->_config['merchant_no'],
            'partner_id'    =>  $this->_config['partner_id'],
            'version'       =>  $this->_config['version'],
            'sign_type'     =>  $this->_config['sign_type'],
            'rand_str'      =>  $this->create_guid(),
        ];
    }

    //提交订单
    public function submitOrderInfo($param) {
        if(empty($param['service'])) {
            return $this->showJson(['errcode' => 10001,'msg' => '接口方法名不能为空']);
        }

        if(empty($param['merchant_order_sn'])) {
            return $this->showJson(['errcode' => 10002,'msg' => '商户订单号不能为空']);
        }

        if(empty($param['ord_name'])) {
            return $this->showJson(['errcode' => 10003,'msg' => '订单描述不能为空']);
        }

        if(empty($param['trade_amount'])) {
            return $this->showJson(['errcode' => 10004,'msg' => '交易金额不能为空']);
        }

        if(!preg_match('/^[0-9]+$/',$param['trade_amount'])) {
            return $this->showJson(['errcode' => 10005,'msg' => '交易金额不合法']);
        }

        if(empty($param['paychannel_type'])) {
            return $this->showJson(['errcode' => 10005,'msg' => '支付类型不能为空']);
        }

        if(empty($param['interface_type'])) {
            return $this->showJson(['errcode' => 10006,'msg' => '接口类型不能为空']);
        }

        if(!in_array($param['interface_type'],[1,2])) {
            return $this->showJson(['errcode' => 10007,'msg' => '接口类型不合法']);
        }

        if(empty($param['merchant_notify_url'])) {
            return $this->showJson(['errcode' => 10008,'msg' => '异步回调地址不能为空']);
        }

        $parameters = array_merge($param,$this->_public_config);
        return $this->httpCall($parameters);
    }

    private function httpCall($param) {
        $this->_request_handle->setGatewayUrl($this->_config['url']);
        $this->_request_handle->setPrivateKey($this->_config['private_key']);
        $this->_request_handle->setBatchParam($param);
        $this->_http_request->setGatewayUrl($this->_request_handle->getGatewayUrl());
        $this->_http_request->setRequestData($this->_request_handle->getRequestParam());
        $this->_http_request->curlRequest();

        $this->_result_handle->setPublicKey($this->_config['public_key']);
        $this->_result_handle->setReponseContent($this->_http_request->getReponseContent());
        $sign_res = $this->_result_handle->verifySign();
        if($sign_res == true) {
            $return = $this->_result_handle->getReponseContent();
            return $this->showJson(
                [
                    'errcode'   => $return['errcode'],
                    'msg'       => $return['msg'],
                    'data'      => !empty($return['data'])?$return['data']:[],
                ]
            );

        }else {
            return $this->showJson(['errcode' => 10009,'msg' => '验签失败']);
        }
    }

    public function orderQuery($param) {
        if(empty($param['service'])) {
            return $this->showJson(['errcode' => 10001,'msg' => '接口方法名不能为空']);
        }

        if(empty($param['merchant_order_sn'])) {
            return $this->showJson(['errcode' => 10002,'msg' => '商户订单号不能为空']);
        }
        $parameters = array_merge($param,$this->_public_config);
        return $this->httpCall($parameters);
    }


    private function showJson($return) {
        $data = [
            'errcode'   =>  $return['errcode'],
            'msg'       =>  $return['msg'],
            'data'      =>  !empty($return['data'])?$return['data']:[],
        ];
        echo json_encode($data);
        exit;
    }

    /**
     * 生成GUID
     * @return string
     */
    private function create_guid(){
        return strtoupper(md5(uniqid(mt_rand(), true)));
    }

    /**
     * 回调数据验签
     */
    public function setNotify() {
        $notify_data = $_POST;

        $this->_result_handle->setReponseContent($notify_data);
        $this->_result_handle->setPublicKey($this->_config['public_key']);
        return $this->_result_handle->setNotify();
    }

}


/**
 *  建立Request类的反射类
 */
$class  = new ReflectionClass('Request');
$action = !empty($_GET['action'])?$_GET['action']:'';

if(!$class->hasMethod($action)) {
    return $this->showJson(['errcode' => 20002,'msg' => '非法请求']);
}
//实例化request类
$instance = $class->newInstance();
return $instance->$action($_POST);




