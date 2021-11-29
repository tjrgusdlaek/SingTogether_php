<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $idx=$_POST['idx'];


    $sql="delete from `duet`  where `idx` = '$idx'";
    $result =mysqli_query($conn, $sql); 

        if($result){  

            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);
?>