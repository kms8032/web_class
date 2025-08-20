<?php

# 에러 메시지 저장을 위한 세션 시작
session_start();

# DB 정보 가져오기
require_once("../conf/db.php");

# 입력된 값 공백 제거
$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$pw = isset($_POST['pw']) ? trim($_POST['pw']) : '';
$check_pw = isset($_POST['check_pw']) ? trim($_POST['check_pw']) : '';

# 입력된 값이 빈 값인지 확인
if ($id === '' || $name === '' || $pw === '' || $check_pw === ''){
    # 빈 값이라면 세션에 에러메시지(입력값 중 빈칸이 있습니다. 다시 입력해주세요.)
    $_SESSION['error'] = "입력값 중 빈칸이 있습니다. 다시입력해주세요.";
    # 회원가입 페이지로 리디렉션
    header("Location: register.php");
    exit;
}

# 입력된 비밀번호와 비밀번호 확인이 일치 여부
if ($pw !== $check_pw){
    # 불일치
    # 세션에 에러메시지(비밀번호가 일치하지 않습니다. 다시 입력해주세요.) 저장
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다. 다시 입력해주세요.";
    # 회원가입 페이지로 리디렉션
    header("Location: register.php");
    exit;
} else {
    # 일치하면 바밀번호 해싱
    $hash_pw = password_hash($pw,PASSWORD_DEFAULT);
}

# 데이터베이스 연결
try {
    $db_connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    # 연결 실패 시 세션에 에러메시지( 데이터베이스 연결 실패 ) 저장
    $_SESSION['error'] = "데이터베이스 연결 실패";
    # 회원가입 페이지로 리디렉션
    header("Location: register.php");
    exit;
}



# 아이디 존재 여부 쿼리
# SELETE 쿼리
$check_id_sql = "SELECT id FROM users WHERE id='$id'";
$id_result = $db_connect->query($check_id_sql);

# 아이디 존재 O
if ($id_result->num_rows > 0){
    # 세션에 세션에 에러메시지(중복된 아이디가 있습니다. 다시 입력해 주세요.)
    $_SESSION['error'] = "중복된 아이디가 있습니다. 다시 입력해 주세요.";
    # 회원가입 페이지로 리디렉션
    header("Location: register.php");
    exit;
} else {
    # 아이디 존재 X
    # INSERT INTO 쿼리
    $input_data_sql = "INSERT INTO users (id, password, name) VALUES ('$id','$hash_pw','$name')";
    $result = $db_connect->query($input_data_sql);

    if ($result === 0 ){
        # 실패 - 세션에 에러메시지(관리자에게 문의해 주세요.)
        $_SESSION['error'] = "관리자에게 문의해 주세요.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 회원가입 페이지로 리디렉션
        header("Location: register.php");
        exit;
    } else {
        # 쿼리의 결과에 따라
        # 성공 - 세션에 성공메시지(회원가입이 완료되었습니다. 로그인을 시도해 주세요.)
        $_SESSION['success'] = "회원가입이 완료되었습니다. 로그인을 시도해주세요.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 로그인 페이지로 리디렉션
        header("Location: ../Login/login.php");
        exit;
    }   
}