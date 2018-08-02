<?php
//获取回掉函数
$input = file_get_contents('php://input');

//数据转化
$data=json_decode($input);
//执行事件
if($data->status==0){
	//成功执行事件
	print_r('成功');
}else{
	//失败执行事件
	print_r('失败');
}



