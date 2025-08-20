<?php
# 세션 시작
session_start();
# 데이터베이스 가져오기
require_once("../conf/db.php");

# 데이터베이스 연결
try {
    $db_connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e){
    # 데이터베이스 연결 실패
    # 세션에 오류메시지 저장 ( 데이터베이스 연결 실패 )
    $_SESSION['error'] = "데이터베이스 연결 실패";
    # 환영 페이지로 리디렉션
    header("Location: ../welcome.php");
    exit;
}

# 페이지네이션 기능
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 5;
$offset = ($page-1) * 5;

# 전체 페이지를 구하기 위한 쿼리
$total_page_sql = "SELECT count(*) AS cnt FROM posts";
$cnt_result = $db_connect->query($total_page_sql);
$cnt_row = $cnt_result->fetch_assoc();
# 전체 페이지
$total_page = ceil($cnt_row['cnt']/$per_page);
$cnt_result->free();
# 게시글 조회 쿼리
$posts_check_sql = "SELECT * FROM posts ORDER BY create_at DESC LIMIT $per_page OFFSET $offset";
$posts_check_result = $db_connect->query($posts_check_sql);
# 쿼리 실패
if ($posts_check_result === false) {
    # 세션에 에러메시지 저장 ( 서버 오류. 관리자에게 문의해주세요.)
    $_SESSION['error'] = "서버 오류, 관리자에게 문의해주세요.";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 환영 페이지로 리디렉션
    header("Location: ../welcome.php");
    exit;
} elseif ($posts_check_result->num_rows === 0) {
    # 쿼리 성공
    # 값 X
    # 세션에 메시지 저장 ( 작성된 게시글이 없습니다. )
    $_SESSION['notice'] = "작성된 게시글이 없습니다.";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 공지사항 페이지로 리디렉션
    header("Location: ../welcome.php");
    exit;
} else {
    # 값 O
    # 값을 배열로 저장
    $result_array = $posts_check_result->fetch_all(MYSQLI_ASSOC);
    # 데이터베이스 연결 종료
    $db_connect->close();
}
?>

<!-- 공지사항 렌더링 -->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board</title>
</head>
<body>
    <h1>공지사항</h1>
    <?php
        if (isset($_SESSION['error'])){
            echo"<p style = 'color:red;'>에러 :".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        } elseif (isset($_SESSION['success'])){
            echo"<p style = 'color:blue;'>성공 :".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
    ?>
    <table style="width:100%">
        <tr>
            <td>번호</td>
            <td>제목</td>
            <td>작성일</td>
            <td>수정일</td>
        </tr>
        <?php
            for ($x=1 ; $x<$posts_check_result->num_rows+1; $x++){
                echo "<tr>";
                echo "<td>".$x."</td>";
                echo "<td>".$result_array[$x-1]['title']."</td>";
                echo "<td>".$result_array[$x-1]['create_at']."</td>";
                echo "<td>".$result_array[$x-1]['update_at']."</td>";
                echo "</tr>";
            }
        ?>
    </table>
    <?php
        for ( $y=1; $y<$total_page+1; $y++){
            echo "<a href='board.php?page=$y'>".$y."</a>";
            echo "<br>";
        }
    ?>
    <form>
        <button type="submit" formaction="write.php">작성하기</button>
    </form>
</body>
</html>

<style>
    table, th, td {
        border : 1px solid;
    }
</style>