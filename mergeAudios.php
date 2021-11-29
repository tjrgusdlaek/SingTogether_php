<?php
header("Content-type: image/png");
include "mysqlConnect.php";

/**
 * 듀엣 오디오 두개 병합하는 파일
 * 
 * 1. post 로 클라이언트에서 데이터받기 ( 본인이 녹음한 avi 파일, mr_path, 병합하고자하는 extract_path, 원본 사용자 email ,콜라보 사용자 email)
 * 2. 병합하고자하는 오디오랑 본인이 녹음한 오디오끼리 병합
 * 3. 2번에서 병합한 오디오랑 mr 이랑 병합
 * 4. use테이블에서 각각 circle_profile 가져오기
 * 5. 받은 2개의 이메일값으로 동그란프로필 merge 
 * 6. 클라이언트에 최종오디오path, 4번에서 merge한 썸네일사진 path 전달
 * 
 */

$ffmpeg = "/usr/bin/ffmpeg";  // 전역변수
// 1. post 로 클라이언트에서 데이터받기 
// ( 본인이 녹음한 m4a 파일, mr_path, 병합하고자하는 extract_path, 원본 사용자 email ,콜라보 사용자 email)
$file = $_FILES['uploaded_file'];
$srcName= $file['name'];
$tmpName= $file['tmp_name'];
$mr_path=$_POST['mr_path'];
$mergeExtractPath=$_POST['merge_extract_path']; // 병합하고자하는 오디오 extract_path
$original_email=$_POST['original_email'];
$collaboration_email=$_POST['collaboration_email'];
$date=date("Y-m-d",time());

//원하는 폴더로 이동
$dstName= "user_video/".date('Ymd_his').$srcName; // 서버에 업로드 되는 오디오 파일 이름
$result=move_uploaded_file($tmpName, $dstName); // 파일 이동

if($result){
    //2. 병합하고자하는 오디오랑 본인이 녹음한 오디오끼리 병합
    $mergeName="mergeAudio".uniqid().".m4a";
    $mergeAudio="./song_post/".$mergeName;
    //$cmd="ffmpeg -i $dstName -i $mergeExtractPath -filter_complex '[0][1]amix=inputs=2,pan=stereo|FL<c0+c1|FR<c2+c3[a]' -map '[a]' -y $mergeAudio";
    $cmd="ffmpeg -i $dstName -i $mergeExtractPath  -shortest -filter_complex '[0:a]volume=1.0[a0]; [1:a]adelay=600|600,volume=1.0[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mergeAudio";
    shell_exec($cmd);
}

// 3. 2번에서 병합한 오디오랑 mr 이랑 병합
$mrMergeFileExtist = file_exists("/var/www/html/song_post/".$mergeName);
if($mrMergeFileExtist) {
    $mrMergeName="mrMergeAudio".uniqid().".m4a";
    $mrMergeAudio="./song_post/".$mrMergeName;
    //$cmd2="ffmpeg -i $mr_path -i $mergeAudio -shortest -filter_complex '[0:a]adelay=950|950,volume=0.5[a0]; [1:a]volume=3.5[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mrMergeAudio";
     $cmd2="ffmpeg -i $mr_path -i $mergeAudio -shortest -filter_complex '[0:a]adelay=1300|1300,volume=0.5[a0]; [1:a]volume=3.5[a1]; [a0][a1]amix=inputs=2[out]' -map '[out]' -ac 2 -c:a aac $mrMergeAudio";
    shell_exec($cmd2);

    $output_path="song_post/".$mrMergeName;
}


$mergeFileExtist = file_exists("/var/www/html/song_post/".$mrMergeName);
if($mergeFileExtist) {

 // 4. use테이블에서 각각 circle_profile 가져오기
$sql ="SELECT circle_profile from user where email='$original_email' and user.leaveCheck='0'";
$res=mysqli_query($conn,$sql);
$row=mysqli_fetch_array($res);
$original_circle=$row['circle_profile'];

$sql2 ="SELECT circle_profile from user where email='$collaboration_email' and user.leaveCheck='0'";
$res2=mysqli_query($conn,$sql2);
$row2=mysqli_fetch_array($res2);
$collabo_circle=$row2['circle_profile'];

}

if(isset($collabo_circle) ){
// 5. 받은 2개의 이메일값으로 동그란프로필 merge 
$img1_path = '/var/www/html/'.$original_circle;
$img2_path = '/var/www/html/'.$collabo_circle;

list($img1_width, $img1_height) = getimagesize($img1_path);
list($img2_width, $img2_height) = getimagesize($img2_path);

$merged_width  = $img1_width + $img2_width;
//get highest
$merged_height = $img1_height > $img2_height ? $img1_height : $img2_height;

$merged_image = imagecreatetruecolor($merged_width, $merged_height);

imagealphablending($merged_image, true);
imagesavealpha($merged_image, true);

$img1 = imagecreatefrompng($img1_path);
$img2 = imagecreatefrompng($img2_path);

imagecopy($merged_image, $img1, 0, 0, 0, 0, $img1_width, $img1_height);
//place at right side of $img1
imagecopy($merged_image, $img2, $img1_width, 0, 0, 0, $img2_width, $img2_height);

//save file or output to broswer
$SAVE_AS_FILE = TRUE;
if( $SAVE_AS_FILE ){
    $save_name="mergeImage".uniqid().".png";
    $save_path = "/var/www/html/test_audio/".$save_name;
    imagepng($merged_image,$save_path);
    $thumbnail_path="test_audio/".$save_name;
}else{
    header('Content-Type: image/png');
    imagepng($merged_image);
}

//release memory
imagedestroy($merged_image);
}



// 6. 클라이언트에 최종오디오path, 4번에서 merge한 썸네일사진 path 전달
$response = array();
$response["output_path"] =$output_path;
$response["merge_thumbnail_path"] =$thumbnail_path;

echo json_encode($response, JSON_UNESCAPED_SLASHES); 

?>