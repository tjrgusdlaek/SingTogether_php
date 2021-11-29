<?php
header("Content-type: image/png");
// http://3.35.236.251/uploadFile/20210916_045438JPEG_20210916_045437.jpg

$input="http://3.35.236.251/uploadFile/20210916_045438JPEG_20210916_045437.jpg";

$filename="./uploadFile/20210916_045438JPEG_20210916_045437.jpg";
$image_s=imagecreatefromstring(file_get_contents($filename));
$width=imagesx($image_s);
$height=imagesy($image_s);

$newwidth=200;
$newheight=200;

$image=imagecreatetruecolor($newwidth,$newheight);
imagealphablending($image,true);
imagecopyresampled($image,$image_s,0,0,0,0,$newwidth,$newheight,$width,$height);

$mask=imagecreatetruecolor($newwidth,$newheight);

$transparent=imagecolorallocate($mask,255,0,0);
imagecolortransparent($mask,$transparent);

imagefilledellipse($mask,$newwidth/2,$newheight/2,$newwidth,$newheight,$transparent);
$red=imagecolorallocate($mask,0,0,0);

imagecopymerge($image,$mask,0,0,0,0,$newwidth,$newheight,100);
imagecolortransparent($image,$red);
imagefill($image,0,0,$red);

//imagepng($image); 화면에 생성
imagepng($image,"./test_audio/output6.png"); //경로에 생성
imagedestroy($iamge);
imagedestroy($mask);


?>