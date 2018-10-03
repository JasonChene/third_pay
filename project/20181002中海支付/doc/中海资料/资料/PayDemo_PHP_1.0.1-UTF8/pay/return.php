<?php
    /* *
    * 功能：支付接口调试入口页面
    * 版本：1.0
    * 修改日期：2018-06-11
    * 说明：
    * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
    */
    ini_set('display_errors','off');
    error_reporting(E_ALL);
    header("Content-type: text/html; charset=utf-8");
    date_default_timezone_set('Asia/Shanghai');
    require_once dirname ( __FILE__ ).'/lib/PayUtils.php';
    $Pay=new PayUtils();
    echo "<pre>";
    print_r($_REQUEST);
    if($_POST['respCode']=='00'){
        $status=$Pay->makeNotifySign($_POST,'pay_return');
        if($status){
            //验签成功= 停止通知
            echo 'SUCCESS';
        }else{
            echo '验签失败<br>';
        }
    }else{
        echo '返回的状态：'.$_POST['respCode'].'，返回的错误信息：'.$_POST['respDesc'];
    }
    
    exit;

?>
