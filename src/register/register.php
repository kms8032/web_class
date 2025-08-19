<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
</head>
<body>
    <h1>회원 가입</h1>
    <?php
    if (isset($_SESSION['error'])){
        echo"<p style = 'color:red;'>에러 :".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
    <form action="register_process.php" method="POST">
        <label for="name">NAME :
            <input type="text" id="name" name="name" required><br><br>
        </label>
        <label for="id">ID :
            <input type="text" id="id" name="id" required><br><br>
        </label>
        <label for="pw">PW :
            <input type="password" id="pw" name="pw" required><br><br>
        </label>
        <label for="pw">PW 확인:
            <input type="password" id="check_pw" name="check_pw" required><br><br>
        </label>
        <input type="submit" value="회원가입"> 
    </form>
    <form action="../Login/login.php">
        <input type="submit" value="뒤로가기">
    </form>
</body>
</html>
