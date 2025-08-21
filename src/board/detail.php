<?php
# 세션 시작
session_start();

# 데이터베이스 정보 가져오기
require_once("../conf/db.php");

# post_id 값 가져오기
$post_id = isset($_GET['post_id']) ? trim($_GET['post_id']) : '';

# 데이터베이스 연결
try {
    $db_connect = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    # 세션에 에러메시지 저장 ( 데이터베이스 연결 실패 )
    $_SESSION['error'] = "데이터베이스 연결 실패";
    # 게시판 페이지로 리디렉션
    header("Location: board.php");
    exit;
}
# post_id와 같은 행 데이터를 조회하는 쿼리
$check_sql = "SELECT * FROM posts WHERE post_id='$post_id'";
$check_result = $db_connect->query($check_sql);
$check_row = $check_result->fetch_assoc();
# 데이터베이스 연결 종료
$db_connect->close();
?>

<!-- 상세게시글 폼 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>
<body>
    <h1>상세게시글</h1>
    <?php
        if (isset($_SESSION['error'])){
            echo"<p style = 'color:red;'>에러 :".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        } elseif (isset($_SESSION['success'])){
            echo"<p style = 'color:blue;'>성공 :".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        
        echo "<h2>"."Title : ".$check_row['title']."</h3>";
        echo "--------------------------------------------------------------------------------"."<br>";
        echo "<p>"."작성자 : ".$_SESSION['name']."</p>";
        echo "--------------------------------------------------------------------------------"."<br>";
        echo "<p>"."작성일 : ".$check_row['create_at']."</p>";
        echo "--------------------------------------------------------------------------------"."<br>";
        echo "<p>"."수정일 : ".$check_row['update_at']."</p>";
        echo "--------------------------------------------------------------------------------"."<br>";
        echo "내용 : "."<textarea rows='4' cols='50' readonly>".$check_row['content']."</textarea>"."<br><br>";
    ?>

    <form>
        <button><a href="edit.php?post_id=<?= $post_id?>">수정하기</a></button>
        <button><a href="delete.php?post_id=<?= $post_id?>">삭제하기</a></button>
        <button><a href="board.php">돌아가기</a></button>
    </form>
</body>
</html>