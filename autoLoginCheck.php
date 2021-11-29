<?php 
include "mysqlConnect.php";

$email=$_POST['email']; //email 받아옴 
    
$response = array();
$response["result"] = false;
$response["isBadge"] = false;

$sql = "select * from `badge` where `email` = '$email'  and `leaveCheck` = '0'  "; //user DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);


if($rowCnt ===0){
    $response["isBadge"] = false;
}else{
    $response["isBadge"] = true;
}



if($email ===""){
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
    return;
    
}
$sql = "select * from `user` where `email` = '$email'  and `leaveCheck` = '0' "; //user DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
$rowCnt= mysqli_num_rows($result);

if($rowCnt >=1){ // true or false로 보내줌 

    $response["result"] = true;       
    $response["email"] = $row["email"];     
    $response["nickname"] = $row["nickname"];  
    $response["profile"] = $row["profile"];    
    $response["social"] = $row["social"];  
    $response["token"] = $row["token"];  

    echo json_encode($response,JSON_UNESCAPED_UNICODE);

}else{
     echo json_encode($response,JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);



?>