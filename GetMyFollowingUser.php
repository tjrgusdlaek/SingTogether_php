<?php 
include "mysqlConnect.php";

    $userEmail=$_POST['userEmail']; //idx 받아옴 

    
$response = array();
$followingList = array();
$getfollowingList = array();
$response["result"] = false;

$sql = "select `followingId` from `userFollowAndFollowing` where `userId` = '$userEmail'"; 
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($rowCnt ===0){
    echo json_encode($response);
    return;
}

if($result){ 

    $response["result"] = true;
    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $userColumn = array(
            "followingId" => $row['followingId']
        );
        array_push($followingList,$userColumn);
    }

    foreach((array) $followingList as  $value){
        $sql = "select `email`,`profile`,`nickname`,`token`   from `user` where `email` =  '$value[followingId]' and `leaveCheck` = '0'"; 
        $result = mysqli_query($conn,$sql);
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $getuserColumn = array(
            "email" => $row['email'],
            "nickname" => $row['nickname'],
            "profile" => $row['profile'],
            "token" => $row['token']
        );
        array_push($getfollowingList,$getuserColumn);
    }


    $response["getfollowingList"] = $getfollowingList;   
    echo json_encode($response);

}else{
     echo json_encode($response);
}

mysqli_close($conn);



?>