<?php 
include "mysqlConnect.php";

    $roomIdx=$_POST['roomIdx']; //idx 받아옴 

    
$response = array();
$response["result"] = false;

$sql = "select * from `liveStreamingPost` where `idx` = '$roomIdx' "; // DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
$rowCnt= mysqli_num_rows($result);

$viewer = $row['viewer'];
$viewer_int =  (int)$viewer;
$viewer_int++;
$updateViewer = (String)$viewer_int;


if($rowCnt >=1){ // true or false로 보내줌 
    $sql = "update `liveStreamingPost` set `viewer` ='$updateViewer' where `idx`='$roomIdx'";
    $result = mysqli_query($conn, $sql);
    
    $response["result"] = true;       
    echo json_encode($response);

}else{
     echo json_encode($response);
}

mysqli_close($conn);



?>