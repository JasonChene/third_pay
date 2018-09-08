<?php
/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilters($para) {
    $para_filter = array();
    while (list ($key, $val) = each ($para)) {
        if($key == "sign" || $val == "")continue;
        else	$para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSorts($para) {
    ksort($para);
    reset($para);
    return $para;
}
		        
 /**
  * 签名验证
  * $datas 数据数组
  * $key 密钥
  */
 function sign($datas = array(), $key = ""){

     $str=http_build_query(argSorts(paraFilters($datas)));
     //print_r($str);
     $str = urldecode($str); 
     //$str = urldecode(http_build_query(argSorts(paraFilters($datas))));
     print_r($str."&appkey=".$key.'<br>');
     $sign = md5($str."&appkey=".$key);
     return $sign;
 }


 ?>