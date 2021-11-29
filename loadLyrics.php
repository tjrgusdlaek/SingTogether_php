<?php
// 가사 시간 불러오기
    include('dbconn.php');
    $conn = dbconn();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $mr_idx=$_POST['mr_idx'];

    //$sql="select * from lyricsInfo where mr_idx='10'";
    $sql="select * from lyricsInfo where mr_idx='$mr_idx'";

    $result=mysqli_query($conn,$sql);
    $rowCnt=mysqli_num_rows($result);

    $outputData = array();
    if($result){
        for($i=0 ; $i < $rowCnt ; $i++){
            $row= mysqli_fetch_array($result, MYSQLI_ASSOC);
            //각 각의 row를 $arr에 추가
            $arr[$i]= $row;
        }

        $outputData= $arr;
        $jsonData=json_encode($outputData, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
        echo $jsonData;

    }else {
        echo "SQL문 처리중 에러 발생 : ";
        echo mysqli_error($conn);
    }

    mysqli_close($conn);
?>