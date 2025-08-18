<?php

session_start();

require_once("../db_config.php");

$check_pw = isset($_POST['check_password']) ? trim($_POST['check_password']) : '';
$post_id = (int)$_POST['id'];


if ($check_pw === ''){
    $_SESSION['error'] = "비밀번호를 입력하지 않았습니다. 입력해주세요.";
    header("Location: delete.php");
    exit;
}
try {
    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e){
    $_SESSION['error'] = "데이터베이스 연결 실패";
    header("Location: delete.php");
    exit;
}

$sql = "SELECT password FROM posts WHERE id=$post_id";
$result = $conn->query($sql);
$pw_row = $result->fetch_assoc();

$password_checking = password_verify($check_pw, $pw_row['password']);

if ($password_checking){
    $sql = "DELETE FROM posts WHERE id=$post_id";
    $result = $conn->query($sql);
    
    if($result){
        # 성공
        $conn->close();
        $_SESSION['sussecc'] = "게시글이 삭제 되었습니다.";
        header("Location: list.php");
        exit;
    } else {
        $_SESSION['error'] = "게시글 삭제 오류. 관리자에게 문의 해주세요.";
        header("Location: list.php");
        exit;
    }
} else {
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다. 다시 입력해주세요.";
    header("Location: delete.php");
    exit;
}