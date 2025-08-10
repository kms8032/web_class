<?php

# 에러 메시지 저장과 사용자 정보 저장을 위한 세션 시작
session_start();

# DB 정보 가져오기
require_once('../db_config.php');

# 공백 제거와 입력값이 빈칸인지 확인
# trim() : 앞뒤 공백 문자(스페이스, 탭, 줄바꿈 등) 제거하는 내장 함수
# 프론트엔드에서 required 작업을 하더라도 사용자가 속성을 제거할 수 있기 때문에 
# 혹은 자바스크립트를 꺼버리거나, post 요청을 직접 보내는 툴로 접근이 가능하기 떄문
# 보안의 문제로 인해서 검증이 필요함
$id = isset($_POST['id']) ? trim($_POST['id']) : ''; // id값이 있다면(true) trim 없다면(false) '' 을 저장
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if($id === '' | $password === ''){
    $_SESSION['error'] = "아이다와 비밀번호를 모두 입력해주세요";
    header("Location: login.php");
    exit;
}

# DB 연결
// $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
# PHP 8+ 에서는 mysqli 생성자 자체가 에러 발생 시 예외를 바로 던져 버러셔 아래 if문까지 도달하지 않고 에러를 발생시킴
# try-catch문을 사용한다면 아래의 if문을 실행이 가능
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    # DB 연결 실패
    $_SESSION['error'] = "데이터 베이스 연결 실패";
    header("Location: login.php");
    exit;
}
# db에 해당 아이디가 존재 하는지 확인
$sql_1 = "SELECT * FROM users";
$result_1 = $conn->query($sql_1);
$check_id = mysqli_fetch_assoc($result_1);


if($check_id['id'] === $id){
    # 해시된 pw와 입력한 pw가 일치하는 확인
    $take_pass = password_verify($password, $check_id['password']);
    if ($take_pass === TRUE){
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $check_id['name'];
        header("Location: ../welcome.php");
        exit;
    } else {
        # 일치하지 않으면 에러메시지 출력후 login.php로 이동
        $_SESSION['error'] = "아이디와 비밀번호가 일치하지 않습니다. 다시 입력해주세요.";
        header("Location: login.php");
        exit;
    }
} else {
    # 존재 하지 않으면 login.php로 이동
    $_SESSION['error'] = "아이다가 존재하지 않습니다. 다시 입력해주세요.";
    header("Location: login.php");
    exit;
}