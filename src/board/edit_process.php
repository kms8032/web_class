<?php

# 세션 시작
session_start();

# 데이터베이스 가져오기
require_once("../conf/db.php");

# post_id 가져오기
$post_id = isset($_GET['post_id']) ? trim($_GET['post_id']) : '';

# 입력값 공백제거
$name = isset($_SESSION['name']) ? trim($_SESSION['name']) : '';
$post_pw = isset($_POST['post_pw']) ? trim($_POST['post_pw']) : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

# 입력값 공백인지 확인
if ($post_pw === '' || $title === '' || $content === '' ){
    # 세션에 에러메시지 저장 ( 모든 값을 입력해야 합니다. 다시 입력해주세요. )
    $_SESSION['error'] = "모든값을 입력해야 합니다. 다시 입력해주세요.";
    # 작성폼으로 리디렉션
    header("Location: edit.php");
    exit;
}
# 로그인된 사용자인지 확인
if ($name === ''){
    # 세션에 에러메시지 저장 ( 로그인된 사용자가 아닙니다. 로그인해주세요. )
    $_SESSION['error'] = "로그인된 사용자가 아닙니다. 로그인해주세요.";
    # 로그인페이지 리디렉션
    header("Location: ../Login/login.php");
    exit;
}

# 데이터베이스 연결
try {
    $db_connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    # 세션에 에러메시지 저장 ( 데이터베이스 연결 실패 )
    $_SESSION['error'] = "데이터베이스 연결 실패. 관리자에게 문의해주세요.";
    # 작성폼으로 리디렉션
    header("Location: edit.php");
    exit;
}

# 데이터베이스의 비밀번호와 일치한지 확인
# 비밀번호 가져오기
$save_post_pw_sql = "SELECT post_pw FROM posts WHERE post_id=$post_id";
$save_post_pw_result = $db_connect->query($save_post_pw_sql);
$save_post_pw_row = $save_post_pw_result->fetch_assoc();

# 비밀번호 일치 여부
if (password_verify($post_pw, $save_post_pw_row['post_pw'])===false){
    # 불일치
    # 세션에 에러메시지 저장 ( 비밀번호가 일치하지 않습니다. 다시 입력 해주세요. )
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다. 다시 입력 해주세요.";
    # 수정폼으로 리디렉션
    header("Location: edit.php?post_id=$post_id");
    exit;
} else {
    # 업데이트 쿼리
    $post_update_sql = "UPDATE posts SET title='$title', content='$content' WHERE post_id=$post_id";
    $update_result = $db_connect->query($post_update_sql);

    # 업데이트 성공
    if ($update_result) {
        # 세션에 성공 메시지 저장
        $_SESSION['success'] = "게시물이 수정되었습니다.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 게시물 페이지로 리디렉션
        header("Location: board.php");
        exit;
    } else {
        # 세션에 에러 메시지 저장
        $_SESSION['error'] = "시스템 오류. 관리자에게 문의하세요.";
        # 데이터베이스 연결 종료
        $db_connect->close();
        # 게시물 페이지로 리다렉션
        header("Location: board.php");
        exit;
    }
}


