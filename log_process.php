<?php

@$db_con = mysql_connect('localhost','root','autoset');
@mysql_select_db('andb',$db_con);

$query = "select * from user";
$query_r = mysql_query($query);
$loginstatus=false;
$ID = $_POST['ID'];
$PW = $_POST['PW'];
while($result = mysql_fetch_array($query_r)){
    $id = $result['id'];
    $pw = $result['password'];
    if($ID==$id && $PW==$pw){
        session_start();
        $_SESSION['userid'] = $result['userid'];
        $_SESSION['name'] = $result['name'];
        $_SESSION['age']= $result['age'];
        $loginstatus=true;
    }
}
if($loginstatus==false){
    setcookie('nandemo22','1',time()+10);
}
if(isset($_POST['log_out'])){
    session_destroy();
    setcookie('nandemo2ya','1',time()+10);
}
header("Location: http://localhost/2rphp/listup.php");

?>