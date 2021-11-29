<?php 
include "mysqlConnect.php";

    $myEmail=$_POST['email']; //이메일 받아옴 
    $year=$_POST['year'];
    
    $response = array();

    // $dateString = date("Y-m-d", time());

    $sql ="SELECT *  FROM `badge`  WHERE `year`=(
        SELECT max(date) FROM badge) limit 1 ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $maxYear = $row['year'];


    if($maxYear < $year){
    $sql ="SELECT `email` FROM `badge` where `year` ='$year'";
    $result = mysqli_query($conn,$sql);
    $rowCnt= mysqli_num_rows($result);
        if($rowCnt ===0){
 

            $sql =
            "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
            songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
            songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
            mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
            from mr,`user`,songPost LEFT JOIN songPostLike 
            ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$myEmail' and songPostLike.leaveCheck='0'
            where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.userLeaveCheck=user.leaveCheck
            and songPost.date like '%$year%' and songPost.is_with='듀엣'
            LIMIT 1";
            $result = mysqli_query($conn, $sql);
         
            if ($result) {
                $response['result']= true; 
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                    $email = $row['email'];
                    $collabo_email = $row['collabo_email'];
                    
                    $sql="insert into `badge`(`email`,`year`) values ('$email','$year' )";
                    $result =mysqli_query($conn, $sql); 
                    
                    $sql="insert into `badge`(`email`,`year`) values ('$collabo_email','$year' )";
                    $result =mysqli_query($conn, $sql); 

                } else {
                    echo "SQL문 처리중 에러 발생2 : ";
                }


            $sql =
            "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
            songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
            songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
            mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
            from mr,`user`,songPost LEFT JOIN songPostLike 
            ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$myEmail' and songPostLike.leaveCheck='0'
            where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.userLeaveCheck=user.leaveCheck
            and songPost.date like '%$year%' and songPost.is_with='솔로'
            LIMIT 1";
            $result = mysqli_query($conn, $sql);
         
            if ($result) {
                $response['result']= true; 
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $email = $row['email'];

                    $sql="insert into `badge`(`email`,`year`) values ('$email','$year' )";
                    $result =mysqli_query($conn, $sql); 
   

                } else {
                    echo "SQL문 처리중 에러 발생2 : ";
                }
   
        }
    }



    $sql ="SELECT *  FROM `songPost`  WHERE `date`=(
        SELECT min(date) FROM songPost) limit 1 ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $minYear = $row['date'];
    $minYearexplode = explode('-', $minYear );
    $getMinYear = $minYearexplode[0];


    $DuetDataList = array();
    $SoloDataList = array();
      
        $sql =
        "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
        songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
        mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
        from mr,`user`,songPost LEFT JOIN songPostLike 
        ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$myEmail' and songPostLike.leaveCheck='0'
        where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.userLeaveCheck=user.leaveCheck
        and songPost.date like '%$year%' and songPost.is_with='듀엣'
        LIMIT 1";

    //     $sql ="select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
    //     songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
    // mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike,
    // rank() over (order by songPost.cnt_like desc) as ranking
    // from mr,`user`,songPost LEFT JOIN songPostLike 
    // ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$myEmail'
    // where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date like '%$year%' and songPost.is_with='듀엣'
    // order by ranking asc
    // LIMIT 1";
    // $sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user where songPost.collabo_email=user.email";

   

        $result = mysqli_query($conn, $sql);
        $rowCnt = mysqli_num_rows($result);
        // $result2 = mysqli_query($conn, $sql2);
        // $rowCnt2 = mysqli_num_rows($result2);
     
        if ($result) {
            $response['result']= true; 
                for ($j = 0; $j < $rowCnt; $j++) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);


                //$sql2 ="select user.profile as col_profile, user.token as col_token from `user` where `email` = '$row[collabo_email]'";

                
         $sql2 ="select user.profile as col_profile, user.token as col_token from `user`
         where `email` = '$row[collabo_email]' and $row[collaborationLeaveCheck]=`user`.leaveCheck";

                $result2 = mysqli_query($conn, $sql2);
                $rowCnt2 = mysqli_num_rows($result2);  
                $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
              
                $duetDataColumn = array(
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
                    "collaborationLeaveCheck" => $row['collaborationLeaveCheck'],
                    "col_profile" => $row2['col_profile'],
                    "col_token" => $row2['col_token'],
                    "isLike" => $row['isLike']
                );
                array_push($DuetDataList,$duetDataColumn);
                
                }
                } else {
                    echo "SQL문 처리중 에러 발생2 : ";
                }

                $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
                songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.userLeaveCheck,
                songPost.collaboUserLeaveCheck as collaborationLeaveCheck,
                mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
                from mr,`user`,songPost LEFT JOIN songPostLike 
                ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$myEmail' and songPostLike.leaveCheck='0'
                where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.userLeaveCheck=user.leaveCheck
                and songPost.date like '%$year%' and songPost.is_with='솔로'
                LIMIT 1
                ";
    
        
                // $sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user where songPost.collabo_email=user.email";

                $result = mysqli_query($conn, $sql);
                $rowCnt = mysqli_num_rows($result);
                // $result2 = mysqli_query($conn, $sql2);
                // $rowCnt2 = mysqli_num_rows($result2);
             
                if ($result) {
                    $response['result']= true; 
                        for ($j = 0; $j < $rowCnt; $j++) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        //$sql2 ="select user.profile as col_profile, user.token as col_token from `user` where `email` = '$row[collabo_email]'";
                        $sql2 ="select user.profile as col_profile, user.token as col_token from `user`
                        where `email` = '$row[collabo_email]' and $row[collaborationLeaveCheck]=`user`.leaveCheck";
                        $result2 = mysqli_query($conn, $sql2);
                        $rowCnt2 = mysqli_num_rows($result2);  
                        
                        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
                      
                        $soloDataColumn = array(
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
                            "collaborationLeaveCheck" => $row['collaborationLeaveCheck'],
                            "col_profile" => $row2['col_profile'],
                            "col_token" => $row2['col_token'],
                            "isLike" => $row['isLike']
                        );
                        array_push($SoloDataList,$soloDataColumn);
                        
                        }
                        } else {
                            echo "SQL문 처리중 에러 발생2 : ";
                        }


    $response['getMinYear'] = $getMinYear;
    $response['DuetDataList'] = $DuetDataList;
    $response['SoloDataList'] = $SoloDataList;
    echo json_encode($response);

mysqli_close($conn);



?>