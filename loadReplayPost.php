<?php
include "mysqlConnect.php";


$userEmail=$_POST['userEmail'];

$response = array();
$replayPostList = array(); 
$userLikeList = array();
$badgeList = array();


$sql = "select * from `replayPostLike` where `userEmail` ='$userEmail' and `leaveCheck` = '0'";
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($rowCnt !==0) {
    if($result){
        for($i=0;$i<$rowCnt;$i++){
            $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
            $userLikePostColumn = array(
                "replayPostIdx" => $row['replayPostIdx'],
                "replayPostLikeIdx" => $row['idx']
            );
            array_push($userLikeList,$userLikePostColumn);
        }
        $response["userLikeList"] = $userLikeList;   
    }
}else{
    $response["userLikeList"] = "";   
}

$sql = "select * from `badge`";
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);
if($rowCnt!=0){

for($i=0;$i<$rowCnt;$i++){
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    $badgeListColumn = array(
        "email" => $row['email'],
    );
        array_push($badgeList,$badgeListColumn);
}
}else{
    $badgeList ="";
}
$response["badgeList"] = $badgeList; 

$sql = "select * from `replayPost` ";
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);
if($result){
    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $replayPostColumn = array(
            "idx" => $row['idx'],
            "thumbnail" => $row['thumbnail'],
            "replayTitle" => $row['replayTitle'],
            "uploadUserEmail" => $row['uploadUserEmail'],
            "uploadUserProfile" => $row['uploadUserProfile'],
            "uploadUserNickName" => $row['uploadUserNickName'],
            "replayLikeNumber" => $row['replayLikeNumber'],
            "replayHits" => $row['replayHits'],
            "uploadDate" => $row['uploadDate'],
            "replayVideo" => $row['replayVideo'],
            "time" => $row['time'],
            "replayReviewNumber" => $row['replayReviewNumber'],
            "uploadUserFCMToken" => $row['uploadUserFCMToken'],
            "userLeaveCheck" => $row['userLeaveCheck']
        );
        array_push($replayPostList,$replayPostColumn);
    }

    $response["replayPostList"] = $replayPostList;  

    // for($h =0; $h<sizeof($replayPostList); $h++){
    //     for($k =0; $k<sizeof($userLikeList); $k++){
    //         if($replayPostList["idx"] === $userLikeList["replayPostIdx"]){
    //             $isTrue = array(
    //                 "isTrue" => "true"
    //             );
    //             array_push($replayPostList,$isTrue);
    //         }else{
    //             $isTrue = array(
    //                 "isTrue" => "false"
    //             );
    //             array_push($replayPostList,$isTrue);
    //         }
    //     }
    // }

    echo json_encode($response);
}else{
    echo "서버에서 보내지 못하였습니다";
}

mysqli_close($conn);

?>