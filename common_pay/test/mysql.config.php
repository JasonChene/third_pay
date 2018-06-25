<?php
require_once('mysql.user.php');
$driver_options = array(PDO::ATTR_PERSISTENT=>true);

unset($mydata1_db);
$mydata1_db = new PDO("mysql:host=60.245.31.1:3306;dbname=7k111data1_db;charset=utf8",$db_user_utf8,$db_pwd_utf8,$driver_options);
$mydata1_db->query("SET NAMES utf8");
$mydata1_db->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
$mydata1_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


unset($mydata1_dbname);
$mydata1_dbname = "testdata1_db";

$version = "20160425";

?>
