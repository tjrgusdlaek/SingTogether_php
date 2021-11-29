<?php 
include "mysqlConnect.php";

    $searchInput=$_POST['searchInput']; //idx 받아옴 

    
$response = array();
$response["result"] = false;
$liveStreamingList = array();
$replayPostList = array();
$userLikeList = array();
$badgeList = array();
if($searchInput ===null or $searchInput === ""){
    echo json_encode($response);
    return;
}

$sql = "select * from `liveStreamingPost` where `nickName` like '%$searchInput%' or `title` like'%$searchInput%'"; // DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);
$response["sql"] = $sql;   
if($rowCnt >=1){
    $response["result"] = true;   
    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $cheokLiveStreamingList = array(
            "idx" => $row['idx'],
            "email" => $row['email'],
            "thumbnail" => $row['thumbnail'],
            "nickName" => $row['nickName'],
            "profile" => $row['profile'],
            "title" => $row['title'],
            "viewer" => $row['viewer'],
            "category" => "live"
        );
        array_push($liveStreamingList,$cheokLiveStreamingList);
    }

    $response["liveStreamingList"] = $liveStreamingList;   

}else{
    $response["result"] = true;  
    $response["liveStreamingList"] = "";   
}




$sql = "select * from `replayPostLike` where `userEmail` ='$userEmail'";
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($rowCnt !==0){
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

$sql = "select * from `replayPost` where `uploadUserNickName` like '%$searchInput%' or `replayTitle` like'%$searchInput%'"; // DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);

if($rowCnt >=1){
    $response["result"] = true;   
    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $replayPostcolumn = array(
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
            "category" => "replay",
            "uploadUserFCMToken" => $row['uploadUserFCMToken'],
        );
        array_push($replayPostList,$replayPostcolumn);
    }

    $response["replayPostList"] = $replayPostList;   

}else{
    $response["result"] = true;  
    $response["replayPostList"] = "";   
}

     echo json_encode($response);
mysqli_close($conn);



?>