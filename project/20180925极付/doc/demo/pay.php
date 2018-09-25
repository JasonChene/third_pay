<?php
require_once __DIR__."/config.php";
require_once __DIR__."/function.php";

if(isset($_REQUEST['pid']) && isset($_REQUEST['type']) && isset($_REQUEST['out_trade_no']) && isset($_REQUEST['notify_url']) && isset($_REQUEST['return_url']) && isset($_REQUEST['name']) && isset($_REQUEST['money'])){

    //生成签名
    $data = array();
    $data['pid'] = $_REQUEST['pid'];
    $data['type'] = $_REQUEST['type'];
    $data['out_trade_no'] = $_REQUEST['out_trade_no'];
    $data['notify_url'] = $_REQUEST['notify_url'];
    $data['return_url'] = $_REQUEST['return_url'];
    $data['name'] = $_REQUEST['name'];
    $data['money'] = $_REQUEST['money'];
    if(isset($_REQUEST['sitename'])){
        $data['sitename'] = $_REQUEST['sitename'];
    }else{
        $data['sitename'] = '';
    }
    $data['sign'] = wmf\getSign($_REQUEST,$key);

    print <<< EOF
        <script>
        window.onload = function (ev) {
            document.getElementById('auto').submit();
        }
    </script>
<form action="{$gateway_url}" method="post" id="auto" style="display: none;" target="_self">
		商户号：<input type="hidden" name="pid" value="{$data['pid']}"><br />
		支付类型：<input  type="hidden" name="type" value="{$data['type']}"><br />
		商户订单号：<input type="hidden" name="out_trade_no" value="{$data['out_trade_no']}"><br />
		异步地址：<input type="hidden" name="notify_url" value="{$data['notify_url']}"><br />
		同步地址：<input type="hidden" name="return_url" value="{$data['return_url']}"><br />
		商品名称：<input type="hidden" name="name" value="{$data['name']}"><br />
		金额：<input type="hidden" name="money" value="{$data['money']}"><br />
        网站名称：<input type="hidden" name="sitename" value="{$data['sitename']}"> <br />
		签名：<input type="hidden" name="sign" value="{$data['sign']}"><br />
		签名类型：<input type="hidden" name="sign_type" value="MD5"><br />
		<input type="submit" value="提交">
	</form>
EOF;
    exit();
}else{
    echo "<script>alert('信息不完整，请检查。');window.close();</script>";
}
