<?php
session_start();

require_once("../db_config.php");

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$check_password = isset($_POST['check_password']) ? trim($_POST['check_password']) : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if ($check_password === '' | $title === '' | $content === ''){
    $_SESSION['error'] = "모든 항목을 입력해야 합니다.";
    header("Location: edit.php");
    exit;
}

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = "데이터베이스 연결 실패";
    header("Location: edit.php");
    exit;
}

$sql_pw = "SELECT password FROM posts WHERE id=$id";
$result_pw = $conn->query($sql_pw);
$pw_row = $result_pw->fetch_assoc();

$DB_pw = password_verify($check_password, $pw_row['password']);
if ($DB_pw) {

    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
    $result = $conn->query($sql);

    if ($result) {
        # 성공
        $conn->close();
        $_SESSION['success'] = "게시글 수정이 완료되었습니다.";
        header("Location: list.php");
        exit;
    } else {
        # 실패
        $_SESSION['error'] = "수정 실패, 잠시 후 다시 시도해 주세요.";
        header("Location: list.php");
        exit;
    }
} else {
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다.";
    header("Location: edit.php");
    exit;
}