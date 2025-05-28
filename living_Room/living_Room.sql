-- 데이터베이스 생성 및 사용
CREATE DATABASE living_Room;
USE living_Room;

-- 사용자 테이블: 회원 계정 정보 저장
CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,                     -- 사용자 고유 ID
    name VARCHAR(100) NOT NULL,                                 -- 사용자 이름
    email VARCHAR(100) UNIQUE NOT NULL,                         -- 이메일 (중복 불가, 로그인 ID로 사용)
    phone VARCHAR(20),                                          -- 연락처
    password VARCHAR(255) NOT NULL,                             -- 로그인 비밀번호
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 가입 일시
    user_type ENUM('user', 'admin') DEFAULT 'user'              -- 사용자 유형 ('user': 일반, 'admin': 관리자)
);

-- 자취방 테이블: 방 상세 정보 저장
CREATE TABLE Room (
    room_id INT AUTO_INCREMENT PRIMARY KEY,                     -- 자취방 고유 ID
    user_id INT NOT NULL,                                       -- 등록한 사용자 ID (User 테이블 참조)
    room_name VARCHAR(100) NOT NULL,                            -- 자취방 이름
    location VARCHAR(255),                                      -- 위치 정보
    price DECIMAL(10, 2),                                       -- 월세 가격
    size FLOAT,                                                 -- 평수
    image_path TEXT,                                            -- 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 방 등록 일시
    description TEXT,                                           -- 상세 설명
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE -- 등록자 삭제 시 해당 방도 삭제됨
);

-- 옵션 테이블: 자취방에 제공되는 옵션 목록 저장
CREATE TABLE Feature (
    feature_id INT AUTO_INCREMENT PRIMARY KEY,                  -- 옵션 고유 ID
    feature_name VARCHAR(100) UNIQUE NOT NULL                   -- 옵션 이름
);

-- 자취방-옵션 관계 테이블 (N:M 매핑용)
CREATE TABLE RoomFeature (
    room_id INT NOT NULL,                                       -- 자취방 ID
    feature_id INT NOT NULL,                                    -- 옵션 ID
    PRIMARY KEY (room_id, feature_id),                          -- 복합 기본키
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES Feature(feature_id) ON DELETE CASCADE
);

-- 후기 테이블: 사용자들이 남긴 리뷰 정보 저장
CREATE TABLE Review (
    user_id INT NOT NULL,                                       -- 작성자 ID
    room_id INT NOT NULL,                                       -- 대상 자취방 ID
    content TEXT,                                               -- 후기 텍스트
    rating INT CHECK (rating BETWEEN 1 AND 5),                  -- 별점 (1~5점 사이만 허용)
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 후기 작성일시
    PRIMARY KEY (user_id, room_id),                             -- 하나의 유저는 하나의 방에만 한 번 후기 작성 가능
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- 추천 테이블
CREATE TABLE Recommendation (
    rec_id INT AUTO_INCREMENT PRIMARY KEY,                      -- 추천 고유 ID
    user_id INT NOT NULL,                                       -- 추천한 사용자
    room_id INT NOT NULL,                                       -- 추천 대상 자취방
    reason TEXT,                                                -- 추천 사유
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 추천 등록 시각
    UNIQUE (user_id, room_id),                                  -- 같은 유저가 같은 방 중복 추천 불가
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- 좋아요 테이블: 자취방 좋아요 기록
CREATE TABLE Likes (
    user_id INT NOT NULL,                                       -- 좋아요 누른 사용자
    room_id INT NOT NULL,                                       -- 좋아요 누른 자취방
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 좋아요 누른 시각
    PRIMARY KEY (user_id, room_id),                             -- 같은 유저가 같은 방 좋아요 한 번만 가능
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

-- 북마크 테이블
CREATE TABLE Bookmark (
    user_id INT NOT NULL,                                       -- 북마크한 사용자
    room_id INT NOT NULL,                                       -- 북마크한 자취방
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,              -- 북마크한 시각
    PRIMARY KEY (user_id, room_id),                             -- 같은 유저가 같은 방 북마크 1회만 가능
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES Room(room_id) ON DELETE CASCADE
);

INSERT INTO Feature (feature_name) VALUES
('에어컨'),
('냉장고'),
('세탁기'),
('엘리베이터'),
('주차장'),
('인터넷'),
('가스레인지'),
('침대'),
('책상'),
('건조대'),
('신발장'),
('티비');


INSERT INTO Room (user_id, room_name, location, price, size, image_path, description) VALUES
(1, '원룸 1', '서울 마포구 서교동 1', 450000, 12.5, '/images/room1.jpg', '깔끔한 원룸입니다.'),
(2, '원룸 2', '서울 강남구 역삼동 2', 700000, 15.0, '/images/room2.jpg', '역삼역 인근 원룸.'),
(3, '원룸 3', '서울 서초구 방배동 3', 600000, 14.0, '/images/room3.jpg', '편리한 방배동 원룸.'),
(4, '원룸 4', '서울 성동구 성수동 4', 480000, 13.0, '/images/room4.jpg', '성수동 깔끔한 원룸.'),
(5, '원룸 5', '서울 동작구 상도동 5', 420000, 11.0, '/images/room5.jpg', '상도동 아늑한 원룸.'),
(6, '원룸 6', '서울 관악구 신림동 6', 400000, 10.5, '/images/room6.jpg', '신림역 인근 원룸.'),
(7, '원룸 7', '서울 광진구 자양동 7', 460000, 12.0, '/images/room7.jpg', '자양동 원룸입니다.'),
(8, '원룸 8', '서울 영등포구 영등포동 8', 550000, 14.5, '/images/room8.jpg', '영등포역 근처 원룸.'),
(9, '원룸 9', '서울 강서구 화곡동 9', 430000, 11.3, '/images/room9.jpg', '화곡동 아파트형 원룸.'),
(10, '원룸 10', '서울 마포구 망원동 10', 480000, 13.2, '/images/room10.jpg', '망원동 원룸입니다.'),
(11, '원룸 11', '서울 종로구 삼청동 11', 500000, 13.7, '/images/room11.jpg', '삼청동 조용한 원룸.'),
(12, '원룸 12', '서울 중구 을지로 12', 520000, 14.0, '/images/room12.jpg', '을지로 근처 원룸.'),
(13, '원룸 13', '서울 강북구 미아동 13', 400000, 11.5, '/images/room13.jpg', '미아동 원룸입니다.'),
(14, '원룸 14', '서울 서대문구 신촌동 14', 470000, 12.6, '/images/room14.jpg', '신촌역 인근 원룸.'),
(15, '원룸 15', '서울 송파구 잠실동 15', 620000, 15.5, '/images/room15.jpg', '잠실동 원룸.'),
(16, '원룸 16', '서울 동대문구 청량리동 16', 450000, 12.1, '/images/room16.jpg', '청량리동 원룸입니다.'),
(17, '원룸 17', '서울 강남구 삼성동 17', 700000, 16.0, '/images/room17.jpg', '삼성동 고급 원룸.'),
(18, '원룸 18', '서울 강동구 길동 18', 430000, 11.7, '/images/room18.jpg', '길동 원룸.'),
(19, '원룸 19', '서울 관악구 봉천동 19', 400000, 10.8, '/images/room19.jpg', '봉천동 원룸입니다.'),
(20, '원룸 20', '서울 금천구 독산동 20', 390000, 10.5, '/images/room20.jpg', '독산동 원룸.'),
(21, '원룸 21', '서울 마포구 합정동 21', 480000, 13.0, '/images/room21.jpg', '합정동 원룸.'),
(22, '원룸 22', '서울 노원구 중계동 22', 420000, 11.2, '/images/room22.jpg', '중계동 원룸.'),
(23, '원룸 23', '서울 서초구 반포동 23', 650000, 16.2, '/images/room23.jpg', '반포동 원룸.'),
(24, '원룸 24', '서울 동작구 대방동 24', 410000, 11.4, '/images/room24.jpg', '대방동 원룸.'),
(25, '원룸 25', '서울 송파구 문정동 25', 500000, 13.5, '/images/room25.jpg', '문정동 원룸.'),
(26, '원룸 26', '서울 강서구 등촌동 26', 420000, 11.0, '/images/room26.jpg', '등촌동 원룸.'),
(27, '원룸 27', '서울 중랑구 면목동 27', 410000, 10.7, '/images/room27.jpg', '면목동 원룸.'),
(28, '원룸 28', '서울 광진구 구의동 28', 460000, 12.3, '/images/room28.jpg', '구의동 원룸.'),
(29, '원룸 29', '서울 강북구 수유동 29', 400000, 10.9, '/images/room29.jpg', '수유동 원룸.'),
(30, '원룸 30', '서울 금천구 시흥동 30', 390000, 10.6, '/images/room30.jpg', '시흥동 원룸.'),
(31, '원룸 31', '서울 용산구 이태원동 31', 480000, 13.1, '/images/room31.jpg', '이태원동 원룸.'),
(32, '원룸 32', '서울 성동구 성수동 32', 450000, 12.5, '/images/room32.jpg', '성수동 원룸.'),
(33, '원룸 33', '서울 강남구 논현동 33', 700000, 15.7, '/images/room33.jpg', '논현동 원룸.'),
(34, '원룸 34', '서울 마포구 망원동 34', 460000, 12.4, '/images/room34.jpg', '망원동 원룸.'),
(35, '원룸 35', '서울 강서구 화곡동 35', 420000, 11.3, '/images/room35.jpg', '화곡동 원룸.'),
(36, '원룸 36', '서울 노원구 상계동 36', 400000, 10.9, '/images/room36.jpg', '상계동 원룸.'),
(37, '원룸 37', '서울 서초구 방배동 37', 650000, 16.0, '/images/room37.jpg', '방배동 원룸.'),
(38, '원룸 38', '서울 영등포구 당산동 38', 480000, 13.2, '/images/room38.jpg', '당산동 원룸.'),
(39, '원룸 39', '서울 동대문구 답십리동 39', 410000, 11.0, '/images/room39.jpg', '답십리동 원룸.'),
(40, '원룸 40', '서울 광진구 중곡동 40', 420000, 11.3, '/images/room40.jpg', '중곡동 원룸.'),
(41, '원룸 41', '서울 관악구 봉천동 41', 400000, 10.8, '/images/room41.jpg', '봉천동 원룸.'),
(42, '원룸 42', '서울 마포구 합정동 42', 460000, 12.5, '/images/room42.jpg', '합정동 원룸.'),
(43, '원룸 43', '서울 노원구 공릉동 43', 390000, 10.5, '/images/room43.jpg', '공릉동 원룸.'),
(44, '원룸 44', '서울 서대문구 홍제동 44', 420000, 11.2, '/images/room44.jpg', '홍제동 원룸.'),
(45, '원룸 45', '서울 중구 명동 45', 700000, 15.5, '/images/room45.jpg', '명동 원룸.'),
(46, '원룸 46', '서울 동작구 사당동 46', 450000, 12.3, '/images/room46.jpg', '사당동 원룸.'),
(47, '원룸 47', '서울 강남구 대치동 47', 720000, 16.0, '/images/room47.jpg', '대치동 원룸.'),
(48, '원룸 48', '서울 송파구 가락동 48', 430000, 11.5, '/images/room48.jpg', '가락동 원룸.'),
(49, '원룸 49', '서울 은평구 불광동 49', 400000, 10.7, '/images/room49.jpg', '불광동 원룸.'),
(50, '원룸 50', '서울 강동구 둔촌동 50', 420000, 11.1, '/images/room50.jpg', '둔촌동 원룸.');

UPDATE User
SET user_type = 'admin' WHERE email = 'jeongmin@sch.ac.kr';