<?php
# 에러메시지를 위한 세션 시작
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
</head>
<body>
    <?php
        if (isset($_SESSION['error'])){
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }
    ?>
    <form action="register_process.php" method="POST">
        <label for="name">NAME :</label>
        <input type='text' id='name' name='name' required><br><br>    

        <label for="id">ID :</label>
        <input type='text' id='id' name='id' required><br><br>

        <label for='password'>PW :</label>
        <input type='password' id='password' name='password' required><br><br>

        <label for='check_password'>PW 확인 :</label>
        <input type='password' id='check_password' name='check_password' required><br><br>

        <input type='submit' value='회원가입'>
    </form>
</body>
</html>