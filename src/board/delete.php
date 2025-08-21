<?php
# 세션 시작
session_start();

$post_id = isset($_GET['post_id']) ? max(1, trim($_GET['post_id'])) : 1;
?>

<!-- 비밀번호 검증 폼  -->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>delete page</title>
</head>
<body>
    <h1>게시글 삭제</h1>
    <p> 해당 게시글의 비밀번호를 입력해주세요. </p>
    <?php
        if (isset($_SESSION['error'])){
            echo"<p style = 'color:red;'>에러 :".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
    ?>
    <form action="delete_process.php?post_id=<?= $post_id?>" method="post">
        <label for="pw">PW :
            <input type="password" id="pw" name="pw" required><br><br>
        </label>
        <input type="submit" value="삭제하기">
    </form>
</body>
</html>