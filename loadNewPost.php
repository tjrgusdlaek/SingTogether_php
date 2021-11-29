<?php

//**최신순으로 songPost 테이블 데이터 불러오기 - 10개씩 페이징
include "mysqlConnect.php";
$zero="0";

$userEmail=$_POST['email'];


// $sql ="select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
//         songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
//         songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
//     mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
//     from mr,`user`,songPost LEFT JOIN songPostLike 
//     ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
//     where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.userLeaveCheck=user.leaveCheck
//     group by songPost.idx
//     LIMIT 50";

$sql ="select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
        songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
    mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
    from mr,`user`,songPost LEFT JOIN songPostLike 
    ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail' and songPostLike.leaveCheck='0'
    where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.userLeaveCheck=user.leaveCheck
    group by songPost.idx
    LIMIT 50";

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

$zero="0";

$result = mysqli_query($conn, $sql);
$rowCnt = mysqli_num_rows($result);


if ($result) {
    for ($j = 0; $j < $rowCnt; $j++) {

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        
        // $sql2 ="select user.profile as col_profile, user.token as col_token,user.leaveCheck as collaborationLeaveCheck from `user`
        //  where `email` = '$row[collabo_email]' and `user`.leaveCheck='0'";

         $sql2 ="select user.profile as col_profile, user.token as col_token from `user`
         where `email` = '$row[collabo_email]' and $row[collaborationLeaveCheck]=`user`.leaveCheck";
        
        $result2 = mysqli_query($conn, $sql2);
        $rowCnt2 = mysqli_num_rows($result2);  
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

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
            "isLike" => $row['isLike'],
            "userLeaveCheck" => $row['userLeaveCheck'],
            "collaborationLeaveCheck" => $row['collaborationLeaveCheck'],
            "col_profile" => $row2['col_profile'],
            "col_token" => $row2['col_token']
        );
        array_push($outputData,$dataColumn);
    
        }
  
    } else {
        echo "SQL문 처리중 에러 발생2 : ";
    }

    $response['homeList']=$outputData;
    echo json_encode($response, JSON_UNESCAPED_SLASHES); 

    mysqli_close($conn);


?>