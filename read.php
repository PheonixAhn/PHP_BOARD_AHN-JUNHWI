<html>
<head>
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery.mon.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script>
        function reple_check(rep_c){
            var cont;
            switch(rep_c){
                case 1: cont =document.getElementById('reple_1').value;  break;
                case 2: cont =document.getElementById('reple_2').value;  break;
            }
            if(cont.length==0){
                alert("댓글 내용을 입력해주세요^^");
            }
            else{
                if(cont.match(/\n/)){
                    cont = cont.replace(/\n/gi, "<br>");
                }
                document.getElementById('h_cont').value=cont;
                document.getElementById('formid').submit();
            }
        }
        function br_check(){
            var br_ck = document.getElementById('reple_2').value;
            if(br_ck.match("<br>")){
                br_ck = br_ck.replace("<br>",/\n/gi );
            }
            document.getElementById('reple_2').value=br_ck;
        }
    </script>
</head>
<body>
<?php
@$db_con = mysql_connect('localhost','root','autoset');
@mysql_select_db('andb',$db_con);
include 'log_in.php';

$board_id = $_GET['board_id'];
$query =  "select * from board where board_id = $board_id";
$query_r = mysql_query($query);
echo "<div style='margin-top:450px; margin-left:20px; width:600px; height:500px; border:1px solid silver;'>";
while($result = mysql_fetch_array($query_r)){
    echo "<div  width: 600px; style='font-size:15pt; color:#5b84e4;height:15px;'><b>".$result['subject']."</b></div><br>";
    echo "<div style='text-align:right; color:silver;'>작성자:$result[user_id] | 조회:$result[hits]</div><hr/>";
    echo "<div  width: 400px; style='height:400px;' '>".$result['contents']."</div><br>";
    $hits = $result['hits']+1;
    $query = "update board set hits=$hits where board_id = $board_id";
    mysql_query($query);
    $user_id = $result['user_id'];
}
echo "</div>";
echo "<div style=' background-color:silver; width:400px; height: 100px; position:absolute; top:500px; left:700px;'>";

if($_SESSION['name']==$user_id) {
    echo "<button class='btn-default' style='margin-left: 100px;'><a href='writemode.html?board_id=$board_id'>글수정</a></button>";
    echo "<a href='update.php?board_id=$board_id&delete=1'><input class='btn-default' type=button value='글삭제'></a>";
    echo "<button class='btn-default' ><a href='listup.php'>글목록</a></button>";
}else {
    echo "<button class='btn-default' style='margin-left: 170px;'><a href='listup.php'>글목록</a></button><br><br>";
}

//댓글
echo "<form id='formid' action='reple_process.php' method='post'>";
echo "<textarea id='reple_1' type='text' cols='30' rows='2' style='margin-top:10px; margin-left:80px; '></textarea>";
echo "<input name='b_id' type='text' style='display:none;'value='$board_id'>";
echo "<input class='btn btn-info' type='button' value='등록' style='margin-bottom:30px' onclick='reple_check(1)'><br><br>";
echo "<textarea name='reple' id='h_cont' cols='30' rows='10' style='display: none'></textarea>";
$count_reple = "select count(r.reple_id) from board b, reple r where b.board_id = r.board_id and b.board_id='$board_id'";
$count_reple_r = mysql_query($count_reple);
while($count_reple_result=mysql_fetch_row($count_reple_r))
echo "<div class='btn-danger' style='text-align:center; width:400px; height:25px; border-radius:10px;'>댓글($count_reple_result[0])</div>";

$query_reple = "select b.board_id, r.reple_id, r.user_id, r.contents, r.reg_date,r.update_date from board b, reple r where b.board_id = r.board_id and b.board_id='$board_id' order by reg_date";
$query_r_reple = mysql_query($query_reple);
while($reple_result = mysql_fetch_array($query_r_reple)){
    $reple_id = $reple_result['reple_id'];
    $reple_contents = $reple_result['contents'];
    echo "<span style='color:blue; font-size:12pt;'><b>$reple_result[user_id]</b></span><div style='border-radius:10px; margin-top:10px; margin-left:20px; word-wrap: break-word; background-color: #dadada'>";
  if(isset($_GET['reple_id'])&& $_GET['reple_id']==$reple_result['reple_id'])
  {
      $reple_contents = str_replace("<br>","\n",$reple_result['contents']);
      echo "<input name='reple_up' type='text' value='$reple_id' style='display:none;'>";
      echo "<textarea id='reple_2' cols='30' rows='2' resize='none''>";
  }

  echo $reple_contents;

  if(isset($_GET['reple_id'])&& $_GET['reple_id']==$reple_result['reple_id'])
      echo "</textarea>";

  echo "</div> <span style='margin-left:250px; font-size:5pt; color:silver'>$reple_result[update_date]</span>";

  if($reple_result['user_id']==$_SESSION['name']) {
        if (isset($_GET['reple_id']) == false) {
            echo "<a href='read.php?board_id=$board_id&reple_id=$reple_id'><input class='btn-xs' style='background-color: white;font-size:8pt;' onclick='br_check()' type='button' value='수정'></a>";
            echo "<a href='reple_process.php?board_id=$board_id&reple_id=$reple_id&reple_del=1'><input type='button' class='btn-xs' style='background-color: white; font-size:8pt;' value='X'></a><br>";
        } else if($_GET['reple_id']==$reple_result['reple_id']){
            echo "<input class='btn-xs'type='button' value='확인' style='background-color:white;' onclick='reple_check(2)'><br>";
        }
    }
}
echo"</form>";
echo"</div>";
mysql_close($db_con);
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery.mon.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>
