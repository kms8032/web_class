<?php
# 세션 시작
session_start();

# 데이터베이스 정보 가져오기
require_once("../conf/db.php");

# post_id 가져오기
$post_id = isset($_GET['post_id']) ? trim($_GET['post_id']) : '';

# 데이터 베이스 연결
try {
    $db_connect = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $db_connect->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e){
    # 세션에 에러메시지 저장( 데이터베이스 연결 실패 )
    $_SESSION['error'] = "데이터베이스 연결 실패";
    # 상세게시글로 리디렉션
    header("Location: detail.php");
    exit;
}

# 해당 상세 게시글 내용 가져오기
$post_id_get_sql = "SELECT title, content FROM posts WHERE post_id=$post_id";
$get_result = $db_connect->query($post_id_get_sql);
# 배열에 저장
$get_row = $get_result->fetch_assoc();

# 데이터베이스 연결 종료
$db_connect->close();
?>

<!-- 수정 폼  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit page</title>
</head>
<body>
    <h1>수정</h1>
    <?php
        if (isset($_SESSION['error'])){
            echo"<p style = 'color:red;'>에러 :".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        } elseif (isset($_SESSION['success'])){
            echo"<p style = 'color:blue;'>성공 :".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        echo "NAME : ".$_SESSION['name']."<br><br>";
    ?>
    <form action="edit_process.php?post_id=<?= $post_id ?>" method="POST">
        <label for="post_pw">PW :
            <input type="password" id="post_pw" name="post_pw" required><br><br>
        </label>
        <label for="title">Title :
            <input type="text" id="title" name="title" value=<?= $get_row['title']?> required><br><br>
        </label>
        Content: <textarea id="content" name="content" rows="4" cols="50" required><?= $get_row['content']?></textarea>><br><br>
        <input type="submit" value="수정완료">
    </form>
    <form>
        <button><a href="detail.php?post_id=<?= $post_id?>">돌아가기</a></button>
    </form>
</body>
</html>