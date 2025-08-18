<?php
session_start();

require_once("../db_config.php");

try {
    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = "데이터베이스 연결 실패";
    header("Location: list.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$sql = "SELECT id, title, content,  name, create_at, update_at FROM posts WHERE id=$id";
$result = $conn->query($sql);
$result = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
</head>
<body>
    <h1>게시글 수정</h1>
    <?php if(isset($_SESSION['error'])) {
        echo "에러:".$_SESSION['error'];
        unset($_SESSION['error']);
    }
    ?> <br>
    <form action="edit_process.php" method="POST">
        <input type="hidden" id='id' name='id' value="<?= $id?>">
        ID : <?php echo $_SESSION['id'] ?> <br><br>
        name : <?php echo $_SESSION['name'] ?> <br><br>
        <label for="check_password"> PW 확인 :
            <input type="password" id="check_password" name="check_password" required>
        </label> <br><br>
        <label for="title"> Title :
            <input type="text" id="title" name="title" value="<?= $result['title']?>" required>
        </label> <br><br>
        <label for="content"> Content :
            <textarea id="content" name="content" rows="4" cols="50" required><?php echo $result['content'] ?></textarea>
        </label> <br><br>
        
        <input type="submit" value="수정완료">
    </form>
</body>
</html>