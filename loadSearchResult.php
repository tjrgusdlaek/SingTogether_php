<?php
include "mysqlConnect.php";

$searchInput=$_POST['searchInput']; 
$which=$_POST['which'];
$userEmail=$_POST['email'];

if($which=="mr") {
    $sql="select * from mr where `title` like '%$searchInput%' or `singer` like '%$searchInput%' ";

    $result=mysqli_query($conn,$sql);
    $rowCnt=mysqli_num_rows($result);

    $outputData = array();
    if($result){
        for($i=0 ; $i < $rowCnt ; $i++){
            $row= mysqli_fetch_array($result, MYSQLI_ASSOC);
            //각 각의 row를 $arr에 추가
            $outputData[$i]= $row;
        }

        // $outputData= $arr;
        // $jsonData=json_encode($outputData, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
        // echo $jsonData;
        echo json_encode($outputData);

    }else {
        echo "SQL문 처리중 에러 발생 : ";
        echo mysqli_error($conn);
    }

}else if($which=="duet"){
    $zero="0";
    $sql = "select duet.idx as duet_idx,duet.thumbnail,duet.cnt_play,duet.cnt_reply,duet.email,duet.nickname,duet.cnt_duet,
    duet.duet_path,duet.date,duet.extract_path,duet.kinds,duet.userLeaveCheck,
    mr.idx as mr_idx,mr.title,mr.singer,mr.song_path,mr.lyrics,`user`.profile,`user`.email,`user`.token from duet,mr,`user`
    where duet.mr_idx=mr.idx and duet.email=user.email and `user`.leaveCheck=duet.userLeaveCheck 
    and mr.title like '%$searchInput%' or mr.singer like '%$searchInput%'
    GROUP BY duet.idx
    order by duet.idx asc";
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
            "token" => $row['token'],
            "userLeaveCheck" => $row['userLeaveCheck']
        );
        array_push($outputData,$dataColumn);
    
        }
  
    } else {
        echo "SQL문 처리중 에러 발생2 : ";
    }

    $response['duetList']=$outputData;
    echo json_encode($response, JSON_UNESCAPED_SLASHES); 

mysqli_close($conn);


}else{
    // songPost
    $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
        songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
    mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token
    from songPost,mr,`user`
    where (songPost.mr_idx=mr.idx and songPost.email=user.email) and songPost.userLeaveCheck=user.leaveCheck
    and (mr.title like '%$searchInput%' or mr.singer like '%$searchInput%')
    group by songPost.idx
    order by songPost.idx asc";

    $result = mysqli_query($conn, $sql);
    $rowCnt = mysqli_num_rows($result);

    $sql3="select songPostLike.email as isLike from songPost,songPostLike 
            where songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail' and songPostLike.leaveCheck='0'";
    $result3 = mysqli_query($conn, $sql3);
    $rowCnt3 = mysqli_num_rows($result3);
  
    $response = array();
    $badgeList = array();

    //밷지
    $sql4 = "select * from `badge`";
    $result4 = mysqli_query($conn,$sql4);
    $rowCnt4= mysqli_num_rows($result4);
    if($rowCnt4!=0){

    for($i=0;$i<$rowCnt4;$i++){
        $row4= mysqli_fetch_array($result4,MYSQLI_ASSOC);
        $badgeListColumn = array(
            "email" => $row4['email'],
        );
            array_push($badgeList,$badgeListColumn);
    }
    }else{
        $badgeList ="";
    }
    $response["badgeList"] = $badgeList; 

    $outputData2 = array();
    
    if ($result) {
        for ($j = 0; $j < $rowCnt; $j++) {
    
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user  
            where `user`.email = '$row[collabo_email]' and $row[collaborationLeaveCheck]=`user`.leaveCheck";
            $result2 = mysqli_query($conn, $sql2);
            $rowCnt2 = mysqli_num_rows($result2);
            $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        
            $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
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
                "userLeaveCheck" => $row['userLeaveCheck'],
                "isLike" => $row3['isLike'],
                "col_profile" => $row2['col_profile'],
                "col_token" => $row2['col_token'],
                "collaborationLeaveCheck" => $row['collaborationLeaveCheck']
            
            );
            array_push($outputData2,$dataColumn);
        
            }
      
        } else {
            echo "SQL문 처리중 에러 발생2 : ";
        }
    
        $response['homeList']=$outputData2;
        echo json_encode($response, JSON_UNESCAPED_SLASHES); 
    
    mysqli_close($conn);

 

}

?>