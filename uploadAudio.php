<?php
/**
 * 녹음한 오디오 업로드하는 파일
 * 솔로,듀엣 구분해서 각각 songPost, duet테이블에 삽입하기
 */
include('dbconn.php');
$conn = dbconn();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// post값 받기
$idx=$_POST['mr_idx'];
$thumbnail=$_POST['thumbnail_path'];
$song_path=$_POST['song_path'];
$extract_path=$_POST['extract_path'];
$nickname=$_POST['nickname'];
$email=$_POST['email'];
$kinds=$_POST['kinds'];
$with=$_POST['with'];

$cnt_play="0";
$cnt_reply="0";
$cnt_duet=0;
$cnt_like=0;
$date=date("Y-m-d",time());
$collaboration=$_POST['nickname'];


if($with=="솔로"){
    // songPost 테이블에 넣기
    $sql="insert into `songPost`(thumbnail,mr_idx,email,nickname,song_path,cnt_play,cnt_like,cnt_reply,date,collaboration,collabo_email,kinds,is_with)
    values('$thumbnail','$idx','$email','$nickname','$song_path','$cnt_play','$cnt_like','$cnt_reply',
    '$date','$collaboration','$email','$kinds','$with')";
    $res =mysqli_query($conn, $sql);
    $response = array();
    $response["sql"] = $sql;
    echo json_encode($response);
    
}else{
    // duet 테이블에 넣기
    $sql="insert into `duet`(thumbnail,mr_idx,cnt_play,cnt_reply,email,nickname,cnt_duet,duet_path,date,extract_path,kinds)
    values('$thumbnail','$idx','$cnt_play','$cnt_reply','$email','$nickname',
    '$cnt_duet','$song_path','$date','$extract_path','$kinds')";
    $res =mysqli_query($conn, $sql);
    $response = array();
    $response["sql"] = $sql;
    echo json_encode($response);
}


mysqli_close($conn);

?>