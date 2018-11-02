<?php
require_once 'payBase.class.php';

class paySubmit extends payBase {

    public function __construct($config) {
        parent::__construct($config);
    }

    // 过滤掉无用字段
    private function _filterField($arr = array()) {
        $allow_field = array(
            'orderNo', 'orderAmt', 'thirdChannel', 'remark1', 'remark2','payprod',
            'notifyUrl', 'callbackUrl'
        );
        foreach ($arr as $key => $item) {
            if (!in_array($key, $allow_field)) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }

    /**
     * 统一下单接口
     * @param array $inputobj
     * @param int $timeOut
     * @return 成功时返回
     */
    public function unifiedOrder($inputobj = array(), $timeOut = 30) {
        $post_url = $inputobj['postUrl'];

        // 过滤掉无用字段
        $inputobj = $this->_filterField($inputobj);

        $inputobj['merId'] = $this->pay_config['merId']; // 商户ID
        $inputobj['version'] = $this->pay_config['version']; // 商户密钥

        // 检测必填参数
        if (!$inputobj['orderNo']) {
            $this->errMsg = "商户生成的订单号，不能为空且不能重复！";
            return false;
        }

        if (!$inputobj['orderAmt']) {
            $this->errMsg = "订单金额不能为空(单位元)！";
            return false;
        }

        if (!$inputobj['thirdChannel']) {
            $this->errMsg = "支付通道必填！";
            return false;
        }

    //    if (!$inputobj['remark1']) {
    //        $this->errMsg = "备注1不能为空！";
    //        return false;
    //    }

    //    if (!$inputobj['remark2']) {
    //        $this->errMsg = "备注2不能为空！";
    //        return false;
    //    }

        // 商户后台异步通知url
        if (!$inputobj['notifyUrl']) {
            $this->errMsg = "商户后台异步通知url不能为空！";
            return false;
        }

        // 支付成功后，从收银台跳到商户的页面
        if (!$inputobj['callbackUrl']) {
            $this->errMsg = "商户前台回调url不能为空！";
            return false;
        }

        // 非空参数值生成签名 注意参与签名的字段
        $inputobj['sign'] = $this->makeSign($inputobj);

        $jsonData = self::jsonEncode($inputobj);

        $this->logger("下单(" . $inputobj['orderNo'] . ")发送报文: " . $jsonData);

        // 提交下单请求
        $result = self::httpPost($post_url, $jsonData, $timeOut);

        $this->logger("下单(" . $inputobj['orderNo'] . ")返回报文: " . $result);

        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['respCode'] != '0000') {
                $this->errCode = $json['respCode'];
                $this->errMsg = "请求错误(" . $json['respCode'] . ':' . $json['respMsg'] . ')';

                $this->logger("下单(" . $inputobj['orderNo'] . ") " . $this->errMsg);
                return false;
            }
        }

        return $json;
    }

    /**
     * wap下单
     * @param array $inputobj
     * @param int $timeOut
     * @return 成功时返回
     */
    public function payOrder($inputobj = array(), $timeOut = 30) {
        
        return $this->unifiedOrder($inputobj, $timeOut);
    }

   

    /**
     * 订单查询
     * @param string $merOrderNo 商户订单号
     * @param int $timeOut
     * @return 成功时返回
     */
    public function orderQuery($inputobj = array(), $timeOut = 30) {
        // 检测必填参数
        if (!$inputobj['orderNo']) {
            $this->errMsg = "查询订单号不能为空！";
            return false;
        }
        $post_url = $inputobj['postUrl'];
        $orderNo = $inputobj['orderNo'];
        $inputobj = array();
        $inputobj['merId'] = $this->pay_config['merId']; // 商户ID
        $inputobj['version'] = $this->pay_config['version']; // 商户密钥
        
        $inputobj['orderNo'] = $orderNo;

        // 非空参数值生成签名 注意参与签名的字段
        $inputobj['sign'] = $this->makeSign($inputobj);

        $jsonData = self::jsonEncode($inputobj);

        $this->logger("查询订单(" . $inputobj['orderNo'] . ")发送报文: " . $jsonData);

        // 提交下单请求
        $result = self::httpPost($post_url, $jsonData, $timeOut);

        $this->logger("查询订单(" . $inputobj['orderNo'] . ")返回报文: " . $result);

        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['respCode'] != '0000') {
                $this->errCode = $json['respCode'];
                $this->errMsg = "请求错误(" . $json['respCode'] . ':' . $json['respMsg'] . ')';

                $this->logger("查询订单(" . $inputobj['orderNo'] . ") " . $this->errMsg);
                return false;
            }
        }

        return $json;
    }

}