<?php
# 세션 시작 (에러메시지 저장을 위해서)
session_start();
# 데이터베이스 가져오기
require_once("../conf/db.php");
# 입력값 공백 제거 & 입력값이 존재하지 않으면 빈칸으로 나두기
$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$pw = isset($_POST['pw']) ? trim($_POST['pw']) : '';
# 입력값이 빈칸인지 확인
if ($id === '' || $pw === ''){
    # 입력값이 빈칸이라면 세션에 에러메시지( 입력값 중 빈칸이 있습니다. 모두 작성해주세요.);
    $_SESSION['error'] = "입력값 중 빈칸이 있습니다. 모두 작성해주세요.";
    # 로그인 페이지로 리디렉션
    header("Location: login.php");
    exit;
}
# 데이터베이스 연결
try {
    $db_connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    # 데이터베이스 연결 실패
    # 세션에 에러메시지 저장 ( 데이터베이스 연결 실패 )
    $_SESSION['error'] = "데이터베이스 연결 실패";
    # 로그인 페이지로 리디렉션
    header("Location: login.php");
    exit;
}
# users 테이블에 동일한 id 있는지 확인
# 있다면 쿼리를 통해 전체 값을 열로 가져오기
$check_id_sql = "SELECT id, name, password FROM users WHERE id='$id'";
$check_id_result = $db_connect->query($check_id_sql);
if ($check_id_result === false) {
    # 세션에 에러메시지 저장 (시스템 오류)
    $_SESSION['error'] = "시스템 오류";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 로그인 페이지 리디렉션
    header("Location: login.php");
    exit;
} elseif ($check_id_result->num_rows === 0) {
    $_SESSION['error'] = "아이디와 비밀번호가 맞지 않습니다. 다시 입력해주세요.";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 로그인 페이지로 리디렉션
    header("Location: login.php");
    exit;
} else {
    $login_row = $check_id_result->fetch_assoc();
    $matched_pw = password_verify($pw, $login_row['password']);
    if ($matched_pw === false) {
        # 일치 X - 세션에 에러메시지 ( 아이디와 비밀번호가 맞지 않습니다. 다시 입력해주세요. ) 저장
        $_SESSION['error'] = "아이디와 비밀번호가 맞지 않습니다. 다시 입력해주세요.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 로그인 페이지로 리디렉션
        header("Location: login.php");
        exit;
    } else {
        # 일치 O 세션에 id, name 저장
        $_SESSION['id'] = $login_row['id'];
        $_SESSION['name'] = $login_row['name'];
        # 데이터베이스 연결 종료
        $db_connect->close();
        # welcome.php로 리디렉션
        header("Location: ../welcome.php");
        exit;
    }
}
