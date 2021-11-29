<?php
header('Content-Type: application/json; charset=utf8');

$host = 'database-2.c1syd5qqy4xb.ap-northeast-2.rds.amazonaws.com';
$user = 'root';
$pw = 'zxzx3709!';
$dbName = 'summer_jang';
$mysqli=new mysqli($host,$user,$pw,$dbName);
$conn = mysqli_connect($host,$user,$pw,$dbName);

$response = array();

    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $social=$_POST['social'];
    $token=$_POST['token'];
    $file = $_FILES['uploaded_file'];

    $srcName= $file['name'];
    $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp


//임시 저장소 이미지를 원하는 폴더로 이동
    $dstName= "uploadFile/".date('Ymd_his').$srcName;
    $result=move_uploaded_file($tmpName, $dstName);

    if($result){
        
        $image_s=imagecreatefromstring(file_get_contents($dstName));
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

        $circleName="circle".uniqid().".png";
        imagepng($image,"./uploadFile/".$circleName); //경로에 생성
        $dbCircleName="uploadFile/".$circleName;
        imagedestroy($iamge);
        imagedestroy($mask);


         $sql="insert into `user`(`email`,`nickname`,`social`,`token`,`profile`,`circle_profile`)
                values('$email','$nickname','$social','$token','$dstName','$dbCircleName')";
                // $sql="insert into `user`(`email`,`nickname`,`social`,`token`,`profile`)
                // values('$email','$nickname','$social','$token','$dstName')";
            $res =mysqli_query($conn, $sql);
        
       
            $response["sql"] = $sql;
            $response["profile"] = $dstName;
   
        
            echo json_encode($response);
        } else{
           
        }


mysqli_close($conn);

?>