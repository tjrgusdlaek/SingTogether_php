<?php
include('dbconn.php');
$conn = dbconn();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
* 병합한 녹음 영상 db에 삽입 하는 파일
* 
* 1. 클라이언트로부터 post 데이터 값 받기
* 2. db 에 insert - songPost 테이블에 삽입
* 3. duetPost 테이블에 삽입
* 4. duet 테이블에 cnt_duet 값에 +1 해준다
*/

// 1. 클라이언트로부터 post 데이터 값 받기
$mr_idx=$_POST['mr_idx'];
$duet_idx=$_POST['duet_idx'];
$thumbnail_path=$_POST['thumbnail_path']; 
$output_path=$_POST['output_path']; 
$nickname=$_POST['nickname'];
$email=$_POST['email'];
$collaboration=$_POST['collaboration_nickname'];
$collabo_email=$_POST['collabo_email'];
$kinds=$_POST['kinds'];
$cnt_play="0";
$cnt_like=0;
$cnt_reply="0";
$date=date("Y-m-d",time());
$with="듀엣";

// 2. db 에 insert - songPost 테이블에 삽입
$sql="insert into `songPost`(thumbnail,mr_idx,email,nickname,song_path,cnt_play,cnt_like,cnt_reply,date,collaboration,collabo_email,kinds,is_with)
values('$thumbnail_path','$mr_idx','$email','$nickname','$output_path','$cnt_play','$cnt_like','$cnt_reply',
'$date','$collaboration','$collabo_email','$kinds','$with')";
$res =mysqli_query($conn, $sql);

$songPost_idx=mysqli_insert_id($conn);

// 3. duetPost 테이블에 삽입 - insert
if($res){
    $sql2="insert into `duetPost`(duet_idx,songPost_idx,original_email,collaboration_email) values('$duet_idx','$songPost_idx','$email','$collabo_email')";
    $res2=mysqli_query($conn,$sql2);
}

// 4. duet 테이블에 cnt_duet 값에 +1 해준다 - update
if($res2){
    $sql3="update `duet` set cnt_duet=cnt_duet+1 where idx='$duet_idx'";
    $res3=mysqli_query($conn,$sql3);
}

$response = array();
$response["sql"] = $sql;
echo json_encode($response);

mysqli_close($conn);

?>