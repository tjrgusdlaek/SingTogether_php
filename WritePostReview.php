<?php 
include "mysqlConnect.php";

    $postIdx=$_POST['postIdx']; 
    $uploadUserEmail=$_POST['uploadUserEmail']; 
    $uploadUserProfile=$_POST['uploadUserProfile']; 
    $uploadUserNickname=$_POST['uploadUserNickname']; 
    $review=$_POST['review']; 
    $uploadDate=$_POST['uploadDate']; 

    $response = array();
    $response["result"] = false;

    
    $sql = "select `cnt_reply` from `songPost` where `idx` = '$postIdx' "; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    $rowCnt= mysqli_num_rows($result);

    $reviewNumbers = $row['cnt_reply'];
    $reviewNumbers_int =  (int)$reviewNumbers;
    $reviewNumbers_int++;
    $updateReviewNumbers = (String)$reviewNumbers_int;

    $sql="update `songPost` set `cnt_reply` ='$updateReviewNumbers' where `idx` = '$postIdx'";
    $result =mysqli_query($conn, $sql); 

    $sql="insert into `mainPostReview`(`postIdx`,`uploadUserEmail`,`uploadUserProfile`,`uploadUserNickname`,`review`,`uploadDate`)
    values('$postIdx','$uploadUserEmail','$uploadUserProfile','$uploadUserNickname','$review','$uploadDate')";
    $result =mysqli_query($conn, $sql); 

    if($result){ 


        $sql ="select `idx` from `mainPostReview` where `uploadUserEmail` = '$uploadUserEmail'  and `review` = '$review'";
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