<?php
include('dbconn.php');
$conn = dbconn();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = array();

$file = $_FILES['uploaded_file'];
$srcName= $file['name'];
$tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp

$mr_idx=$_POST['mr_idx'];
$thumbnail="http://3.35.236.251/image/ZZZZZaa.PNG";
$cnt_play="0";
$cnt_reply="0";
$nickname=$_POST['nickname'];
$cnt_duet="0";
$date=date("Y-m-d",time());


//임시 저장소 이미지를 원하는 폴더로 이동
$dstName= "user_audio/".date('Ymd_his').$srcName;
$result=move_uploaded_file($tmpName, $dstName);

if($result){
    //$sql="insert into `video`(`path`) values('$dstName')";

    $sql="insert into `duet`(thumbnail,mr_idx,cnt_play,cnt_reply,nickname,cnt_duet,duet_path,date)
            values('$thumbnail','$mr_idx','$cnt_play','$cnt_reply','$nickname',
            '$cnt_duet','$dstName','$date')";
    $res =mysqli_query($conn, $sql);
    $response["sql"] = $sql;
    $response["path"] = $dstName;
    echo json_encode($response);

} else {
    $fileError = $_FILES['uploaded_file']["error"];
    switch ($fileError) {
        case UPLOAD_ERR_INI_SIZE: // Exceeds max size in php.ini
            break;
        case UPLOAD_ERR_PARTIAL: // Exceeds max size in html form
            break;
        case UPLOAD_ERR_NO_FILE: // No file was uploaded
            break;
        case UPLOAD_ERR_NO_TMP_DIR: // No /tmp dir to write to
            break;
        case UPLOAD_ERR_CANT_WRITE: // Error writing to disk
            break;
        default: // No error was faced! Phew!
            break;
    }
    echo json_encode($fileError);

}

mysqli_close($conn);
?>