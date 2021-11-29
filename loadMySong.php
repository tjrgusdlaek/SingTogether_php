<?php

include "mysqlConnect.php";

$userEmail=$_POST['email'];


$sql = "select songPost.idx,songPost.thumbnail,songPost.cnt_play,songPost.cnt_reply,songPost.cnt_like,songPost.email,songPost.nickname,
        songPost.song_path,songPost.date,songPost.collaboration,songPost.collabo_email,songPost.kinds,
    mr.idx as mr_idx ,mr.title,mr.singer,mr.lyrics,`user`.profile,`user`.token,songPostLike.email as isLike
    from mr,`user`,songPost LEFT JOIN songPostLike 
    ON songPost.idx=songPostLike.songPost_idx and songPostLike.email='$userEmail'
    where songPost.mr_idx=mr.idx and songPost.email=user.email and user.email = '$userEmail'
    ";

$sql2 ="select user.profile as col_profile, user.token as col_token from songPost,user where songPost.collabo_email=user.email";

$result = mysqli_query($conn, $sql);
$rowCnt = mysqli_num_rows($result);

$result2 = mysqli_query($conn, $sql2);
$rowCnt2 = mysqli_num_rows($result2);

$outputData = array();

if ($result) {
    for ($i = 0; $i < $rowCnt; $i++) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //각 각의 row를 $arr에 추가
        //$arr[$i] = $row;
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

