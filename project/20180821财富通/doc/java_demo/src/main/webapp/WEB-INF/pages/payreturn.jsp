<%@ page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>


    <title>My JSP 'hello.jsp' starting page</title>

    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
    <meta http-equiv="description" content="This is my page">
    <link href="css/pay1.css" rel="stylesheet" media="screen">
    <script src="../../js/jquery-1.8.2.min.js"></script>
<style>
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
body, button, input, select, textarea, h1, h2, h3, h4, h5, h6 {
    font-family: PingFangSC-Regular,'helvetica neue','hiragino sans gb', 'microsoft yahei', tahoma,'microsoft yahei ui', simsun,sans-serif;
}
html, body {

height: 100vh;
}
h2 {
    font-size: 28px;
    margin-bottom: 15px;
	color: #343434;
    line-height: 1.2;
	margin-top: 20px;
	font-weight: 500;	
}
.small-dialog {
    background: white;
    padding: 0px 30px;
    text-align: left;
    max-width: 600px;
    margin: 0px auto;
    position: relative;
}
.btn {
	display: inline-block;	
    font-weight: 400;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    padding: 10px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    min-width: 160px;
    text-align: center;
    border-radius: 0;
    text-transform: uppercase;
    margin: 10px 0;
    -webkit-transition: all 0.2s ease-in-out;
    -moz-transition: all 0.2s ease-in-out;
    -ms-transition: all 0.2s ease-in-out;
    -o-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
	margin-right: 20px;
}
.btn-primary {
    color: #fff;
    background-color: #428bca;
    border-color: #357ebd;
}
.btn-success {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
}


.text-center {
    text-align: center;
}


</style>

</head>
<body>
<div class="body">
    <h1 class="mod-title" style="line-height: 60px;">
        <span class="ico_log" style="margin-top:0px;">支付成功</span>
    </h1>

    <div class="mod-ct" style="padding-bottom: 0px;">
        <div class="order">
        </div>

        <div class="time-item" style="padding-top: 10px">
            <div class="time-item"><h1>订单:前台:${orderid}</h1> </div>
        </div>

		<div class="tip"style="margin-top: 10px">            
            
                <p><a href="/index.html" class="btn btn-success moneyin">点击返回首页</a></p>

        </div>

        <div class="tip-text">
        </div>


    </div>

</div>

</body></html>