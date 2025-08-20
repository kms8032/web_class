<?php
session_start();

$page = 1;
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
    <?php 
        if (isset($_SESSION['notice'])){
            echo "<p style= 'color:green;'>알림 :".$_SESSION['notice']."</p>";
            unset($_SESSION['notice']);
        }
    ?>
    <a href='../board/board.php?page=<?= $page?>'><input type='submit' value='공지사항'></a>
    <form>
        <button type='submit' formaction="../Login/logout.php">로그아웃</button>
    </form>
</body>
</html>