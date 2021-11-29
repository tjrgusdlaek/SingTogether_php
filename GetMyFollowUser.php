<?php 
include "mysqlConnect.php";

    $userEmail=$_POST['userEmail']; //idx 받아옴 

    
$response = array();
$followerList = array();
$myFollowlist = array();
$getfollowerList = array();
// $response["result"] = false;

$sql = "select `followingId` from `userFollowAndFollowing` where `userId` = '$userEmail'"; 
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($rowCnt ===0){
    $response["myFollowlist"] = "";   
}else{
    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $myFollwColumn = array(
            "followingId" => $row['followingId']
        );
        array_push($myFollowlist,$myFollwColumn);
    }
    $response["myFollowlist"] = $myFollowlist;   
}



$sql = "select `userId` from `userFollowAndFollowing` where `followingId` = '$userEmail'"; 
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($result){ 
    if($rowCnt ===0){
        $response["getfollowerList"] = "";   
    }else{
        for($i=0;$i<$rowCnt;$i++){
            $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
            $userColumn = array(
                "userId" => $row['userId']
            );
            array_push($followerList,$userColumn);
        }
    
        foreach((array) $followerList as  $value){
            $sql = "select `email`,`profile`,`nickname`,`token`   from `user` where `email` =  '$value[userId]'  and `leaveCheck` = '0'"; 
            $result = mysqli_query($conn,$sql);
            $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
            $getuserColumn = array(
                "email" => $row['email'],
                "nickname" => $row['nickname'],
                "profile" => $row['profile'],
                "token" => $row['token']
            );
            array_push($getfollowerList,$getuserColumn);
        }
        $response["getfollowerList"] = $getfollowerList;   
    }
    echo json_encode($response);

}else{
     echo json_encode($response);
}

mysqli_close($conn);



?>