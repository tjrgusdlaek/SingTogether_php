<?php
include "mysqlConnect.php";

$response = array();
$liveStreamingList = array();
$badgeList = array();
$response["result"] = false;   

$sql = "select * from `liveStreamingPost`"; // DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);
  
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
            "viewer" => $row['viewer']
            
        );
        array_push($liveStreamingList,$cheokLiveStreamingList);
    }

    $response["liveStreamingList"] = $liveStreamingList;   

}else{
    // $response["result"] = true;  
    $response["liveStreamingList"] = "";   
}

$sql = "select `email` from `badge`";
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


echo json_encode($response);

// $sql = "select * from `liveStreamingPost` ";
// $result = mysqli_query($conn,$sql);
// $rowCnt= mysqli_num_rows($result);

// $response = array();

// if($result){

//         for($i=0;$i<$rowCnt;$i++){
//             $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
//             $response[$i]= $row;
//         }
    
//         echo json_encode($response);
// }else{
//     echo "서버에서 보내지 못하였습니다";
// }

mysqli_close($conn);

?>