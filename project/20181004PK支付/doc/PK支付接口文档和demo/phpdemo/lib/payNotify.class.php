<?php
require_once 'payBase.class.php';

class payNotify extends payBase {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * 针对notify_url验证消息是否合法消息
     * @return 验证结果
     */
    private function _verifyNotify() {
        // 获取通知的数据
        $json = file_get_contents("php://input");

        if (empty($json)) {
            $this->errMsg = '支付通知回调签名错误';
            $this->logger($this->errMsg);
            return false;
        }

        $result = json_decode($json, true);
        if (!$result) {
            $this->errMsg = "支付通知服务器提供参数解析失败";
            $this->logger($this->errMsg);
            return false;
        }

        // 验证签名
        if (!$this->checkSign($result)) {
            $this->errMsg = '支付通知回调签名错误，数据:' . $json;
            $this->logger($this->errMsg);
            return false;
        }

        return $result;
    }

    /**
     * @param bool $response_code 是否需要返回状态码(报文)给交易平台
     * @return bool|验证结果
     */
    public function notify($return_code = true) {
        // 验证签名
        $result = $this->_verifyNotify();
        if (!$result) {
            return false;
        }

        // 交易支付订单状态 S = 支付成功
        if ($result['payStatus'] == 'S') {

            // 商户订单号
            $merOrderNo = $result['merOrderNo'];

            // 更安全的验证 这里可以通过交易订单查询接口, 查询交易订单状态 ，可选

            // 这里处理本地订单逻辑代码
            // ......
            // ......

            //

            $this->logger('支付回调成功, 数据:'.json_encode($result, true));

            // 输出状态码给远程服务器，请不要修改或删除
            if ($return_code) {
                $result['return_code'] = "success";
            }

            return $result;
        }
    }

}