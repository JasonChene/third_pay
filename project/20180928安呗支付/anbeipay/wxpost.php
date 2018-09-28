<?php
header("content-type:text/html;charset=utf-8");
try {

    //解决OpenSSL Error问题需要加第二个array参数，具体参考 http://stackoverflow.com/questions/25142227/unable-to-connect-to-wsdl
    $client = new SoapClient("http://www.paghy.top:8080/pay/CXFServlet/PaySmService?wsdl",
        array(
            "stream_context" => stream_context_create(
                array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    )
                )
            )
        )
    );
    //print_r($client->__getFunctions());
    //print_r($client->__getTypes());

    $parm = array('mobile' => '136XXXXXX', 'mmsid' => 'XXX', 'sToken' => 'XXX');
    $result = $client->SendPersonMMS($parm);
    //print_r($result);

    //将stdclass object的$result转换为array
    $result = get_object_vars($result);  
    //输出结果 
    echo $result["SendPersonMMSResult"];
   

} catch (SOAPFault $e) {
    print $e;
}
?>