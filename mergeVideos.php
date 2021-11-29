<?php
    include "mysqlConnect.php";
/**
 * 듀엣 비디오 두개 병합하는 파일
 * 
 * 1. post 로 클라이언트에서 데이터받기 ( 본인이 녹화한 mp4파일, 병합하고자하는 영상 mp4, 병합하고자하는 오디오path)
 * 2. 본인이 녹화한비디오에서 오디오 추출
 * 3. 병합하고자하는 오디오랑 추출한 오디오끼리 병합
 * 4. 3번에서 병합한 오디오랑 mr 이랑 병합
 * 5. 병합하고자하는 비디오 mp4 랑 본인이 녹화한 mp4 랑 비디오끼리 병합 - avi
 * 6. avi 를 mp4 로 변환
 * 7. 변환한 mp4 랑 4번에서 병합한 오디오랑 병합 = 최종 비디오파일
 * 8. 클라이언트에 최종비디오 파일 path 전달
 * 
 */

$ffmpeg = "/usr/bin/ffmpeg";  // 전역변수
// 1. post 로 클라이언트에서 데이터받기 ( 본인이 녹화한 mp4파일, 병합하고자하는 비디오 path, 병합하고자하는 오디오path)
$file = $_FILES['uploaded_file'];
$srcName= $file['name'];
$tmpName= $file['tmp_name'];
$mr_path=$_POST['mr_path']; // mr path
$mergeVideoPath=$_POST['merge_video_path']; // 병합하고자하는 비디오 path
$mergeExtractPath=$_POST['merge_extract_path']; // 병합하고자하는 오디오path
$side=$_POST['side']; // 전면,후면 구분

//임시 저장소 이미지를 원하는 폴더로 이동
$dstName= "user_video/".date('Ymd_his')."yes".$srcName; // 서버에 업로드 되는 비디오 파일 이름
$result=move_uploaded_file($tmpName, $dstName); // 파일 이동

if($result) {
    // 2. 녹화한 비디오에서 사용자오디오 *추출*
     $extractName="extract".uniqid().date('Ymd_his').".m4a";
     $extractFile = "./song_post/".$extractName;
     $cmd="$ffmpeg -i $dstName -map 0:a -c copy $extractFile";
     shell_exec($cmd);
     $extract_path="song_post/".$extractName;
  
 } else {
     // 오류났을때 처리해주기
 }
 
// 3. 병합하고자하는 오디오랑 추출한 오디오끼리 병합
$FileExtist = file_exists("/var/www/html/song_post/".$extractName);
if($FileExtist) {
    $mergeName="merge".uniqid().date('Ymd_his').".m4a";
    $mergeAudio="./song_post/".$mergeName;
    //$cmd2="$ffmpeg -i $extractFile -i $mergeExtractPath -filter_complex '[0][1]amix=inputs=2,pan=stereo|FL<c0+c1|FR<c2+c3[a]' -map '[a]' -y $mergeAudio";
    $cmd2="$ffmpeg -i $extractFile -i $mergeExtractPath  -shortest -filter_complex '[0:a]adelay=200|200,volume=1.0[a0]; [1:a]volume=1.0[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mergeAudio";
    shell_exec($cmd2);
}

// 4. 3번에서 병합한 오디오랑 mr 이랑 병합
$mrMergeFileExtist = file_exists("/var/www/html/song_post/".$mergeName);
if($mrMergeFileExtist) {
    $mrMergeName="mrMerge".uniqid().date('Ymd_his').".m4a";
    $mrMergeAudio="./song_post/".$mrMergeName;
    //$cmd3="$ffmpeg -i $mergeAudio -i $mr_path -filter_complex '[0][1]amix=inputs=2,pan=stereo|FL<c0+c1|FR<c2+c3[a]' -map '[a]' -y $mrMergeAudio";
    $cmd3="$ffmpeg -i $mergeAudio -i $mr_path -shortest -filter_complex '[0:a]adelay=350|350,volume=3.5[a0]; [1:a]volume=0.5[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mrMergeAudio";
    shell_exec($cmd3);
}

// 전면으로 찍은 동영상이면 좌우반전 시켜주기
if($side=="front") {
    $flipVideoName="flip".uniqid().date('Ymd_his').".mp4";
    $flipVideo="./user_video/".$flipVideoName;
    $cmd7="$ffmpeg -i $dstName -vf 'hflip' $flipVideo";
    shell_exec($cmd7);
    $flip_path="user_video/".$flipVideoName;

    $videoFileExtist = file_exists("/var/www/html/song_post/".$mrMergeName);
    if($videoFileExtist) {
        $videoMergeName="videoMerge".uniqid().date('Ymd_his').".avi";
        $videoMergeFile="./user_video/".$videoMergeName;
        $cmd4="$ffmpeg -i $flipVideo -i $mergeVideoPath -filter_complex '[0:v][1:v]hstack=inputs=2:shortest=1[outv]' -map '[outv]' -movflags frag_keyframe+empty_moov $videoMergeFile";
        shell_exec($cmd4);
    }
    $convertFileExtist = file_exists("/var/www/html/user_video/".$videoMergeName);
    if($convertFileExtist) {
        $convertName="convert".uniqid().date('Ymd_his').".mp4";
        $convertFile="./user_video/".$convertName;
        $cmd5="$ffmpeg -y -i $videoMergeFile -ar 22050 -ab 512 -b 800k -f mp4 -s 514*362 -strict -2 -c:a aac $convertFile";
        shell_exec($cmd5);
    }

    // 7. 변환한 mp4 랑 4번에서 병합한 오디오랑 병합 = 최종 비디오파일
    $outputFileExtist = file_exists("/var/www/html/user_video/".$convertName);
    if($outputFileExtist) {
        $outputName="output".date('Ymd_his').".mp4";
        $outputFile="./user_video/".$outputName;
        $cmd6="$ffmpeg -i $convertFile -i $mrMergeAudio -c copy -shortest  -map '0:v:0' -map '1:a:0' -y $outputFile";
        shell_exec($cmd6);
        $output_path="user_video/".$outputName;
    }

}else {

    $videoFileExtist = file_exists("/var/www/html/song_post/".$mrMergeName);
    if($videoFileExtist) {
        $videoMergeName="videoMerge".uniqid().date('Ymd_his').".avi";
        $videoMergeFile="./user_video/".$videoMergeName;
        $cmd4="$ffmpeg -i $dstName -i $mergeVideoPath -filter_complex '[0:v][1:v]hstack=inputs=2:shortest=1[outv]' -map '[outv]' -movflags frag_keyframe+empty_moov $videoMergeFile";
        shell_exec($cmd4);
    }
    $convertFileExtist = file_exists("/var/www/html/user_video/".$videoMergeName);
    if($convertFileExtist) {
        $convertName="convert".uniqid().date('Ymd_his').".mp4";
        $convertFile="./user_video/".$convertName;
        $cmd5="$ffmpeg -y -i $videoMergeFile -ar 22050 -ab 512 -b 800k -f mp4 -s 514*362 -strict -2 -c:a aac $convertFile";
        shell_exec($cmd5);
    }

    // 7. 변환한 mp4 랑 4번에서 병합한 오디오랑 병합 = 최종 비디오파일
    $outputFileExtist = file_exists("/var/www/html/user_video/".$convertName);
    if($outputFileExtist) {
        $outputName="output".date('Ymd_his').".mp4";
        $outputFile="./user_video/".$outputName;
        $cmd6="$ffmpeg -i $convertFile -i $mrMergeAudio -c copy -shortest  -map '0:v:0' -map '1:a:0' -y $outputFile";
        shell_exec($cmd6);
        $output_path="user_video/".$outputName;
    }
}

// 5. 비디오 + 비디오 병합
//ffmpeg -i /var/www/html/user_audio/20210903_04373020210903_133727.mp4 -i /var/www/html/user_audio/20210907_07070920210907_160706.mp4 -filter_complex "[0:v][1:v]hstack=inputs=2:shortest=1[outv]" -map "[outv]" -movflags frag_keyframe+empty_moov /var/www/html/test_audio/longMerge.avi
// $videoFileExtist = file_exists("/var/www/html/song_post/".$mrMergeName);
// if($videoFileExtist) {
//     $videoMergeName="videoMerge".uniqid().date('Ymd_his').".avi";
//     $videoMergeFile="./user_video/".$videoMergeName;
//     $cmd4="$ffmpeg -i $dstName -i $mergeVideoPath -filter_complex '[0:v][1:v]hstack=inputs=2:shortest=1[outv]' -map '[outv]' -movflags frag_keyframe+empty_moov $videoMergeFile";
//     shell_exec($cmd4);
// }

// //6. avi 파일 mp4 로 변환
// $convertFileExtist = file_exists("/var/www/html/user_video/".$videoMergeName);
// if($convertFileExtist) {
//     $convertName="convert".uniqid().date('Ymd_his').".mp4";
//     $convertFile="./user_video/".$convertName;
//     $cmd5="$ffmpeg -y -i $videoMergeFile -ar 22050 -ab 512 -b 800k -f mp4 -s 514*362 -strict -2 -c:a aac $convertFile";
//     shell_exec($cmd5);
// }

// // 7. 변환한 mp4 랑 4번에서 병합한 오디오랑 병합 = 최종 비디오파일
// $outputFileExtist = file_exists("/var/www/html/user_video/".$convertName);
// if($outputFileExtist) {
//     $outputName="output".date('Ymd_his').".mp4";
//     $outputFile="./user_video/".$outputName;
//     $cmd6="$ffmpeg -i $convertFile -i $mrMergeAudio -c copy -shortest  -map '0:v:0' -map '1:a:0' -y $outputFile";
//     shell_exec($cmd6);
//     $output_path="user_video/".$outputName;
// }

// 8. 클라이언트로 url 전달
$response = array();
$response["output_path"] =$output_path;

echo json_encode($response, JSON_UNESCAPED_SLASHES); 

?>