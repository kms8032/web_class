<?php
session_start();
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
  </body>
</html>
