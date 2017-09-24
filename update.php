<?php
@$db_con=mysql_connect('localhost','root','autoset');
@mysql_select_db('andb',$db_con);
//INSERT와 UPDATE의 공통적 사용 변수
if(isset($_POST['subject']) && isset($_POST['contents'])&&isset($_GET['delete'])==false){
    $subject= $_POST['subject']; $contents= $_POST['contents']; $update = $_POST['update'];
    $date = date('Y-m-d H:i:s');
}
//INSERT문
if(strlen($update)==0 && isset($_GET['delete'])==false){
   $query = "insert into board (user_id, subject, contents, hits, reg_date) ";
   $query.= " values ('$_SESSION[name]','$subject','$contents','0','$date')";
   mysql_query($query);
}
//UPDATE문
else if(strlen($update)!=0 &&isset($_GET['delete'])==false){
    $board_id = $_POST['board_id'];
    $query = "update board set subject='$subject',contents='$contents',reg_date='$date' where board_id=$board_id";
    mysql_query($query);
}
//DELETE문
if(isset($_GET['delete'])){
    $board_id=$_GET['board_id'];
    $query = "delete from board where board_id=$board_id";
    mysql_query($query);
}
//작업다하면 리스트로 돌아감
header("Location: http://localhost/2rphp/listup.php");
mysql_close($db_con);
?>