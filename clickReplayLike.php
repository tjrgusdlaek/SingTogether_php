<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $ReplayIdx=$_POST['ReplayIdx'];
    $userEmail=$_POST['userEmail'];
    $clickDate=$_POST['clickDate'];
    $PostedUserEmail=$_POST['PostedUserEmail'];


    

    $sql = "select * from `replayPostLike` where `userEmail` ='$PostedUserEmail' and `replayPostIdx` ='$ReplayIdx'";
    $result = mysqli_query($conn,$sql);
    $rowCnt= mysqli_num_rows($result);
    
    if($rowCnt <1){
        $response["isLiked"] =  false;
    }else{
        $response["isLiked"] = true;   
    }


    

    $sql = "select `replayLikeNumber` from `replayPost` where `idx` = '$ReplayIdx' and `userEmail` ='$userEmail'"; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $rowCnt= mysqli_num_rows($result);
    
    if($rowCnt ==0){
    $sql = "select `replayLikeNumber` from `replayPost` where `idx` = '$ReplayIdx'"; // DB에 데이터가 있나 체크
    $result = mysqli_query($conn,$sql);
    $row= mysqli_fetch_array($result,MYSQLI_ASSOC);

    $replayLikeNumber = $row['replayLikeNumber'];
    $replayLikeNumber_int =  (int)$replayLikeNumber;
    $replayLikeNumber_int++;
    $updatereplayLikeNumber = (String)$replayLikeNumber_int;


 
  

    $sql="update `replayPost` set `replayLikeNumber` ='$updatereplayLikeNumber' where `idx` = '$ReplayIdx'";
    $result =mysqli_query($conn, $sql); 
        if($result){  
         
            $sql="insert into `replayPostLike`(`userEmail`,`replayPostIdx`,`clickDate`) values ('$userEmail','$ReplayIdx','$clickDate')";
            $result =mysqli_query($conn, $sql); 
            if($result){
                $sql="select `idx` from `replayPostLike` where `userEmail` = '$userEmail' and `replayPostIdx` = '$ReplayIdx' and `clickDate` = '$clickDate'";
                $result =mysqli_query($conn, $sql); 
                $row= mysqli_fetch_assoc($result);
                $response["row"] = $row;
                $response["sql"] = $sql;
                $response["idx"] = $row['idx'];
                $response["result"] = true;
                // echo json_encode($response);
            }else{

                // echo json_encode($response);
            }
        }else{
            // echo json_encode($response);
        }
    }else{
        $response["result"] = true;
        // echo json_encode($response);
    }

    // $sql ="SELECT `replayLikeNumber` FROM `replayPost` where `uploadUserEmail` ='$PostedUserEmail' and  `uploadDate` like '%2021%'";
    // $result = mysqli_query($conn,$sql);
    // $rowCnt= mysqli_num_rows($result);

    // $finalLike =0;
    // for ($j = 0; $j < $rowCnt; $j++) {
    //     $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    //     $replayLikeNumber = $row['replayLikeNumber'];
    //     $replayLikeNumber_int =  (int)$replayLikeNumber;
    //     $finalLike += $replayLikeNumber_int;
        
    // }

    // $sql ="SELECT `cnt_like` FROM `songPost` where `email` ='$PostedUserEmail' and  `date` like '%2021%'";
    // $result = mysqli_query($conn,$sql);
    // $rowCnt= mysqli_num_rows($result);
    // $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
  

    // for ($j = 0; $j < $rowCnt; $j++) {
    //     $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
    //     $cnt_like = $row['cnt_like'];
    //     $cnt_like_int =  (int)$cnt_like;
    //     $finalLike += $cnt_like_int;
    // }

    // $sql ="SELECT `email` FROM `badge` where `email` ='$PostedUserEmail'";
    // $result = mysqli_query($conn,$sql);
    // $rowCnt= mysqli_num_rows($result);
    // if($rowCnt ===0){
    //     if($finalLike >=100){

    //         $sql="insert into `badge`(`email`) values ('$PostedUserEmail')";
    //         $result =mysqli_query($conn, $sql); 
    //     }
    // }
 
    // $response["finalLike"] = $finalLike; 

    echo json_encode($response);
mysqli_close($conn);

?>