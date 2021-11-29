<?php
header('Content-Type: application/json; charset=utf8');

include "mysqlConnect.php";

    $response = array();
    $response["result"] = false;

    $followingUser=$_POST['followingUser'];
    $follower=$_POST['follower'];

    $sql="delete from `userFollowAndFollowing`  where `userId` = '$followingUser' and `followingId`= '$follower' ";
    $result =mysqli_query($conn, $sql); 

        if($result){  

            $response["result"] = true;
            echo json_encode($response);

        }else{
            echo json_encode($response);
        }

mysqli_close($conn);

?>