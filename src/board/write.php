<?php

# 세션 시작 ( 메시지 저장, 이름 가져오기 )
session_start();
?>

<!-- 작성 폼 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>게시글 작성</h1>
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
    <form action="write_process.php" method="POST">
        <label for="post_pw">PW :
            <input type="password" id="post_pw" name="post_pw" required><br><br>
        </label>
        <label for="check_post_pw">PW 확인 :
            <input type="password" id="check_post_pw" name="check_post_pw" required><br><br>
        </label>
        <label for="title">Title :
            <input type="text" id="title" name="title" required><br><br>
        </label>
        Content: <textarea id="content" name="content" rows="4" cols="50" required></textarea>><br><br>
        <input type="submit" value="작성완료">
    </form>
    <form>
        <button type="submit" formaction="board.php">돌아가기</button>
    </form>
</body>
</html>