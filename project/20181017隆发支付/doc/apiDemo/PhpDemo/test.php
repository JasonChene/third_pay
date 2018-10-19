<?php 
	include('util.php');
	#$data['user'] = 'zhao6810';
	#$data['pass'] = '1234560';
	#echo wx_post('http://127.0.0.1/test.php',$data);
	$post = 'user=zhao6810&pass=1234560';
	echo wx_post('http://127.0.0.1/test.php',$post);

?>