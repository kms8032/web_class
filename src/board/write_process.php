<?php

# 세션 시작
session_start();

# DB 정보 불러오기
require_once("../db_config.php");

# 입력값 전처리 및 검증
$name = $_SESSION['name'];
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$check_password = isset($_POST['check_password']) ? trim($_POST['check_password']) : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if ($password === ''){
    $_SESSION['error'] = "비밀번호를 빈칸으로 둘 수 없습니다.";
    header("Location: write.php");
    exit;
}

if ($title === ''){
    $_SESSION['error'] = "제목을 빈칸으로 둘 수 없습니다.";
    header("Location: write.php");
    exit;
}

if ($content === ''){
    $_SESSION['error'] = "내용을 빈칸으로 둘 수 없습니다.";
    header("Location: write.php");
    exit;
}

# 비밀 번호 확인
if ($password !== $check_password) {
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다. 다시 입력해주세요.";
    header("Location: write.php");
    exit;
}

# 비밀번호 해쉬화
$password = password_hash($password, PASSWORD_DEFAULT);

try {
    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    # 연결 실패 시 에러메시지 세션에 저장 후 write.php로 리디렉션
    $_SESSION['error'] = "데이터베이스 연결 실패";
    header("Location: write.php");
    exit;
}

# 쿼리
$sql = "INSERT INTO posts ( name, password, title, content ) VALUES ( '$name','$password', '$title', '$content')";
$result = $conn->query($sql);

if($result) {
    # 게시글 작성 완료
    # 데이터베이스 종료
    # 성공 메시지를 세션에 저장 후 list.php로 리디렉션
    $conn->close(); 
    header("Location: list.php");
    exit;
} else {
    # 데이터베이스에 저장하지 못한 경우
    # 데이터베이스 종료
    # 세션에 에러메시지를 남기후 list.php로 리디렉션
    $conn->close();
    $_SESSION['error'] = "게시글을 저장하지 못했습니다. 관리자에게 문의해주세요";
    header("Location: list.php");
    exit;
}