<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();

    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $profile=$_POST['profile'];
    $liveTitle=$_POST['liveTitle'];
    $file = $_FILES['uploaded_file'];

    $srcName= $file['name'];
    $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp

    $response["result"] = false;

//임시 저장소 이미지를 원하는 폴더로 이동
$dstName= "uploadFile/".date('Ymd_his').$srcName;
$result=move_uploaded_file($tmpName, $dstName);

if($result){
        $sql="insert into `liveStreamingPost`(`email`,`nickName`,`profile`,`title`,`thumbnail`)
                values('$email','$nickname','$profile','$liveTitle','$dstName')";
        $result =mysqli_query($conn, $sql); 

        if($result){  
            
            $sql ="select `idx` , `thumbnail` from `liveStreamingPost` where `email` = '$email'  and `title` = '$liveTitle'";
            $result =mysqli_query($conn, $sql); 
            $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
            // $response["sql"] = $sql;
            $response["result"] = true;
            $response["roomIdx"] =$row['idx'];
            $response["thumbnail"] =$row['thumbnail'];
            echo json_encode($response);
        }else{
            echo json_encode($response);
        }
}else{
    echo "MySQL 접속 실패";
}


mysqli_close($conn);

?>