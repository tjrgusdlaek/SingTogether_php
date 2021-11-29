<?php 
include "mysqlConnect.php";

    $myEmail=$_POST['myEmail']; //이메일 받아옴 

    $response = array();
    $followingList = array();
    $badgeList = array();
    
    $response['result']= false; 
    $sql = "select `followingId` from `userFollowAndFollowing` where `userId` = '$myEmail'"; 
    $result = mysqli_query($conn,$sql);
    $rowCnt= mysqli_num_rows($result);
    
    if($result){ 

        if($rowCnt===0){
            $response['outputData'] = "";
            $response["badgeList"] =  "";
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
        }
    }else{

    }
$outputData = array();

    foreach((array) $followingList as  $key => $value){

 
        // $response["key_$key"] = $key; 

        $sql ="select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
            songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
        mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
        from mr,`user`,songPost LEFT JOIN songPostLike 
        ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$myEmail'
        where songPost.mr_idx=mr.idx and songPost.email=user.email and `user`.email = '$value[followingId]'";

    

        $result = mysqli_query($conn, $sql);
        $rowCnt = mysqli_num_rows($result);

        if ($result) {
            $response['result']= true; 
            for ($j = 0; $j < $rowCnt; $j++) {


                if(count($outputData) >3){
                    break; 
                }
        

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $sql2 ="select user.profile as col_profile, user.token as col_token from `user` where `email` = '$row[collabo_email]'";
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
                "col_profile" => $row2['col_profile'],
                "col_token" => $row2['col_token']
            );
            array_push($outputData,$dataColumn);
            


            }

        } else {
            echo "SQL문 처리중 에러 발생2 : ";
        }

      

    }
 


        //밷지
        $sql3 = "select `email` from `badge`";
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
    $response['outputData'] = $outputData;
    echo json_encode($response);

mysqli_close($conn);



?>