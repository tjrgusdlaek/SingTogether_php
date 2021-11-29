<?php
$db_con = mysqli_connect("database-2.c1syd5qqy4xb.ap-northeast-2.rds.amazonaws.com", "root", "zxzx3709!");
if ($db_con){
  echo "DB 연결 성공<p>";
} else {
  echo "DB 연결 실패<p>";
}
  
$db_sec = mysqli_select_db($db_con, "summer_jang");
if ($db_sec) {
  echo "DB select OK <p>";
} else {
  echo "DB select NO <p>";
}

?>