<?php

include "mysqlConnect.php";

/**
* 병합한 녹화 영상 db에 삽입 하는 파일
* 
* 1. 썸네일 추출
* 2. db 에 insert - 'duet' 테이블에 추출한오디오도 삽입하기
*/

// POST 값 받기
$mr_idx=$_POST['mr_idx'];
$duet_idx=$_POST['duet_idx'];
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


/**
* 1. 썸네일 추출
*/
$ffmpeg = "/usr/bin/ffmpeg";
$size = "270x180";

$imageName="thumbnail".uniqid().".png";
$imageFile = "./uploadFile/".$imageName;
$getFromSecond =3;
$cmd = "$ffmpeg -i $output_path -ss $getFromSecond -s $size $imageFile";
shell_exec($cmd);

$thumbnail="http://3.35.236.251/uploadFile/$imageName";

// 2. db 에 insert

$FileExtist = file_exists("/var/www/html/uploadFile/".$imageName);
if($FileExtist) {

$with="듀엣";
//email : 업로드하는 사람 이메일
//collabo_email : 듀엣먼저올려놓은 사람 이메일
   $sql="insert into `songPost`(thumbnail,mr_idx,email,nickname,song_path,cnt_play,cnt_like,cnt_reply,date,collaboration,collabo_email,kinds,is_with)
   values('$thumbnail','$mr_idx','$email','$nickname','$output_path','$cnt_play','$cnt_like','$cnt_reply',
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


}else{
echo "error";
}

mysqli_close($conn);

?>