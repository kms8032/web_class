<?php
# 세션 시작
session_start();
# 데이터베이스 정보 불러오기
require_once("../conf/db.php");

# 입력값 공백제거
$name = isset($_SESSION['name']) ? trim($_SESSION['name']) : '';
$post_pw = isset($_POST['post_pw']) ? trim($_POST['post_pw']) : '';
$check_post_pw = isset($_POST['check_post_pw']) ? trim($_POST['check_post_pw']) : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

# 입력값 공백인지 확인
if ($post_pw === '' || $check_post_pw === '' || $title === '' || $content === '' ){
    # 세션에 에러메시지 저장 ( 모든 값을 입력해야 합니다. 다시 입력해주세요. )
    $_SESSION['error'] = "모든값을 입력해야 합니다. 다시 입력해주세요.";
    # 작성폼으로 리디렉션
    header("Location: write.php");
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

# 비밀번호 일치 여부
if ($post_pw === $check_post_pw){
    # 일치
    # 비밀번호 해쉬
    $hash_post_pw = password_hash($post_pw, PASSWORD_DEFAULT);
} else {
    # 불일치
    # 세션에 에러메시지 저장 ( 비밀번호가 일치하지 않습니다. 다시 입력 해주세요. )
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다. 다시 입력 해주세요.";
    # 작성폼으로 리디렉션
    header("Location: write.php");
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
    header("Location: write.php");
    exit;
}


# 입력값 삽입을 위한 쿼리
$write_sql = "INSERT INTO posts (writer, post_pw, title, content) VALUES ('$name', '$hash_post_pw', '$title', '$content')";
$write_result = $db_connect->query($write_sql);

# 입력되었는지 확인
if ($write_result === true){
    # 성공
    # 세션에 성공메시지 저장 ( 게시글이 저장되었습니다.)
    $_SESSION['success'] = "게시글이 저장되었습니다.";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 게시판으로 리디렉션
    header("Location: board.php");
    exit;
} else {
    # 실패
    # 세션에 에러메시지 저장 ( 게시글이 저장되지 않습니다. 잠시 후 다시 시도해주세요.);
    $_SESSION['error'] = "게시글이 저장되지 않습니다. 잠시 후 다시 시도해주세요.";
    # 데이터베이스 연결 종료
    $db_connect->close();
    # 게시판으로 리디렉션
    header("Location: board.php");
    exit;
}
