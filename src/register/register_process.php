<?php

# 세션 시작
session_start();

# 데이터베이스 불러오기
require_once("../db_config.php");

# 입력값 공백 제거 및 입력값이 전부 있는지 확인
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$check_password = isset($_POST['check_password']) ? trim($_POST['check_password']) : '';

if ($name === '' | $id === '' | $password === '' | $check_password === '') {
    # 세션에 에러 메시지 저장 후 register.php 리디렉션
    $_SESSION['error'] = "모든 창에 입력해야 합니다.";
    header("Location: register.php");
    exit;
}

# 비밀번호와 비밀번호 확인이 일치한지 확인
if ($password !== $check_password) {
    # 세션에 에러 메시지 저장 후 register.php 리디렉션
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다. 다시 입력해주세요.";
    header("Location: register.php");
    exit;
}

# 비밀번호 해시
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

# 데이터베이스 연결
try {
    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    # 연결 실패 시 에러메시지 세션에 저장 후 register.php로 리디렉션
    $_SESSION['error'] = '데이터베이스 연결 실패';
    header("Location: register.php");
    exit;
}

# 데이터베이스에 이미 존재하는 아이디인지 확인
$sql_1 = "SELECT * FROM users";
$result_1 = $conn->query($sql_1);
$check_id = mysqli_fetch_assoc($result_1);


if($check_id['id'] === $id){
    $SESSION['error'] = "중복된 아이디가 있습니다. 다른 아이디로 해주세요.";
    header("Location: register.php");
    exit;
}

# 쿼리 작업
$sql_2 = "INSERT INTO users (name, id, password) VALUES ('$name', '$id', '$hashed_password')";
$result_2 = $conn->query($sql_2);


if ($result_2) {
    # 회원가입 성공 처리
    # 데이터 베이스 연결 종료
    # 세션에 성공 메시지 저장 후 login.php로 리디렉션
    $conn->close();
    $_SESSION['success'] = "회원가입이 완료되었습니다. 로그인을 해주세요";
    header("Location: ../login/login.php");
    exit;
} else {
    # 화원가입 실패 처리
    # 1. 데이터베이스 연결 실패
    # 세션에 에러 메시지 저장 후 register.php로 리디렉션
    $conn->close();
    $_SESSION['error'] = "회원가입에 실패했습니다. 잠시 후 다시 해주세요";
    header("Location: register.php");
    exit;
}