
<script>
    function check(){
        var idlength=document.getElementById('id').value;
        var pwlength=document.getElementById('pw').value;

        if(idlength.length==0 || pwlength.length==0){
            alert("아이디 또는 비밀번호가 입력되지않았습니다.");
        }else{
            document.getElementById('form').submit();
        }
    }
</script>


<?php
echo "<div style='width:100%; height: 70px; background-color: white;position:absolute; top:0px;'>
      <h1 style='text-align:center; color:mediumpurple;'><a href='listup.php' style='text-decoration: none;'>AHN BOARD</a></h1></div>";
echo "<img src='wallpapaer.jpg' style='width:100%;margin-bottom:-500px; '><br>";
echo "<div style=' position:absolute; top:0px; right:0px;'>";
echo "<form id='form' action='log_process.php' method='post'>";
    if(isset($_SESSION['userid'])){
    echo "<table style='width:300px;'>";
    echo "<td style='width:50pt; height:50pt; border-right: 1px solid black;'>
    <img src='photo.jpg'style='width:40px; height:40px;'>$_SESSION[name]님";
    echo "<input style='margin-left:30%; height: 20pt; background-color: white' class='btn-xs'  type='submit' name='log_out' value='로그아웃'></td>";
    echo"</table>";
}
else {
        if(isset($_COOKIE['nandemo22']) && isset($_COOKIE['nandemo2ya'])==false ){
           echo "<script> alert('아이디 또는 비밀번호를 다시 확인하세요. 등록되지 않은 아이디이거나,아이디 또는 비밀번호를 잘못 입력하셨습니다.');</script>";
        }
    echo "<table >";
    echo "<tr><td ><input name='ID' id='id' type='text' size='20' style='background-color:ivory; height: 23pt;' placeholder='아이디'></td>";
    echo "<td><input name='PW' id='pw' type='password' size='20' style='background-color:ivory;height: 23pt; ' placeholder='비밀번호'></td>";
       echo "<td><input type='button'  onclick='check()' value='로그인' style='height: 25pt; width: 50pt; background-color: deepskyblue;
            border:1px solid lightblue; color:white; font-size: medium' >
            </td></tr>";
    echo "</table>";

}
echo "</form>";

echo "</div>";
?>