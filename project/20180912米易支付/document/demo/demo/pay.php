<?php
require_once 'config.php';

//获取表单提交参数
$params = $_POST;
//初始化Config类
$config = new Config;
$datas = $config->startexecution($params);
// echo $datas;exit;
//将返回结果转为数组。
$return = json_decode($datas,true);

if(!is_array($return)){
    echo '请求失败:'.$datas;
    exit;
}else{
    if($return['resultCode']=='0000'){

        // if(isset($return['type'])){
        //     header("Location: qrcode.php?data=".urlencode($return['qr_code']));exit;
        // }else{
        //     // header("Location: ".$return['qr_code']);exit;
        //     header("Location: ".$return['qr_code']);exit;
        // }
        if($return['gateway'] == 'weixin') {
            header("Location: ./erweima/erweima.php?data=".$return['pay_url'].'&total_fee='.$return['total_fee']);exit;
           // require_once 'phpqrcode.php';
           // ob_clean();
            //QRcode::png($return['pay_url'], false, 'L', 6);
        }
        if($return['gateway'] == 'wxh5') {
            header("Location: ".$return['pay_url']);exit;
        }
    }
}
?>


