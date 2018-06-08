<?php
/**
 * Created by PhpStorm.
 * User: yangzhe
 * Date: 2017/9/13
 * Time: 13:09
 */
?>

<script src="http://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    var getting = {
        url:'queryOrder.php',
        data:{"txnTime":"<?php echo $a ?>","order_id":"<?php echo $a ?>"},
        dataType:'json',
        success:function(res) {
            console.log(res);
        }
    };
    //关键在这里，Ajax定时访问服务端，不断获取数据 ，这里是1.5秒请求一次。
    window.setInterval(function(){$.ajax(getting)},1500);
</script>
