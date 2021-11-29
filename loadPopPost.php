<?php
//**인기순으로 songPost 테이블 데이터 불러오기 - 50개 제한
include "mysqlConnect.php";


$userEmail=$_POST['email'];

$sql ="select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
    mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike,
    rank() over (order by songPost.cnt_like desc) as ranking
    from mr,`user`,songPost LEFT JOIN songPostLike 
    ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
    where songPost.mr_idx=mr.idx and songPost.email=user.email
    order by ranking desc
    LIMIT 50";

// $sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user where songPost.collabo_email=user.email";

$response = array();
$outputData = array();
$badgeList = array();



//$outputData = array();

$result = mysqli_query($conn, $sql);
$rowCnt = mysqli_num_rows($result);

// $result2 = mysqli_query($conn, $sql2);
// $rowCnt2 = mysqli_num_rows($result2);

if ($result) {
    for ($j = 0; $j < $rowCnt; $j++) {

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        $sql2 ="select user.profile as col_profile, user.token as col_token from `user` where `email` = '$row[collabo_email]'";
        $result2 = mysqli_query($conn, $sql2);
        $rowCnt2 = mysqli_num_rows($result2);  
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        // $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        $dataColumn = array(
            "idx" => $row['idx'],
            "thumbnail" => $row['thumbnail'],
            "cnt_play" => $row['cnt_play'],
            "cnt_reply" => $row['cnt_reply'],
            "cnt_like" => $row['cnt_like'],
            "email" => $row['email'],
            "nickname" => $row['nickname'],
            "song_path" => $row['song_path'],
            "date" => $row['date'],
            "profile" => $row['profile'],
            "collaboration" => $row['collaboration'],
            "collabo_email" => $row['collabo_email'],
            "kinds" => $row['kinds'],
            "mr_idx" => $row['mr_idx'],
            "title" => $row['title'],
            "singer" => $row['singer'],
            "lyrics" => $row['lyrics'],
            "token" => $row['token'],
            "ranking"=>$row['ranking'],
            "isLike" => $row['isLike'],
            "col_profile" => $row2['col_profile'],
            "col_token" => $row2['col_token']
        );
        array_push($outputData,$dataColumn);
    
        }
  
    } else {
        echo "SQL문 처리중 에러 발생2 : ";
    }

    
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
    $response['homeList']=$outputData;
    echo json_encode($response, JSON_UNESCAPED_SLASHES); 

//echo json_encode($outputData,JSON_UNESCAPED_UNICODE);

mysqli_close($conn);

?>
