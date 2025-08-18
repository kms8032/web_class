<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
</head>
<body>
    <h1>게시글 작성</h1>
    <?php if(isset($_SESSION['error'])) {
        echo "에러:".$_SESSION['error'];
        unset($_SESSION['error']);
    }
    ?> <br>
    <form action="write_process.php" method="POST">
        ID : <?php echo $_SESSION['id'] ?> <br><br>
        name : <?php echo $_SESSION['name'] ?> <br><br>
        <label for="password"> PW :
            <input type="password" id="password" name="password" required>
        </label> <br><br>
        <label for="check_password"> PW 확인 :
            <input type="password" id="check_password" name="check_password" required>
        </label> <br><br>
        <label for="title"> Title :
            <input type="text" id="title" name="title" required>
        </label> <br><br>
        <label for="content"> Content :
            <textarea id="content" name="content" rows="4" cols="50" required> </textarea>
        </label> <br><br>
        <input type="submit" value="작성완료">
    </form>
    <form action="list.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>