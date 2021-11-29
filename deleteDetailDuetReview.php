<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $idx=$_POST['idx'];
    $detailDuetIdx=$_POST['detailDuetIdx'];

    $sql = "select `cnt_reply` from `duet` where `idx` = '$detailDuetIdx'";
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
 

    $reviewNumbers = $row['cnt_reply'];
    $reviewNumbers_int =  (int)$reviewNumbers;
    if($reviewNumbers_int !==0){
        $reviewNumbers_int--;
        $updateReviewNumbers = (String)$reviewNumbers_int;
        $sql="update `duet` set `cnt_reply` ='$updateReviewNumbers' where `idx` = '$detailDuetIdx'";
        $result =mysqli_query($conn, $sql); 
    }

 

    $sql="delete from `detailDuetReview`  where `idx` = '$idx'";
    $result =mysqli_query($conn, $sql); 

        if($result){  
            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>