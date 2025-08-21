<?php

# 세션 시작
session_start();
# 데이터베이스 가져오기
require_once("../conf/db.php");

# post_id 가져오기
$post_id = isset($_GET['post_id']) ? max(1, trim($_GET['post_id'])) : 1;

# 입력값 검증
$post_pw = isset($_POST['pw']) ? trim($_POST['pw']) : '';

# 입력값 빈칸인지 확인
if ($post_pw === '') {
    # 세션에 에러메시지 저장 ( 비밀번호를 입력해주세요. )
    $_SESSION['error'] = "비밀번호를 입력해주세요. ";
    # 삭제 페이지로 리디렉션
    header("Location: delete.php?post_id=$post_id");
    exit;
}

# 데이터베이스 연결
try {
    $db_connect = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    # 세션에 에러메시지 저장 ( 데이터베이스 연결 실패 )
    $_SESSION['error'] = "데이터베이스 연결 실패";
    # 상세 게시글로 리디렉션
    header("Location: detail.php?post_id=$post_id");
    exit;
}

# 비밀번호 검증을 위해 비밀번호 가져오기
$pw_get_sql = "SELECT post_pw FROM posts WHERE post_id=$post_id";
$pw_get_result = $db_connect->query($pw_get_sql);
$pw_get_row = $pw_get_result->fetch_assoc();

# 비밀번호 일치 여부 확인
if (password_verify($post_pw, $pw_get_row['post_pw'])) {
    # 일치
    $delete_pw_sql = "DELETE FROM posts WHERE post_id=$post_id";
    $pw_get_result = $db_connect->query($delete_pw_sql);
    if ($pw_get_result) {
        # 성공
        # 세션에 성공메시지 저장
        $_SESSION['success'] = "게시글이 삭제되었습니다.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 게시판 페이지로 리디렉션
        header("Location: board.php");
        exit;
    } else {
        # 실패
        # 세션에 에러메시지 저장
        $_SESSION['error'] = "시스템 오류. 관리자에게 문의 해주세요.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 게시판 페이지로 리디렉션
        header("Location: board.php");
        exit;
    }
} else {
    # 실패
    # 세션에 에러메시지 저장
    $_SESSION['error'] = "비밀번호가 틀렸습니다. 다시 입력해주세요.";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 삭제 페이지로 리디렉션
    header("Location: delete.php?post_id=$post_id");
    exit;
}