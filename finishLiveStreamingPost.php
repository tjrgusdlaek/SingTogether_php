<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $roomIdx=$_POST['roomIdx'];


    $sql="delete from `liveStreamingPost` where `idx` = '$roomIdx'";
    $result =mysqli_query($conn, $sql); 

        if($result){  
            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>