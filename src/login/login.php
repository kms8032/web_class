<?php
# 에러 메시지 출력을 위한 세션 시작
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>로그인</h2>
    <!-- 에러 발생 시 에러 메세지 호출 -->
    <?php
        if (isset($_SESSION['error'])) {
            echo "에러 :".$_SESSION['error'];
            unset($_SESSION['error']);
        } elseif (isset($_SESSION['success'])) {
            echo "성공 :".$_SESSION['success'];
            unset($_SESSION['success']);
        }
    ?>
    <!-- 로그인 폼 작성 -->
    <form action="login_process.php" method="POST">
        <label for="id">ID :</label>
        <input type='text' id="id" name="id" required><br><br>

        <label for="id">PW :</label>
        <input type='password' id="password" name="password" required><br><br>

        <input type='submit' value="로그인">
    </form>
    
    <a href='../register/register.php'><input type='submit' value='회원가입'></a>
</body>
</html>