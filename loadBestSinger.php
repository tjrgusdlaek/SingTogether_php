<?php 
include "mysqlConnect.php";
// $conn = dbconn();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

//$today_date=$_POST['today_date'];
//$today_date=date("Y-m-d",strtotime("+1 days"));
$today_date="2021-10-26";
$userEmail=$_POST['email'];

$solo="솔로";
$duet="듀엣";

$response = array();
$outputData = array();

$sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.is_with,
    mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
    from mr,`user`,songPost LEFT JOIN songPostLike 
    ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
    where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date' and songPost.is_with= '듀엣'
    order by songPost.cnt_like desc
    LIMIT 1" ; 


    // $sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user where songPost.collabo_email=user.email";

    $result = mysqli_query($conn, $sql);
    $rowCnt = mysqli_num_rows($result);
    // $result2 = mysqli_query($conn, $sql2);

    
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
            "isLike" => $row['isLike'],
            "is_with" => $row['is_with'],
            "col_profile" => $row2['col_profile'],
            "col_token" => $row2['col_token'],
           
        );
        array_push($outputData,$dataColumn);
    
        }
  
    } else {
        echo "SQL문 처리중 에러 발생1 : ";
    }



    $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
    songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.is_with,
    mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
    from mr,`user`,songPost LEFT JOIN songPostLike 
    ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
    where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date' and songPost.is_with= '솔로'
    order by songPost.cnt_like desc
    LIMIT 1" ; 


    // $sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user where songPost.collabo_email=user.email";

    $result = mysqli_query($conn, $sql);
    $rowCnt = mysqli_num_rows($result);
    // $result2 = mysqli_query($conn, $sql2);
    if ($result) {
        for ($j = 0; $j < $rowCnt; $j++) {
    
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $sql2 ="select user.profile as col_profile, user.token as col_token from `user` where `email` = '$row[collabo_email]'";
            $result2 = mysqli_query($conn, $sql2);
            $rowCnt2 = mysqli_num_rows($result2);  
            $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
            // $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
            //$row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
       
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

                "profile" => $row['profile'], //?????????? 

                "collaboration" => $row['collaboration'],
                "collabo_email" => $row['collabo_email'],
                "kinds" => $row['kinds'],
                "mr_idx" => $row['mr_idx'],
                "title" => $row['title'],
                "singer" => $row['singer'],
                "lyrics" => $row['lyrics'],
                "token" => $row['token'],
                "isLike" => $row['isLike'],
                "is_with" => $row['is_with'],
                "col_profile" => $row2['col_profile'],
                "col_token" => $row2['col_token'],
               
            );
            array_push($outputData,$dataColumn);
        
            }
      
        } else {
            echo "SQL문 처리중 에러 발생2 : ";
        }
    

        $response['outputData'] = $outputData ; 


        echo json_encode($response);


// $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
//         songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.is_with,
//     mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.circle_profile,`user`.token,songPostLike.email as isLike
//     from mr,`user`,songPost LEFT JOIN songPostLike 
//     ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
//     where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date'
//     order by songPost.cnt_like asc
//     LIMIT 1";


// $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
//         songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.is_with,
//     mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.circle_profile,`user`.token,songPostLike.email as isLike
//     from mr,`user`,songPost LEFT JOIN songPostLike 
//     ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
//     where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date'
//     and songPost.is_with='$solo'
//     order by songPost.cnt_like desc
//     LIMIT 2";

//     $sql3 = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
//         songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,songPost.is_with,
//     mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.circle_profile,`user`.token,songPostLike.email as isLike
//     from mr,`user`,songPost LEFT JOIN songPostLike 
//     ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
//     where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date'
//     and songPost.is_with='$duet'
//     order by songPost.cnt_like desc
//     LIMIT 2";



// $sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,
//         songPost.cnt_like,songPost.email,songPost.nickname,
//         songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
//          mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.circle_profile,`user`.token
//         from songPost,mr,`user`
//         where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date' 
//         and songPost.with='$solo'
//         order by songPost.cnt_like desc
//         LIMIT 1";

// $sql2 ="select user.profile as col_profile from songPost,user where songPost.collabo_email=user.email";

// $sql3 = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,
//         songPost.cnt_like,songPost.email,songPost.nickname,
//         songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
//          mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.circle_profile,`user`.token
//         from songPost,mr,`user`
//         where songPost.mr_idx=mr.idx and songPost.email=user.email and songPost.date='$today_date' 
//         and songPost.with='$duet'
//         order by songPost.cnt_like desc
//         LIMIT 1";

// $sql4 ="select user.profile as col_profile from songPost,user where songPost.collabo_email=user.email";

// $result = mysqli_query($conn, $sql);
// $rowCnt = mysqli_num_rows($result);

// $result3 = mysqli_query($conn, $sql3);
// $rowCnt3 = mysqli_num_rows($result3);


// $result2 = mysqli_query($conn, $sql2);
// $rowCnt2 = mysqli_num_rows($result2);
// $outputData = array();

// $outputData = array();







// if ($result) {
//     for ($i = 0; $i < $rowCnt; $i++) {
//         $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

//         $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

//         $arr[$i] = $row+$row2;
//     }

//     $outputData = $arr;
//     $jsonData = json_encode($outputData, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
//     echo $jsonData;

// } else {
//     echo "SQL문 처리중 에러 발생 : ";
//     echo mysqli_error($conn);
// }

mysqli_close($conn);

?>