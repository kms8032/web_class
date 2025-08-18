<?php
# 세션 시작
session_start();

# 데이터베이스 가져오기
require_once("../db_config.php");

$now_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; #max() ()안의 값들 중에서 제 큰 값을 반환
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board</title>
</head>
<body>
    <!--표 생성 ( 번호, 제목, 작성일, 수정 )-->
    <table style="width=100%; border=1px solid #000">
        <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성일</th>
            <th>수정일</th>
        </tr>
        <!-- php에서 한 페이지에 게시글 5개씩 가져오기 -->
         <?php
            # db 연결
            $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

            # 연결 실패 시
            if ($conn->connect_error) {
                $_SESSION['error'] = "데이터베이스 연결 실패";
                header("Location: list.php");
                exit;
            }

            # 쿼리
            $sql = "SELECT count(*) FROM posts";
            $result = $conn->query($sql);
            $result = $result->fetch_all();
            $total_posts = $result[0][0];

            $number = 5;
            $offset = ($now_page - 1) * $number;

            $sql = "SELECT id, title, create_at, update_at FROM posts ORDER BY create_at DESC, id DESC LIMIT $number OFFSET $offset";
            /*
             * 데이터베이스는 순서 보장하지 않음 : ORDER BY가 없으면 결과는 "집합"이고, 어떤 순소롤 보내줄지는 DB가 마음대로 함
             * 배열로 다 받아서 인덱스로 표시하더라도 "정렬 기준"이 없으면 행들의 전체 순서가 매번 달라짐
             * 
             * PHP 로직으로 하면 문제점
             * 데이터가 커지면 메모리/속도가 확 나빠짐
             * DB 인덱스 활용 못함 -> DB가 정렬하는 게 훨씬 효율적
             * 동시성 상황에서 한 번의 쿼리로 정렬+슬라이스 하는 편이 일관성이 좋음 
             */
            $result = $conn->query($sql);
            $array = $result->fetch_all(MYSQLI_ASSOC);


            $total_page = ceil($total_posts / $number);
            
            # $array 를 활용하여 게시글 띄우기
            for ( $x=1; $x<=$number; $x++){
                $no = $x+$offset;
                if(isset($array[$x-1]['id'])){
                    $post_id = $array[$x-1]['id'];
                    echo "<tr>";
                    echo "<th>"."<a href='view.php?id=$post_id'>".$no."</a>"."</th>";
                    echo "<th>".$array[$x-1]['title']."</th>";
                    echo "<th>".$array[$x-1]['create_at']."</th>";
                    echo "<th>".$array[$x-1]['update_at']."</th>";
                    echo "</tr>";
                }
            }
            
        ?>
    </table>
    <?php
        for ( $x=1; $x<=$total_page; $x++){
            echo "<a href='list.php?page=$x'>".$x."</a>";
            echo "<br>";
        }
    ?>

    <form action="write.php">
        <input type="submit" value="작성하기">
    </form>
    <form action="../welcome.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>
<style>
    table, th, td {
        border : 1px solid black;
    }
    table.center {
        margin-left: auto;
        margin-right: auto;
    }
</style>