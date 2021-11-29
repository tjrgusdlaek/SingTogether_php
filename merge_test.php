<?php

/**
 * 썸네일 추출
*/
//$ffmpeg = "/usr/bin/ffmpeg";
//$size = "270x180";
//
//$videoFile = 'http://3.35.236.251/user_audio/20210903_04373020210903_133727.mp4';
//    $imageFile = './image/thumbnail.png';
//$getFromSecond =2;
//$cmd = "$ffmpeg -i $videoFile -ss $getFromSecond -s $size $imageFile";
//    shell_exec($cmd);

/**
 * 비디오 병합
 */

$ffmpeg = "/usr/bin/ffmpeg";
$videoFile = 'http://3.35.236.251/user_audio/20210903_04373020210903_133727.mp4';
$addFile = 'http://3.35.236.251/user_audio/20210907_07070920210907_160706.mp4';

$outputFile='./test_audio/mergeVideo10.mp4';
//$cmd = "$ffmpeg -i $videoFile -i $addFile -filter_complex -map '[0:v]pad=iw*2:ih[int];[int][1:v]overlay=W/2:0[vid]' -map [vid] -c:v libx264 -shortest -y -crf 23 -preset veryfast $outputFile";

//$cmd="$ffmpeg -i concat:".$videoFile|$addFile." $outputFile";

// 파일은 만들어지는데 빈파일이다
//$cmd="$ffmpeg -i $videoFile -i $addFile -filter_complex '[0:v]pad=iw*2:ih[int];[int][1:v]overlay=W/2:0[vid]' -map [vid] -c:v libx264 -crf 23 -preset veryfast $outputFile";

// 파일은 만들어지는데 빈파일이다
//$cmd="$ffmpeg -i $videoFile -i $addFile -filter_complex '[0:v][1:v]hstack=inputs=2[v]; [0:a][1:a]amerge[a]' -map '[v]' -map '[a]' -ac 2 $outputFile";

//$cmd="$ffmpeg -i $videoFile -i $addFile -filter_complex '[1:v][0:v]scale2ref[wm][base];[base][wm]hstack=2' $outputFile";

//$cmd="$ffmpeg -i $videoFile -i $addFile -filter_complex '[0:v]pad=iw*2:ih[int];[int][1:v]overlay=W/2:0[vid]' -map '[vid]' -c:v libx264 -crf 23 -preset veryfast $outputFile";

$cmd="$ffmpeg -i $videoFile -i $addFile -filter_complex '[0:v][1:v]hstack=inputs=2[v]; [0:a][1:a]amerge[a]' -map '[v]' -map '[a]' -ac 2 $outputFile";


shell_exec($cmd);


?>