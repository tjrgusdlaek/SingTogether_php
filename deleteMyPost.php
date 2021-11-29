<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $idx=$_POST['idx'];


    $sql="delete from `songPost`  where `idx` = '$idx'";

    $result =mysqli_query($conn, $sql); 

    if($result){  

        $sql2="select duet_idx from `duetPost` where songPost_idx='$idx'";
        $res2=mysqli_query($conn,$sql2);
        $row2=mysqli_fetch_array($res2);

        $duet_idx=$row2['duet_idx'];

        $query="update `duet` set cnt_duet=cnt_duet-1 where idx='$duet_idx'";
        $conn->query($query);
        
        $response["result"] = true;
        echo json_encode($response);

    }else{
        echo json_encode($response);
    }




    // $result =mysqli_query($conn, $sql); 

    //     if($result){  

    //         $response["result"] = true;
    //         echo json_encode($response);

    //     }else{
    //         echo json_encode($response);
    //     }

mysqli_close($conn);
?>