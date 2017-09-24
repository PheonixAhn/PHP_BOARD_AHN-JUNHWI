<?php
@$db_con = mysql_connect('localhost','root','autoset');
@mysql_select_db('andb',$db_con);
//댓글 달기 
if(isset($_POST['reple_up'])==false && isset($_GET['reple_del'])==false){
    $reple = $_POST['reple'];
    $board_id = $_POST['b_id'];
    $date_now = date('Y-m-d H:i:s');
    $insert = "insert into reple (user_id,contents,reg_date,board_id,update_date) values ('$_SESSION[name]','$reple','$date_now','$board_id','$date_now')";
   $insert_q = mysql_query($insert);
}
//댓글 수정
else if(isset($_POST['reple_up'])&&isset($_GET['reple_del'])==false) {
    $contents = $_POST['reple'];
    $board_id = $_POST['b_id'];
    $reple_id = $_POST['reple_up'];
    $date_now = date('Y-m-d H:i:s');
    $query = "update reple set contents='$contents',update_date='$date_now' where board_id=$board_id AND reple_id=$reple_id";
    mysql_query($query);
}
//댓글 삭제
if(isset($_GET['reple_del'])){
    $board_id = $_GET['board_id'];
    $reple_id = $_GET['reple_id'];
    $delete ="delete from reple where board_id=$board_id AND reple_id=$reple_id";
    mysql_query($delete);
}


//delete



header("Location: http://localhost/2rphp/read.php?board_id=$board_id");



mysql_close();
?>