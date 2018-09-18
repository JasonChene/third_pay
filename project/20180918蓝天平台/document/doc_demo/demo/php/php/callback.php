<?php
//报文是QueryString格式
$data = file_get_contents("php://input");
//开始...........
//解析data 处理业务 
//结束...........
echo $data;
