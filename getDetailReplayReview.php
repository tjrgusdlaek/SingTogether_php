<?php 
include "mysqlConnect.php";

    $replayIdx=$_POST['replayIdx']; //idx 받아옴 

    
$response = array();
// $response["result"] = false;

$sql = "select * from `replayPostReview` where `replayIdx` = '$replayIdx' "; // DB에 데이터가 있나 체크
$result = mysqli_query($conn,$sql);
$rowCnt= mysqli_num_rows($result);


if($result){ 

    // $response["result"] = true;   
    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result,MYSQLI_ASSOC);
        $response[$i]= $row;
    }
    echo json_encode($response);

}else{
     echo json_encode($response);
}

mysqli_close($conn);



?>