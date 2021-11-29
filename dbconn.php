<?php
function dbconn()
{
    header('Content-Type: application/json; charset=utf8');
    $conn=new mysqli("database-2.c1syd5qqy4xb.ap-northeast-2.rds.amazonaws.com","root","zxzx3709!","summer_jang");
    mysqli_query($conn,'SET NAMES utf8');
    return $conn;
} 

?>