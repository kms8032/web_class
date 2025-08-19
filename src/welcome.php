<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcome page</title>
</head>
<body>
    <h1>환영합니다. <?= $_SESSION['name'] ?></h1>
    <form>
        <button type='submit' formaction="../board/board.php">공지사항</button>
    </form>
    <form>
        <button type='submit' formaction="../Login/logout.php">로그아웃</button>
    </form>
</body>
</html>