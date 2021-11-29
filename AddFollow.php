<?php
include "mysqlConnect.php";

$response = array();
$response["result"] = false;
$followingUser=$_POST['followingUser'];
$follower=$_POST['follower'];


        $sql="insert into `userFollowAndFollowing`(`userId`,`followingId`)
        values('$followingUser','$follower')";
        $result =mysqli_query($conn, $sql);

        if($result){
            $response["sql"] = $sql;
            $response["result"] =true;
            echo json_encode($response);
    
        }
        
  
mysqli_close($conn);

?>