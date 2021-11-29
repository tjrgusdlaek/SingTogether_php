<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();

    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $social=$_POST['social'];
    $token=$_POST['token'];
    $profile=$_POST['profile'];

if($mysqli){

    $conn = mysqli_connect($host,$user,$pw,$dbName);
    
    $sql="insert into `user`(`email`,`nickname`,`social`,`token`,`profile`)
             values('$email','$nickname','$social','$token','$profile')";
    $result =mysqli_query($conn, $sql); 
    $response["sql"] = $sql;
   
    //     $response["success"] = true;
    echo json_encode($response);

}else{
    echo "MySQL 접속 실패";
}


mysqli_close($conn);

?>