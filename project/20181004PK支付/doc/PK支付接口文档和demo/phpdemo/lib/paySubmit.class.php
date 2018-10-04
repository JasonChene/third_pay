<?php
require_once 'payBase.class.php';

class paySubmit extends payBase {

    public function __construct($config) {
        parent::__construct($config);
    }

    // 过滤掉无用字段
    private function _filterField($arr = array()) {
        $allow_field = array(
            'merOrderNo', 'orderAmt', 'payPlat', 'orderTitle', 'orderDesc',
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
        $post_url = $inputobj['post_url'];

        // 过滤掉无用字段
        $inputobj = $this->_filterField($inputobj);

        $inputobj['merId'] = $this->pay_config['merId']; // 商户ID
        $inputobj['version'] = $this->pay_config['version']; // 商户密钥

        // 检测必填参数
        if (!$inputobj['merOrderNo']) {
            $this->errMsg = "商户生成的订单号，不能为空且不能重复！";
            return false;
        }

        if (!$inputobj['orderAmt']) {
            $this->errMsg = "订单金额不能为空(单位元)！";
            return false;
        }

        if (!$inputobj['payPlat']) {
            $this->errMsg = "支付平台必填！";
            return false;
        }

        if (!$inputobj['orderTitle']) {
            $this->errMsg = "订单标题不能为空！";
            return false;
        }

        if (!$inputobj['orderDesc']) {
            $this->errMsg = "订单描述不能为空！";
            return false;
        }

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

        $this->logger("下单(" . $inputobj['merOrderNo'] . ")发送报文: " . $jsonData);

        // 提交下单请求
        $result = self::httpPost($post_url, $jsonData, $timeOut);

        $this->logger("下单(" . $inputobj['merOrderNo'] . ")返回报文: " . $result);

        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['respCode'] != '0000') {
                $this->errCode = $json['respCode'];
                $this->errMsg = "请求错误(" . $json['respCode'] . ':' . $json['respMsg'] . ')';

                $this->logger("下单(" . $inputobj['merOrderNo'] . ") " . $this->errMsg);
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
    public function wapOrder($inputobj = array(), $timeOut = 30) {
        $inputobj['post_url'] = "http://api.szcsjn.cn/grmApp/createWapOrder.do";
        if ($this->is_dev_env) {
            $inputobj['post_url'] = "http://api.szcsjn.cn/grmApp/createWapOrder.do";
        }
        return $this->unifiedOrder($inputobj, $timeOut);
    }

    /**
     * 扫码下单
     * @param array $inputobj
     * @param int $timeOut
     * @return 成功时返回
     */
    public function scanOrder($inputobj = array(), $timeOut = 30) {
        $inputobj['post_url'] = "http://api.szcsjn.cn/grmApp/createScanOrder.do";
        if ($this->is_dev_env) {
            $inputobj['post_url'] = "http://api.szcsjn.cn/grmApp/createScanOrder.do";
        }
        return $this->unifiedOrder($inputobj, $timeOut);
    }

    /**
     * 订单查询
     * @param string $merOrderNo 商户订单号
     * @param int $timeOut
     * @return 成功时返回
     */
    public function orderQuery($merOrderNo = '', $timeOut = 30) {
        $post_url = "http://api.szcsjn.cn/grmApp/queryOrder.do";
        if ($this->is_dev_env) {
            $post_url = "http://api.szcsjn.cn/grmApp/queryOrder.do";
        }

        $inputobj = array();
        $inputobj['merId'] = $this->pay_config['merId']; // 商户ID
        $inputobj['version'] = $this->pay_config['version']; // 商户密钥

        // 检测必填参数
        if (!$merOrderNo) {
            $this->errMsg = "查询订单号不能为空！";
            return false;
        }
        $inputobj['merOrderNo'] = $merOrderNo;

        // 非空参数值生成签名 注意参与签名的字段
        $inputobj['sign'] = $this->makeSign($inputobj);

        $jsonData = self::jsonEncode($inputobj);

        $this->logger("查询订单(" . $inputobj['merOrderNo'] . ")发送报文: " . $jsonData);

        // 提交下单请求
        $result = self::httpPost($post_url, $jsonData, $timeOut);

        $this->logger("查询订单(" . $inputobj['merOrderNo'] . ")返回报文: " . $result);

        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['respCode'] != '0000') {
                $this->errCode = $json['respCode'];
                $this->errMsg = "请求错误(" . $json['respCode'] . ':' . $json['respMsg'] . ')';

                $this->logger("查询订单(" . $inputobj['merOrderNo'] . ") " . $this->errMsg);
                return false;
            }
        }

        return $json;
    }

}