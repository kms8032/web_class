<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h1>로그인</h1>
    <?php
    if (isset($_SESSION['error'])){
        echo"<p style = 'color:red;'>에러 :".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    } elseif (isset($_SESSION['success'])){
        echo"<p style = 'color:blue;'>성공 :".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    ?>
    <form action="login_process.php" method="POST">
        <label for="id">ID :
            <input type="text" id="id" name="id" required><br><br>
        </label>
        <label for="pw">PW :
            <input type="password" id="pw" name="pw" required><br><br>
        </label>
        <input type="submit" value="로그인"> 
    </form>
    <form action="../register/register.php">
        <input type="submit" value="회원가입">
    </form>
</body>
</html>