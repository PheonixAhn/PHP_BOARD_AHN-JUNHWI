<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="css/bootstrap-theme.css" type="text/css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery.mon.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
</head>
<body>

<?php
include 'log_in.php';
echo "<br>";

@$db_con = mysql_connect('localhost','root','autoset');
@mysql_selectdb('andb',$db_con);
if(isset($_GET['limit'])) {
    $limit=$_GET['limit'];
    $query = "select * from board where board_pid=0 order by reg_date desc limit $limit,5";
}else{
    $query = "select * from board where board_pid=0 order by reg_date desc limit 0,5";
}
if(isset($_GET['search_n'])&& $_GET['search_n']!=0){
    $search=$_GET['search'];
    $search_n=$_GET['search_n'];
    switch ($_GET['search_n']){
        case 1:$keyword = "subject";
            break;
        case 2:$keyword = "contents";
            break;
        case 3:$keyword = "user_id";
            break;
        case 4://통합검색
            break;
    }
    if($search_n==4){
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        } else {
            $limit=0;
        }
    $query = "select * from board where INSTR(subject,'$search')!=0";
    $query .=" UNION";
        $query .=" select * from board where INSTR(contents,'$search')!=0";
        $query .=" UNION";
        $query .=" select * from board where INSTR(user_id,'$search')!=0 order by reg_date desc limit $limit,5";
    }
    else {
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        } else {
            $limit = 0;
        }
        $query = "select * from board where INSTR($keyword,'$search')!=0 order by reg_date desc limit $limit,5";

    }
}
else{
    $search = "";
    $search_n=0;
}
$result=mysql_query($query);
//리스트업 표
echo "<div>";
echo "<table class='table'style=' margin-top:400px;'>";
echo "<th class='btn-primary'>글번호</th><th class='btn-primary'style='text-align:center'>제목</th><th class='btn-primary'>글쓴이</th><th class='btn-primary'>조회수</th><th class='btn-primary'>작성일</th>";
while($contents=mysql_fetch_array($result)){
    echo "<tr>";
    echo "<td style='text-align:left' >$contents[board_id]  </td>";
    echo "<td style='width: 600px;'>";
    if(isset($_SESSION['userid'])) {
        echo "<a href='read.php?board_id=$contents[board_id]'>$contents[subject]   </a>";
    }
    else{
        echo "$contents[subject]";
    }
    echo "</td>";
    echo "<td style='text-align:left'>$contents[user_id]   </td>";
    echo "<td style='text-align:left''>$contents[hits]      </td>";
    echo "<td style='width: 250px;'>$contents[reg_date]  </td>"."<br>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";
//글쓰기, 글목록 버튼
echo "<form action='listup.php' method='GET'>";
echo "<select  name='search_n' style='margin-left:40%;' >
                <option value='1'>제목</option>
                <option value='2'>내용</option>
                <option value='3'>작성자</option>
                <option value='4번'>제목+내용+작성자</option>
        </select>";
echo "<input name='search' type='text'>";
echo "<input type='submit' class='btn-danger' value='검색'>";
echo "<button class='btn-danger'><a href='listup.php'style='color:white;'>글목록</a></button>";
if(isset($_SESSION['userid'])) {
    echo "<a href='writemode.html'><input type='button' class='btn-danger' value='글쓰기'/></a>";
}
echo "</form>";
echo "<hr>";
echo "<div style='font-size: 15pt; margin-left: 45%;'>";

//페이지 네이셔어어언
$query2 = "select count(*) from board where board_pid=0";
if(isset($_GET['search_n'])&& $_GET['search_n']!=4 && $_GET['search_n']!=0){
    $query2 = "select count($keyword) from board where INSTR($keyword,'$search')!=0";
}
if($search_n==4){
    $query2 = "select count(b.board_id) from";
    $query2 .=" (select * from board where INSTR(subject,'$search')!=0";
    $query2 .=" UNION";
    $query2 .=" select * from board where INSTR(contents,'$search')!=0";
    $query2 .=" UNION";
    $query2 .=" select * from board where INSTR(user_id,'$search')!=0) b";
}
$query2 = mysql_query($query2);
$query2= mysql_fetch_row($query2);
$limit_b = ceil($query2[0]/5);
if($limit_b!=0) {
    if ($limit_b <= 5) {
//왼쪽 방향 작업
        if (isset($limit)) {
            $limit_p = $limit - 5;
            if ($limit != 0) {
                echo "<a href='listup.php?limit=$limit_p&search=$search&search_n=$search_n'> < </a>";
            } else {
                echo "<a href='#'> < </a>";
            }
        } else {
            echo "<a href='#'> < </a>";
        }
//페이지 숫자 출력및 링크
        for ($i = 1; $i <= $limit_b; $i++) {
            $j = ($i - 1) * 5;
            echo "<a href='listup.php?limit=$j&search=$search&search_n=$search_n'>  $i   </a>";
        }
//오른쪽 방향 작업
        if (isset($limit)) {
            $limit_n = $limit + 5;
            $limit_now = $limit / 5 + 1;
            if ($limit_b != $limit_now) {
                echo "<a href='listup.php?limit=$limit_n&search=$search&search_n=$search_n'>  >  </a>";
            } else {
                echo "<a href='#' >  >  </a>";
            }
        } else {
            echo "<a href='listup.php?limit=5&search=$search&search_n=$search_n'>  >  </a>";
        }
        echo @$limit_now;
        echo "/" . $limit_b;
//////////////////////////////////////////////////////////////////////
    } else {
        if (isset($limit) == false) {
            $page = 0;
        } else {
           @ $page = $_GET['page'];
            $limit_p = $limit - 5;
            $limit_n = $limit + 5;
            $limit_now = $limit / 5 + 1;
        }
        if (isset($_GET['prev'])) {
            $page -= 5;
        }
        if (isset($_GET['next'])) {
            $page += 5;
        }

        //왼쪽 방향 작업
        if (isset($limit)) {
            if ($limit != 0) {
                if ($limit_now % 5 == 1) {
                    echo "<a href='listup.php?limit=$limit_p&page=$page&search=$search&search_n=$search_n&prev'> < </a>";
                } else {
                    echo "<a href='listup.php?limit=$limit_p&page=$page&search=$search&search_n=$search_n'> < </a>";
                }
            } else {
                echo "<a href='#'> < </a>";
            }
        } else {
            echo "<a href='#'> < </a>";
        }
//페이지 숫자 출력및 링크
        //1~5
        if ($page == 0) {
            for ($i = 1; $i <= 5; $i++) {
                $page = 0;
                $j = ($i - 1) * 5;
                echo "<a href='listup.php?limit=$j&page=$page&search=$search&search_n=$search_n'>  $i   </a>";
            }
        } //1페이지아닌 페이지
        else {
            for ($i = 1 + $page; $i <= 5 + $page; $i++) {
                $j = ($i - 1) * 5;
                echo "<a href='listup.php?limit=$j&page=$page&search=$search&search_n=$search_n'>  $i   </a>";
                if ($i == $limit_b)
                    break;
            }
        }
//오른쪽 방향 작업
        if (isset($limit)) {
            if ($limit_b != $limit_now) {
                if ($limit_now < 5) {
                    echo "<a href='listup.php?limit=$limit_n&page=0&search=$search&search_n=$search_n'>  >  </a>";
                } else {
                    if ($limit_now % 5 == 0) {
                        echo "<a href='listup.php?limit=$limit_n&page=$page&next&search=$search&search_n=$search_n'>  >  </a>";
                    } else {
                        echo "<a href='listup.php?limit=$limit_n&page=$page&search=$search&search_n=$search_n'>  >  </a>";
                    }
                }
            } else {
                echo "<a href='#' >  >  </a>";
            }
        } else {
            echo "<a href='listup.php?limit=5&page=0&search=$search&search_n=$search_n'>  >  </a>";
        }
        echo @$limit_now;
        echo "/" . $limit_b;
    }
}
echo "</div>";
mysql_close($db_con);
?>
</body>
</html>
