<?php

session_start();

require_once("../db_config.php");

try {
    # 데이터베이스 연결
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e){
    # 연결 실패 시
    $_SESSION['error'] = "데이터베이스 연결 실패";
    header("Location: list.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT title, name, content, create_at, update_at FROM posts WHERE id=$id";
$result = $conn->query($sql);
$result = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view</title>
</head>
<body>
    <?php
        echo "제목: ".$result['title']."<br>";
        echo "--------------------------------<br>";
        echo "작성일: ".$result['create_at']."<br>";
        echo "수정일: ".$result['update_at']."<br>";
        echo "--------------------------------<br>";
        echo "작성자 :".$result['name']."<br>";
        echo "--------------------------------<br>";
        echo "내용 <br>";
        echo $result['content']."<br>";
   
        echo "<form>";
        echo "<a href='./list.php?id=$id'>"."돌아가기"."</a>";
        echo "<a href='./edit.php?id=$id'>"."수정가기"."</a>";
        echo "<a href='./delete.php?id=$id'>"."삭제하기"."</a>";
        echo "</from>"
    ?>
</body>
</html>