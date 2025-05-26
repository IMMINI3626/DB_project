-- living_alone 데이터베이스 및 테이블 설계 (EER 다이어그램용)

CREATE DATABASE living_alone;
USE living_alone;

-- 사용자 테이블: 회원 정보 저장
CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,                     -- 사용자 고유 ID
    name VARCHAR(100) NOT NULL,                                 -- 사용자 이름
    email VARCHAR(100) UNIQUE NOT NULL,                         -- 이메일 (로그인 ID로 사용, 중복 불가)
    phone VARCHAR(20),                                          -- 연락처
    password VARCHAR(255) NOT NULL,                             -- 비밀번호
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 가입 일시
    user_type ENUM('user', 'admin') DEFAULT 'user'        -- 사용자 유형 (일반 or 관리자)
);


-- 자취방 테이블: 방 정보 저장
CREATE TABLE Room (
    room_id INT AUTO_INCREMENT PRIMARY KEY,                     -- 자취방 고유 ID
    user_id INT NOT NULL,                                       -- 등록한 사용자 ID
    room_name VARCHAR(100) NOT NULL,                            -- 자취방 이름
    location VARCHAR(255),                                      -- 위치 정보
    price DECIMAL(10, 2),                                       -- 월세 (숫자 + 소수점 둘째자리까지)
    size FLOAT,                                                 -- 평수
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 등록 일시
    description TEXT,                                           -- 상세 설명
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE -- 사용자 삭제 시 방도 같이 삭제
);

-- 옵션 테이블
CREATE TABLE Feature (
    feature_id INT AUTO_INCREMENT PRIMARY KEY,                  -- 옵션 고유 ID
    feature_name VARCHAR(100) UNIQUE NOT NULL                   -- 옵션 이름 (중복 불가)
);

-- 방-옵션 중간 테이블
CREATE TABLE RoomFeature (
    room_id INT NOT NULL,                                       -- 자취방 ID
    feature_id INT NOT NULL,                                    -- 옵션 ID
    PRIMARY KEY (room_id, feature_id),                          -- 복합 기본키로 중복 방지
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES Feature(feature_id) ON DELETE CASCADE
);

-- 리뷰 테이블: 사용자들이 남긴 후기
CREATE TABLE Review (
    user_id INT NOT NULL,                                       -- 작성자 ID
    room_id INT NOT NULL,                                       -- 대상 자취방 ID
    content TEXT,                                               -- 후기 내용
    rating INT CHECK (rating BETWEEN 1 AND 5),                  -- 별점 (1~5 사이)
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 작성일시
    PRIMARY KEY (user_id, room_id),                             -- 하나의 방당 하나의 후기만 작성 가능
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- 추천 테이블: 사용자들이 추천한 방과 사유
CREATE TABLE Recommendation (
    rec_id INT AUTO_INCREMENT PRIMARY KEY,                      -- 추천 고유 ID
    user_id INT NOT NULL,                                       -- 추천한 사용자
    room_id INT NOT NULL,                                       -- 추천 대상 자취방
    reason TEXT,                                                -- 추천 사유
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 추천한 시간
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- 좋아요 테이블
CREATE TABLE LikeRoom (
    user_id INT NOT NULL,                                       -- 좋아요 누른 사용자
    room_id INT NOT NULL,                                       -- 좋아요 대상 자취방
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 좋아요 누른 시간
    PRIMARY KEY (user_id, room_id),                             -- 같은 방에 두 번 좋아요 못 누르게 설정
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- 북마크 테이블: 관심 있는 자취방 저장
CREATE TABLE Bookmark (
    bookmark_id INT AUTO_INCREMENT PRIMARY KEY,                 -- 북마크 고유 ID
    user_id INT NOT NULL,                                       -- 북마크한 사용자
    room_id INT NOT NULL,                                       -- 북마크한 자취방
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 북마크한 시간
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- DESCRIBE User;
	