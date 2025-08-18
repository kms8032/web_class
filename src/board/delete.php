<?php
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_SESSION['error'])){
    echo $_SESSION['error'];
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>delete</title>
</head>
<body>
    <form action="delete_process.php" method="POST">
        <input type="hidden" id='id' name='id' value="<?= $id?>">
        <label for="check_password">PW 확인 :
            <input type="password" id="check_password" name="check_password" required>
        </label><br><br>

        <input type="submit" value="삭제하기">
    </form>
</body> 
</html>