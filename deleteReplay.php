<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $replayIdx=$_POST['replayIdx'];


    $sql="delete from `replayPost`  where `idx` = '$replayIdx'";
    $result =mysqli_query($conn, $sql); 

        if($result){  

            $sql="delete from `replayPostReview`  where `replayIdx` = '$replayIdx'";
            $result =mysqli_query($conn, $sql);
            
            $sql="delete from `replayPostLike`  where `replayPostIdx` = '$replayIdx'";
            $result =mysqli_query($conn, $sql); 

            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>