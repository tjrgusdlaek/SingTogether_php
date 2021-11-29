<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $userEmail=$_POST['userEmail'];


    $sql="delete from `userFollowAndFollowing`  where `userId` = '$userEmail' or `followingId`= '$userEmail'";
    $result =mysqli_query($conn, $sql); 

    $sql="update `replayPost` set `userLeaveCheck` ='1' , `uploadUserFCMToken` = 'exit'  where `uploadUserEmail` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 

    $sql="update `songPost` set `userLeaveCheck` ='1'  where `email` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 

    $sql="update `duet` set `userLeaveCheck` ='1'  where `email` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 

    $sql="update `songPost` set `collaboUserLeaveCheck` ='1'  where `collabo_email` = '$userEmail'";
    $result =mysqli_query($conn, $sql);

    $sql="update `badge` set `leaveCheck` ='1' where `email` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 

    $sql="update `replayPostLike` set `leaveCheck` ='1' where `userEmail` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 
    
    $sql="update `songPostLike` set `leaveCheck` ='1' where `email` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 

    // $sql="delete from `user`  where `email` = '$userEmail'";
    $sql="update `user` set `leaveCheck` ='1' , `token` = 'exit' ,`nickname` ='' where `email` = '$userEmail'";
    $result =mysqli_query($conn, $sql); 

        if($result){  

            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>