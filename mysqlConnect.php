<?php

$host = 'database-2.c1syd5qqy4xb.ap-northeast-2.rds.amazonaws.com';
$user = 'root';
$pw = 'zxzx3709!';
$dbName = 'summer_jang';
$mysqli=new mysqli($host,$user,$pw,$dbName);


if($mysqli){
    // echo "MySQL 접속 성공";

    $conn = mysqli_connect($host,$user,$pw,$dbName);
}else{
    echo "MySQL 접속 실패";
}



?>