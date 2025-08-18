<?php
session_start();

$now_page = 1;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <h2>환영합니다 <?php echo $_SESSION['name']; ?> 님</h2>
    <form action="./login/logout.php">
      <input type="submit" value="로그아웃">
    </form>
    <a href='../board/list.php?page=<?= $now_page?>'><input type='submit' value='공지사항'></a>
  </body>
</html>
