<?php
include "mysqlConnect.php";

$response = array();
$otherUserInformation = array(); 


$response["result"] = false;
$otherUserEmail=$_POST['otherUserEmail'];
$UserEmail=$_POST['UserEmail'];


$response["isBadge"] = false;

$sql = "select `email` from `badge` where `email` = '$otherUserEmail' "; //user DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);


if($rowCnt >=1){
    $response["isBadge"] = true;
}

// ////유저 프로필액티비티로 넘어가기전에 해당유저의 데이터를 가져온다 
// $sql = "select * from `user` where `email` ='$otherUserEmail'";
// $result = mysqli_query($conn,$sql);
// if($result){
//     $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
//     $otherUserInfoColumn = array(
//         "idx" => $row['idx'],
//         "nickname" => $row['nickname'],
//         "profile" => $row['profile']
//     );
//     array_push($otherUserInformation,$otherUserInfoColumn);
// }else{
//     $response["result"] = false;
//     echo json_encode($response);
//     return;
// }

// $response["otherUserInformation"] = $otherUserInformation;  
 

////해당유저의 팔로잉 수 데이터를 담아준다 . 
$sql = "select * from `userFollowAndFollowing` where `userId` ='$otherUserEmail'";
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
$sql = "select * from `userFollowAndFollowing` where `followingId` ='$otherUserEmail'";
$result = mysqli_query($conn,$sql);
if($result){
    $rowCnt= mysqli_num_rows($result);
    $response["followUserNumber"] = $rowCnt;  
}else{
    $response["result"] = false;
    echo json_encode($response);
    return;
}

////해당유저와 자신이 팔로우를 맺고있는지 확인유무 
$sql = "select * from `userFollowAndFollowing` where `followingId` ='$otherUserEmail' and `userId` = '$UserEmail'";
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($rowCnt===1){
    $response["isFollow"] = true;
}else{
    $response["isFollow"] = false;
}

$response["result"] = true;
echo json_encode($response);
 


mysqli_close($conn);

?>