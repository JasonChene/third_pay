<?php
$orderid = $_REQUEST["orderid"];

$filename = $orderid . ".lock";

if (file_exists($filename)) {
    echo "1";
    unlink($filename);
} else {
    echo "2";
}

