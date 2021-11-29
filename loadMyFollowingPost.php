<?php 
include "mysqlConnect.php";

    $myEmail=$_POST['myEmail']; //이메일 받아옴 

    $response = array();
    $badgeList = array();
    $followingList = array();


    $response['result']= false; 
    $sql = "select `followingId` from `userFollowAndFollowing` where `userId` = '$myEmail'"; 
    $result = mysqli_query($conn,$sql);
    $rowCnt= mysqli_num_rows($result);
    
    if($result){ 

        if($rowCnt===0){

            $response["badgeList"] = ""; 
            $response['outputData'] = "";
            echo json_encode($response);
            return;
        }else{
            for($i=0;$i<$rowCnt;$i++){
                $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
                $userColumn = array(
                    "followingId" => $row['followingId']
                );
                array_push($followingList,$userColumn);
            }
        
            // $response['followingList'] = $followingList;
        }
    }else{
        // echo "SQL문 처리중 에러 발생 : ";
    }
$outputData = array();

    foreach((array) $followingList as  $value){
        $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
        mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
        from mr,`user`,songPost LEFT JOIN songPostLike 
        ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
        where songPost.mr_idx=mr.idx and songPost.email=user.email and `user`.email = '$value[followingId]'
        ";
        $sql2 ="select user.profile as col_profile, user.token as col_token,user.leaveCheck as collaborationLeaveCheck from songPost,user where songPost.collabo_email=user.email";


        $result = mysqli_query($conn, $sql);
        $rowCnt = mysqli_num_rows($result);
        $result2 = mysqli_query($conn, $sql2);
        $rowCnt2 = mysqli_num_rows($result2);
     
        if ($result) {
            $response['result']= true; 
        for ($j = 0; $j < $rowCnt; $j++) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        // $arr[$j] = $row+$row2;
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
            "col_profile" => $row2['col_profile'],
            "col_token" => $row2['col_token'],
            "isLike" => $row['isLike'],
            "collaborationLeaveCheck" => $row2['collaborationLeaveCheck']
        );
        array_push($outputData,$dataColumn);
         
        }

        } else {
            echo "SQL문 처리중 에러 발생2 : ";
        }
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
  
    $response['outputData'] = $outputData;
    echo json_encode($response);

mysqli_close($conn);



?>