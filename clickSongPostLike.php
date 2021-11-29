<?php
include('dbconn.php');
$conn = dbconn();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// songPost 게시물 좋아요 기능 
/**
 * 1. post 값 받고
 * 2. 좋아요면 songPostLike 에 값 집어넣어주고 좋아요취소면 들어간 값 삭제한다
 * 3. songPostLike 테이블에 songPost_idx 와 email 이 같은 값이 존재하면 그 idx 값 삭제한다
 * 4. 좋아요면 songPost 에 있는 cnt_like 에 +1 로 update 해준다
 * 5. 마찬가지로 좋아요취소하면 cnt_likie 에 -1 로 update 해준다
 * 6. 클라이언트에 songPost에있는 cnt_like값 전달
 */

//$isLike=$_POST['like']; // 좋아요 인지 좋아요 취소인지 확인하는 변수
$songPost_idx=$_POST['songPost_idx'];
$email=$_POST['email'];
$songPost_email=$_POST['songPost_email'];
$date=date("Y-m-d",time());

$sql="select idx as count from `songPostLike` where songPost_idx='$songPost_idx' and email='$email'";
$result=mysqli_query($conn,$sql);
//$row=mysqli_fetch_array($result);
//$cnt=$row['count'];
$rowCnt=mysqli_num_rows($result);

if($rowCnt>0) {
// 이미 좋아요를 누른 경우 기존 좋아요를 삭제한다
// songPost 테이블에서 cnt_like -1 로 update 해준다
// 총 cnt_like수 전달

//$sql4="delete from `songPostLike` where songPost_idx='$songPost_idx'";
$sql4="delete from `songPostLike` where songPost_idx='$songPost_idx' and email='$email'";
$res4=$conn->query($sql4);

$query="update `songPost` set cnt_like=cnt_like-1 where idx='$songPost_idx'";
$conn->query($query);

$sql5="select cnt_like from `songPost` where idx='$songPost_idx'";
$res5=mysqli_query($conn,$sql5);
$row5=mysqli_fetch_array($res5);

$total_like=$row5['cnt_like'];

$response = array();
$response["cnt_like"] =$total_like;
$response["isLike"] ="false";

$jsonData=json_encode($response, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
echo $jsonData;



}else {
// songPostLike 테이블에 insert해주고
// 같은 songPost_idx 인 songPost 테이블 가져와서 cnt_like +1 로 update 해준다
// 총 cnt_like수 전달
$sql2="insert into `songPostLike`(email,songPost_idx,date) values ('$email','$songPost_idx','$date')";
$res=$conn->query($sql2);

$query="update `songPost` set cnt_like=cnt_like+1 where idx='$songPost_idx'";
$conn->query($query);

$sql3="select cnt_like from `songPost` where idx='$songPost_idx'";
$res2=mysqli_query($conn,$sql3);
$row2=mysqli_fetch_array($res2);

$total_like2=$row2['cnt_like'];

$response2 = array();
$response2["cnt_like"] =$total_like2;
$response2["isLike"] ="true";

$jsonData2=json_encode($response2, JSON_UNESCAPED_UNICODE); //json배열로 만들어짐.
echo $jsonData2;


}



?>