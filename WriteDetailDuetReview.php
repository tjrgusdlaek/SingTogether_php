<?php 
include "mysqlConnect.php";

    $detailDuetIdx=$_POST['detailDuetIdx']; 
    $uploadUserEmail=$_POST['uploadUserEmail']; 
    $uploadUserProfile=$_POST['uploadUserProfile']; 
    $uploadUserNickname=$_POST['uploadUserNickname']; 
    $review=$_POST['review']; 
    $uploadDate=$_POST['uploadDate']; 

    $response = array();
    $response["result"] = false;

    
    $sql = "select `cnt_reply` from `duet` where `idx` = '$detailDuetIdx' "; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    $rowCnt= mysqli_num_rows($result);

    $reviewNumbers = $row['cnt_reply'];
    $reviewNumbers_int =  (int)$reviewNumbers;
    $reviewNumbers_int++;
    $updateReviewNumbers = (String)$reviewNumbers_int;

    $sql="update `duet` set `cnt_reply` ='$updateReviewNumbers' where `idx` = '$detailDuetIdx'";
    $result =mysqli_query($conn, $sql); 

    $sql="insert into `detailDuetReview`(`detailDuetIdx`,`uploadUserEmail`,`uploadUserProfile`,`uploadUserNickname`,`review`,`uploadDate`)
    values('$detailDuetIdx','$uploadUserEmail','$uploadUserProfile','$uploadUserNickname','$review','$uploadDate')";
    $result =mysqli_query($conn, $sql); 

    if($result){ 


        $sql ="select `idx` from `detailDuetReview` where `uploadUserEmail` = '$uploadUserEmail'  and `review` = '$review'";
        $result =mysqli_query($conn, $sql); 
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);

        $response["result"] = true;
        $response["idx"] =$row['idx'];
        echo json_encode($response);
    }else{
        echo json_encode($response);
    }

    mysqli_close($conn);



?>