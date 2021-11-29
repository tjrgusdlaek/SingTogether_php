<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $idx=$_POST['idx'];

    $sql = "select `cnt_play` from `songPost` where `idx` = '$idx' "; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);

    $hits = $row['cnt_play'];
    $hits_int =  (int)$hits;
    $hits_int++;
    $updateHits = (String)$hits_int;

    
    $sql="update `songPost` set `cnt_play` ='$updateHits' where `idx` = '$idx'";
    $result =mysqli_query($conn, $sql); 

        if($result){  
            $response["sql"] = $sql;
            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>