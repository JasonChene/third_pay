<?php

//返回页面处理类
class payback {

    public $trade_no, $out_order_no, $total_fee, $trade_status, $sign, $pid, $key;

    public function __construct() {
        //交易号
        $this->trade_no = $_GET['trade_no'];
        //订单号
        $this->out_order_no = $_GET['out_order_no'];
        //金额
        $this->total_fee = $_GET['total_fee'];
        //交易状态
        $this->trade_status = $_GET['trade_status'];
        //服务器加密字符串
        $this->sign = $_GET['sign'];
        //环球付pid
        $this->pid = '303126';
        //环球付key
        $this->key = 'cWfeQ9uV3samKt3uAFVXkhkBQk7h4gAc';
    }
      //验证
    public function _validation() {
        $md5sign = md5($this->out_order_no . $this->total_fee . $this->trade_status . $this->pid . $this->key);
        if ($this->trade_status == 'TRADE_SUCCESS') {
            if ($md5sign == $this->sign) {
                return '交易成功';
                
            } else {
                //validation fail
                return '支付出错';
            }
        }
        return $$this->trade_status;
    }

}
