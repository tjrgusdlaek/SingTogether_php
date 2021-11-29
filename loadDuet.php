<?php
// duet 불러오기
include "mysqlConnect.php";

$sql = "select duet.idx as duet_idx,duet.thumbnail,duet.cnt_play,duet.cnt_reply,duet.email,duet.nickname,duet.cnt_duet,
    duet.duet_path,duet.date,duet.extract_path,duet.kinds,
    mr.idx as mr_idx,mr.title,mr.singer,mr.song_path,mr.lyrics,`user`.profile,`user`.email,`user`.token from duet,mr,`user`
    where duet.mr_idx=mr.idx and duet.email=user.email";
$result = mysqli_query($conn, $sql);
$rowCnt = mysqli_num_rows($result);

$response = array();
$outputData = array();
$badgeList = array();

//밷지
$sql3 = "select * from `badge`";
$result3 = mysqli_query($conn,$sql3);
$rowCnt3= mysqli_num_rows($result3);
if($rowCnt3!=0){

for($i=0;$i<$rowCnt3;$i++){
    $row3= mysqli_fetch_array($result3,MYSQLI_ASSOC);
    $badgeListColumn = array(
        "email" => $row3['email'],
    );
        array_push($badgeList,$badgeListColumn);
}
}else{
    $badgeList ="";
}
$response["badgeList"] = $badgeList; 

if ($result) {
    for ($j = 0; $j < $rowCnt; $j++) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $dataColumn = array(
            "duet_idx" => $row['duet_idx'],
            "thumbnail" => $row['thumbnail'],
            "cnt_play" => $row['cnt_play'],
            "cnt_reply" => $row['cnt_reply'],
            "email" => $row['email'],
            "nickname" => $row['nickname'],
            "cnt_duet" => $row['cnt_duet'],
            "duet_path" => $row['duet_path'],
            "date" => $row['date'],
            "extract_path" => $row['extract_path'],
            "kinds" => $row['kinds'],
            "mr_idx" => $row['mr_idx'],
            "title" => $row['title'],
            "singer" => $row['singer'],
            "song_path" => $row['song_path'],
            "lyrics" => $row['lyrics'],
            "profile" => $row['profile'],
            "token" => $row['token']
        );
        array_push($outputData,$dataColumn);
    
        }
  
    } else {
        echo "SQL문 처리중 에러 발생2 : ";
    }

    $response['duetList']=$outputData;
    echo json_encode($response, JSON_UNESCAPED_SLASHES); 

mysqli_close($conn);



// $outputData = array();
// if ($result) {
//     for ($i = 0; $i < $rowCnt; $i++) {
//         $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//         //각 각의 row를 $arr에 추가
//         $arr[$i] = $row;
//     }

//     $outputData = $arr;
//     $jsonData = json_encode($outputData, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
//     echo $jsonData;

// } else {
//     echo "SQL문 처리중 에러 발생 : ";
//     echo mysqli_error($conn);
// }

// mysqli_close($conn);
?>