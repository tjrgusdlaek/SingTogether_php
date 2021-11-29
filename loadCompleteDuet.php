<?php
// 듀엣 완성된 포스팅 불러오는 파일
include('dbconn.php');
$conn = dbconn();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$duet_idx=$_POST['duet_idx'];

$sql="select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
        mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token
        from songPost,mr,`user`,duetPost
        where songPost.mr_idx=mr.idx and songPost.email=user.email and duetPost.duet_idx='$duet_idx' and duetPost.songPost_idx=songPost.idx 
        and duetPost.collaboration_email=songPost.collabo_email";

 $sql2 ="select user.profile as col_profile from songPost,user where songPost.collabo_email=user.email";

// $sql="select songPost.idx,songPost.thumbnail,songPost.mr_idx,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,
//         songPost.email,songPost.nickname,songPost.song_path,songPost.date,
//         songPost.collaboration,songPost.collabo_email,songPost.kinds
//         from songPost,duetPost
//         where duetPost.duet_idx='$duet_idx' and duetPost.songPost_idx=songPost.idx 
//         and duetPost.collaboration_email=songPost.collabo_email";

        $result = mysqli_query($conn, $sql);
        $rowCnt = mysqli_num_rows($result);

        $result2 = mysqli_query($conn, $sql2);
        $rowCnt2 = mysqli_num_rows($result2);
        
        $outputData = array();
        if ($result) {
            for ($i = 0; $i < $rowCnt; $i++) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                //각 각의 row를 $arr에 추가
                $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
                $arr[$i] = $row+$row2;
            }
        
            $outputData = $arr;
            $jsonData = json_encode($outputData, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
            echo $jsonData;
        
        } else {
            echo "SQL문 처리중 에러 발생 : ";
            echo mysqli_error($conn);
        }
        
        mysqli_close($conn);

?>