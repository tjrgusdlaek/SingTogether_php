<?php 
include "mysqlConnect.php";

    $replayIdx=$_POST['replayIdx']; 
    $uploadUserEmail=$_POST['uploadUserEmail']; 
    $uploadUserProfile=$_POST['uploadUserProfile']; 
    $uploadUserNickname=$_POST['uploadUserNickname']; 
    $review=$_POST['review']; 
    $uploadDate=$_POST['uploadDate']; 
    $PostedUserEmail=$_POST['PostedUserEmail']; 


    
    $response = array();
    $response["result"] = false;


    $sql = "select * from `replayPostLike` where `userEmail` ='$PostedUserEmail' and `replayPostIdx` ='$replayIdx'";
    $result = mysqli_query($conn,$sql);
    $rowCnt= mysqli_num_rows($result);
    
    if($rowCnt <1){
        $response["isLiked"] =  false;
    }else{
        $response["isLiked"] = true;   
    }

    
    $sql = "select `replayReviewNumber` from `replayPost` where `idx` = '$replayIdx' "; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    $rowCnt= mysqli_num_rows($result);

    $reviewNumbers = $row['replayReviewNumber'];
    $reviewNumbers_int =  (int)$reviewNumbers;
    $reviewNumbers_int++;
    $updateReviewNumbers = (String)$reviewNumbers_int;

    $sql="update `replayPost` set `replayReviewNumber` ='$updateReviewNumbers' where `idx` = '$replayIdx'";
    $result =mysqli_query($conn, $sql); 

    $sql="insert into `replayPostReview`(`replayIdx`,`uploadUserEmail`,`uploadUserProfile`,`uploadUserNickname`,`review`,`uploadDate`)
    values('$replayIdx','$uploadUserEmail','$uploadUserProfile','$uploadUserNickname','$review','$uploadDate')";
    $result =mysqli_query($conn, $sql); 

    if($result){ 


        $sql ="select `idx` from `replayPostReview` where `uploadUserEmail` = '$uploadUserEmail'  and `review` = '$review'";
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