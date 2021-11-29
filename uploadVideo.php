<?php
     include('dbconn.php');
     $conn = dbconn();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


/**
 * 녹화 영상 db에 삽입 하는 파일
 * 
 * 1. 썸네일 추출
 * 2. db 에 insert - 'duet' 테이블에 추출한오디오도 삽입하기
 */

// POST 값 받기
$mr_idx=$_POST['mr_idx'];
$nickname=$_POST['nickname'];
$finish_path=$_POST['finish_path']; 
$extract_path=$_POST['extract_path']; 
$cnt_play="0";
$cnt_reply="0";
$cnt_duet="0";
$date=date("Y-m-d",time());


/**
 * 1. 썸네일 추출
*/
$ffmpeg = "/usr/bin/ffmpeg";
$size = "270x180";

$imageName=uniqid().".png";
$imageFile = "./uploadFile/".$imageName;
$getFromSecond =3;
$cmd = "$ffmpeg -i $finish_path -ss $getFromSecond -s $size $imageFile";
shell_exec($cmd);

$thumbnail="http://3.35.236.251/uploadFile/$imageName";

// 2. db 에 insert

$FileExtist = file_exists("/var/www/html/uploadFile/".$imageName);
if($FileExtist) {
        $sql="insert into `duet`(thumbnail,mr_idx,cnt_play,cnt_reply,nickname,cnt_duet,duet_path,date,extract_path)
        values('$thumbnail','$mr_idx','$cnt_play','$cnt_reply','$nickname',
        '$cnt_duet','$finish_path','$date','$extract_path')";
    $res =mysqli_query($conn, $sql);
    $response = array();
    $response["sql"] = $sql;
    echo json_encode($response);


}else{
   echo "error";
}

mysqli_close($conn);

?>