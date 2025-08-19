-- 데이터베이스 생성 (존재하지 않으면)
CREATE DATABASE IF NOT EXISTS gsc CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- gsc 데이터베이스 사용
USE gsc;

-- users 테이블 생성
CREATE TABLE IF NOT EXISTS users (
    no INT AUTO_INCREMENT PRIMARY KEY, -- 순번 (자동 증가)
    id VARCHAR(20) NOT NULL UNIQUE, -- 학번 (유일)
    password VARCHAR(100) NOT NULL, -- 해시된 비밀번호 (해싱 필요)
    name VARCHAR(50) NOT NULL -- 이름
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- posts 테이블 생성
CREATE TABLE IF NOT EXISTS posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY, -- 게시글 고유 번호
    writer VARCHAR(50) NOT NULL, -- 작성자
    post_pw VARCHAR(100) NOT NULL, -- 게시글 비밀번호 (수정, 삭제)
    title VARCHAR(255) NOT NULL, -- 게시글 제목
    content TEXT NOT NULL -- 게시글 내용
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;