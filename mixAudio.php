<?php
    include "mysqlConnect.php";
/*
    녹음한 오디오 믹싱하는 파일

 * 1. 녹음한 오디오 파일 업로드
 * 2. 녹음한 오디오 + 노래 MR 오디오 병합
 * 3. 사용자 프로필사진.png 으로 mp4 파일 만들기
 * 4. 2번에서 병합한 오디오 + 3번에서 만든 mp4 병합
 * 5. 4번에서 완성된 비디오 url을 클라이언트로 전달 
 * 
*/

// ffmpeg -i /var/www/html/test_audio/duetsong.m4a -i /var/www/html/song_post/merge6143977b726a5.m4a -shortest -filter_complex \
// "[0:a]volume=0.5[a0]; \
//  [1:a]adelay=1000|1000,volume=3.5[a1]; \
//  [a0][a1]amix=inputs=2[out]" \
//  -map "[out]" -ac 2 -c:a aac /var/www/html/test_audio/adjust4.m4a

$ffmpeg = "/usr/bin/ffmpeg"; 
// 1. 녹음한 오디오 파일 업로드
$file = $_FILES['uploaded_file'];
$srcName= $file['name'];
$tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
$mr_path=$_POST['mr_path']; // mr path
$email=$_POST['email'];// 이메일 -> 3번때문에 필요
$date=date("Y-m-d",time());

//원하는 폴더로 이동
$dstName= "user_video/".date('Ymd_his').$srcName; // 서버에 업로드 되는 오디오 파일 이름
$result=move_uploaded_file($tmpName, $dstName); // 파일 이동


if($result) {
   //2. 녹음한 오디오 + 노래 MR 오디오 병합
   $mergeName="mixAudio".uniqid().".m4a";
   $mergeAudio="/var/www/html/test_audio/".$mergeName;
    $cmd="ffmpeg -i $mr_path -i $dstName -shortest -filter_complex '[0:a]adelay=500|500,volume=0.5[a0]; [1:a]volume=3.5[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mergeAudio";
    shell_exec($cmd);
    $mix_path="test_audio/".$mergeName;

    // $cmd="$ffmpeg -i $dstName -map 0:a -c copy $extractFile";
    
}

$mergeFileExtist = file_exists("/var/www/html/test_audio/".$mergeName);
if($mergeFileExtist) {

 // 3. use테이블에서 circle_profile 가져오기 -> 썸네일 
$sql ="SELECT circle_profile from user where email='$email' and user.leaveCheck='0'";
$res=mysqli_query($conn,$sql);
$row=mysqli_fetch_array($res);
$sql_result=$row['circle_profile'];

}

// 4. 2번에서 완성된 비디오 url,3번 db를 클라이언트로 전달 
$response = array();
$response["mix_path"]=$mix_path;
$response["extract_path"]=$dstName;
$response["circle_profile"] =$sql_result;

echo json_encode($response, JSON_UNESCAPED_SLASHES); 

?>