<?php
include "mysqlConnect.php";

$response = array();
$otherUserInformation = array(); 


$response["result"] = false;

$UserEmail=$_POST['UserEmail'];

$sql = "select * from `userFollowAndFollowing` where `userId` ='$UserEmail'";
$result = mysqli_query($conn,$sql);
if($result){
    $rowCnt= mysqli_num_rows($result);
    $response["followingUserNumber"] = $rowCnt;  
}else{
    $response["result"] = false;
    echo json_encode($response);
    return;
}

////해당유저의 팔로워 수 데이터를 담아준다 . 
$sql = "select * from `userFollowAndFollowing` where `followingId` ='$UserEmail'";
$result = mysqli_query($conn,$sql);
if($result){
    $rowCnt= mysqli_num_rows($result);
    $response["followUserNumber"] = $rowCnt;  
}else{
    $response["result"] = false;
    echo json_encode($response);
    return;
}


$response["result"] = true;
echo json_encode($response);
 


mysqli_close($conn);

?>