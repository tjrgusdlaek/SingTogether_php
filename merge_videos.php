<?php
require './ffmpeg/vendor/autoload.php';

include('dbconn.php');
$conn = dbconn();
error_reporting(E_ALL);
ini_set('display_errors', 1);

function merge($additionalInputFiles)
{

    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open('http://3.35.236.251/user_audio/20210907_07070920210907_160706.mp4');
    $video->addFilter(new customFilter($additionalInputFiles));
    $video->save(new \FFMpeg\Format\Video\X264(), 'testoutput.mp4');
    return $video;
}
$additionalInputFiles="http://3.35.236.251/user_audio/20210903_04373020210903_133727.mp4";
merge($additionalInputFiles)

//
//
//function get_video_duration($file_full_path)
//{
//    $file_full_path = trim($file_full_path);
//    if (file_exists($file_full_path) == TRUE) {
//        $cmd = 'ffprobe -i ' . $file_full_path . ' -show_entries format=duration -v quiet -of csv="p=0"';
//        $duration = floor(shell_exec($cmd));
//        echo $duration;
//        return $duration;
//
//    } else {
//        echo "안돼";
//        return FALSE;
//
//    }
//}
//$file_full_path="http://3.35.236.251/user_audio/20210907_07070920210907_160706.mp4";
//
//get_video_duration($file_full_path);

?>