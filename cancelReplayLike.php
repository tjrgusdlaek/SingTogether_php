<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $ReplayIdx=$_POST['ReplayIdx'];
    $userEmail=$_POST['userEmail'];
    $replayPostLikeIdx=$_POST['replayPostLikeIdx'];
    
    $sql = "select `replayLikeNumber` from `replayPost` where `idx` = '$ReplayIdx' "; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    

    $replayLikeNumber = $row['replayLikeNumber'];
    $replayLikeNumber_int =  (int)$replayLikeNumber;
    $replayLikeNumber_int--;
    $updatereplayLikeNumber = (String)$replayLikeNumber_int;
    $sql="update `replayPost` set `replayLikeNumber` ='$updatereplayLikeNumber' where `idx` = '$ReplayIdx'";
    $result =mysqli_query($conn, $sql); 

        if($result){  

            $sql="delete from `replayPostLike`  where `idx` = '$replayPostLikeIdx'";
            $result =mysqli_query($conn, $sql); 

            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>