<?php 
include "mysqlConnect.php";

$nickname=$_POST['nickname']; //email 받아옴 
    
$response = array();
$response["result"] = false;


$sql = "select * from `user` where `nickname` = '$nickname' "; //user DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
$rowCnt= mysqli_num_rows($result);

if($rowCnt ===0){ // true or false로 보내줌 

    $response["result"] = true;       
  

}else{
    
    $response["result"] = false;       
  
}
echo json_encode($response,JSON_UNESCAPED_UNICODE);
mysqli_close($conn);



?>