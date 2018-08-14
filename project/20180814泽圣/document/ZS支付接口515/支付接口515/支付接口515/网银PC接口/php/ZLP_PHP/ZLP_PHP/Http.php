<?php

/**
 * 这段代码仅供参考
 * 仅完成了POST请求的功能
 *
 **/
class Http
{

    /**
     * 其它版本
     * 使用方法：
     * $post_string = "app=request&version=beta";
     * request_by_other('http://facebook.cn/restServer.php',$post_string);
     */
    function request_by_other($remote_server, $post_string)
    {
        $context = array(
            'http' => array(
                'method’=>’POST',
                'header' => 'Content-type: application/x-www-form-urlencoded' . "\r\n" . 'User-Agent : Jimmy\'s POST Example beta' . "\r\n" . 'Content-length: ' . strlen($post_string),
                'content' => $post_string
            )
        );
        echo $post_string . "<br/>";
        $stream_context = stream_context_create($context);
        $data = file_get_contents($remote_server, FALSE, $stream_context);
        return $data;
    }
}
// phpinfo();
// $p1=new Http();
// echo $p1->request_by_other("", "ssss");
?>