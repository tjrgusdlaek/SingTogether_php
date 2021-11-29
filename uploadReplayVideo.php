<?php
include "mysqlConnect.php";

$response = array();

$userEmail=$_POST['userEmail'];
$nickname=$_POST['nickname'];
$profile=$_POST['profile'];
$roomTitle=$_POST['roomTitle'];
$thumbnail=$_POST['thumbnail'];
$time=$_POST['time'];
$uploaderToken=$_POST['uploaderToken'];   
$file = $_FILES['uploaded_file'];
$response["file"] = $file;
$date=date("Y-m-d",time());

$srcName= $file['name'];
$tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
$dstName= "uploadFile/".date('Ymd_his').$srcName;
$result=move_uploaded_file($tmpName, $dstName);
$response["dstName"] = $dstName;
if($result){

        $sql="insert into `replayPost`(`thumbnail`,`replayVideo`,`replayTitle`,`uploadUserEmail`,`uploadUserProfile`,`uploadUserNickName`,`uploadDate`,`time`,`uploadUserFCMToken`)
        values('$thumbnail','$dstName','$roomTitle','$userEmail','$profile','$nickname','$date','$time','$uploaderToken')";
        $res =mysqli_query($conn, $sql);

        $response["sql"] = $sql;
        echo json_encode($response);

    }else{
    echo "error";
    }


mysqli_close($conn);

?>