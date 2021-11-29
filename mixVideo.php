<?php
    include('dbconn.php');
    $conn = dbconn();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

/*
    녹화한 비디오 믹싱하는 파일

 * 1. 녹화한 비디오 파일 업로드
 * 2. 녹화한 비디오에서 사용자오디오 추출
 * 3. 추출한 사용자 오디오 + 노래 MR 오디오 병합
 * 4. 병합한 오디오 + 녹화한 비디오 병합
 * 5. 4번에서 완성된 비디오 url을 클라이언트로 전달 - AfterRecordActivity.kt
 * 
*/

$ffmpeg = "/usr/bin/ffmpeg";  // 전역변수

// 1. 녹화한 비디오 파일 업로드
// post로 클라이언트에서 보내온 비디오 파일 받기
$file = $_FILES['uploaded_file'];
$srcName= $file['name'];
$tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
$mr_path=$_POST['mr_path']; // mr path
$side=$_POST['side']; // 전면,후면 구분
$date=date("Y-m-d",time());

//임시 저장소 이미지를 원하는 폴더로 이동
$dstName= "user_video/".date('Ymd_his').$srcName; // 서버에 업로드 되는 비디오 파일 이름
$result=move_uploaded_file($tmpName, $dstName); // 파일 이동


if($result) {
   // 2. 녹화한 비디오에서 사용자오디오 *추출*
    $extractName=uniqid().".m4a";
    $extractFile = "./song_post/".$extractName;
    //ffmpeg -i /var/www/html/user_audio/20210915_04223820210915_132230.mp4 -map "0:a" -c "copy" /var/www/html/user_audio/extarct.m4a

    $cmd="$ffmpeg -i $dstName -map 0:a -c copy $extractFile";
    shell_exec($cmd);
    $extract_path="song_post/".$extractName;
 

} else {
    // 오류났을때 처리해주기
}



// 3. 추출한 사용자 오디오 + 노래 MR *오디오 병합*
//ffmpeg -i  /var/www/html/user_audio/extarct.m4a -i /var/www/html/test_audio/duetsong.m4a -filter_complex "[0][1]amix=inputs=2,pan=stereo|FL<c0+c1|FR<c2+c3[a]" -map "[a]" -y /var/www/html/user_audio/mergeMR.m4a

$FileExtist = file_exists("/var/www/html/song_post/".$extractName);
if($FileExtist) {
    $mergeName=uniqid().".m4a";
    $mergeAudio="./song_post/".$mergeName;
    //$cmd2="$ffmpeg -i $extractFile -i $mr_path -filter_complex '[0][1]amix=inputs=2,pan=stereo|FL<c0+c1|FR<c2+c3[a]' -map '[a]' -y $mergeAudio";
    
    $cmd2="ffmpeg -i $mr_path -i $extractFile -shortest -filter_complex '[0:a]volume=0.5[a0]; [1:a]adelay=1000|1000,volume=3.5[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mergeAudio";
    shell_exec($cmd2);
}

// 4. 병합한 오디오 + 녹화한 *비디오 병합*
//ffmpeg -i /var/www/html/user_audio/20210915_04223820210915_132230.mp4 -i /var/www/html/user_audio/mergeMR.m4a -c copy -shortest  -map "0:v:0" -map "1:a:0" -y /var/www/html/user_audio/finalOutput.mp4

$mergeFileExtist = file_exists("/var/www/html/song_post/".$mergeName);
if($mergeFileExtist) {
    $mergeVideoName=uniqid().".mp4";
    $mergeVideo="./user_video/".$mergeVideoName;
    $cmd3="$ffmpeg -i $dstName -i $mergeAudio -c copy -shortest  -map '0:v:0' -map '1:a:0' -y $mergeVideo";
    shell_exec($cmd3);
    $output_path="user_video/".$mergeVideoName;
    $finish_path="user_video/".$mergeVideoName;
   
}

// 전면으로 찍은 동영상이면 좌우반전 시켜주기
//ffmpeg -i input.mp4 -vf "hflip" /var/www/html/test_audio/hflip.mp4
if($side=="front") {
    $flipVideoName="flip".uniqid().".mp4";
    $flipVideo="./user_video/".$flipVideoName;
    $cmd4="$ffmpeg -i $output_path -vf 'hflip' $flipVideo";
    shell_exec($cmd4);
    $finish_path="user_video/".$flipVideoName;

}

// 클라이언트로 전달해야할거 - 1.추출한오디오파일path , 2.최종완성된 비디오파일주소
// json방식으로 보내주기
$response = array();
$response["extract_path"]=$extract_path;
$response["finish_path"] =$finish_path;

echo json_encode($response, JSON_UNESCAPED_SLASHES); 

?>