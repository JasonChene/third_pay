<?php
header('Content-Type:text/html;charset=utf8');
$userkey='cd54ba7719d318d5564c7ed7e19eaee65cb99c43';
$status=$_POST['status'];
$customerid=$_POST['customerid'];
$sdorderno=$_POST['sdorderno'];
$total_fee=$_POST['total_fee'];
$realmoney=$_POST['realmoney'];

$sdpayno=$_POST['sdpayno'];
$sign=$_POST['sign'];

$mysign=md5('customerid='.$customerid.'&status='.$status.'&sdpayno='.$sdpayno.'&sdorderno='.$sdorderno.'&realmoney='.realmoney.'&'.$userkey);

if($sign==$mysign){
    if($status=='2'){
        echo('代付通过');
    } else {
        echo('代付拒绝');
    }

    echo 'success';
} else {
    echo 'signerr';
}
?>
