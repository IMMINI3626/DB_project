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
(1, '원룸 1', '천안시 서북구 두정동 37', 500000, 13, '/images/room1.jpg', '두정동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(2, '원룸 2', '천안시 동남구 신방동 48', 450000, 13, '/images/room2.jpg', '신방동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(3, '원룸 3', '천안시 동남구 신방동 77', 440000, 12, '/images/room3.jpg', '신방동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(4, '원룸 4', '천안시 동남구 원성동 56', 390000, 12, '/images/room4.jpg', '원성동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(5, '원룸 5', '천안시 동남구 원성동 97', 420000, 15, '/images/room5.jpg', '원성동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(6, '원룸 6', '천안시 서북구 쌍용동 8', 460000, 14, '/images/room6.jpg', '쌍용동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(7, '원룸 7', '천안시 동남구 용곡동 20', 360000, 9, '/images/room7.jpg', '용곡동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(8, '원룸 8', '천안시 동남구 신방동 17', 450000, 13, '/images/room8.jpg', '신방동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(9, '원룸 9', '천안시 동남구 원성동 35', 390000, 12, '/images/room9.jpg', '원성동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(10, '원룸 10', '천안시 서북구 성성동 7', 490000, 12, '/images/room10.jpg', '성성동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(11, '원룸 11', '천안시 서북구 두정동 22', 470000, 10, '/images/room11.jpg', '두정동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(12, '원룸 12', '천안시 동남구 원성동 80', 360000, 9, '/images/room12.jpg', '원성동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(13, '원룸 13', '천안시 동남구 봉명동 84', 400000, 13, '/images/room13.jpg', '봉명동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(14, '원룸 14', '천안시 동남구 청수동 59', 400000, 14, '/images/room14.jpg', '청수동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(15, '원룸 15', '천안시 동남구 청당동 68', 400000, 13, '/images/room15.jpg', '청당동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(16, '원룸 16', '천안시 서북구 쌍용동 17', 460000, 14, '/images/room16.jpg', '쌍용동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(17, '원룸 17', '천안시 서북구 두정동 76', 470000, 10, '/images/room17.jpg', '두정동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(18, '원룸 18', '천안시 동남구 봉명동 78', 360000, 8, '/images/room18.jpg', '봉명동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(19, '원룸 19', '천안시 동남구 봉명동 15', 370000, 10, '/images/room19.jpg', '봉명동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(20, '원룸 20', '천안시 동남구 청룡동 56', 350000, 8, '/images/room20.jpg', '청룡동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(21, '원룸 21', '천안시 동남구 청수동 100', 360000, 9, '/images/room21.jpg', '청수동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(22, '원룸 22', '천안시 동남구 봉명동 12', 410000, 14, '/images/room22.jpg', '봉명동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(23, '원룸 23', '천안시 동남구 청수동 40', 360000, 9, '/images/room23.jpg', '청수동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(24, '원룸 24', '천안시 동남구 용곡동 57', 390000, 12, '/images/room24.jpg', '용곡동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(25, '원룸 25', '천안시 서북구 두정동 14', 510000, 14, '/images/room25.jpg', '두정동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(26, '원룸 26', '천안시 서북구 쌍용동 95', 400000, 8, '/images/room26.jpg', '쌍용동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(27, '원룸 27', '천안시 서북구 성성동 92', 490000, 12, '/images/room27.jpg', '성성동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(28, '원룸 28', '천안시 동남구 청룡동 59', 410000, 14, '/images/room28.jpg', '청룡동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(29, '원룸 29', '천안시 서북구 성성동 92', 450000, 8, '/images/room29.jpg', '성성동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(30, '원룸 30', '천안시 동남구 봉명동 52', 360000, 9, '/images/room30.jpg', '봉명동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(31, '원룸 31', '천안시 동남구 용곡동 34', 390000, 12, '/images/room31.jpg', '용곡동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(32, '원룸 32', '천안시 동남구 청당동 51', 370000, 10, '/images/room32.jpg', '청당동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(33, '원룸 33', '천안시 동남구 원성동 23', 400000, 14, '/images/room33.jpg', '원성동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(34, '원룸 34', '천안시 동남구 청룡동 95', 420000, 15, '/images/room34.jpg', '청룡동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(35, '원룸 35', '천안시 서북구 백석동 29', 370000, 10, '/images/room35.jpg', '백석동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(36, '원룸 36', '천안시 동남구 용곡동 50', 370000, 10, '/images/room36.jpg', '용곡동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(37, '원룸 37', '천안시 동남구 원성동 8', 360000, 9, '/images/room37.jpg', '원성동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(38, '원룸 38', '천안시 동남구 원성동 79', 410000, 14, '/images/room38.jpg', '원성동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.'),
(39, '원룸 39', '천안시 동남구 원성동 15', 390000, 12, '/images/room39.jpg', '원성동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(40, '원룸 40', '천안시 서북구 불당동 17', 480000, 11, '/images/room40.jpg', '불당동 지역의 원룸입니다. 생활 편의시설이 인접한 곳입니다.'),
(41, '원룸 41', '천안시 서북구 쌍용동 32', 400000, 8, '/images/room41.jpg', '쌍용동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(42, '원룸 42', '천안시 서북구 성성동 73', 490000, 12, '/images/room42.jpg', '성성동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(43, '원룸 43', '천안시 동남구 용곡동 55', 360000, 9, '/images/room43.jpg', '용곡동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(44, '원룸 44', '천안시 동남구 용곡동 15', 370000, 10, '/images/room44.jpg', '용곡동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(45, '원룸 45', '천안시 동남구 원성동 18', 390000, 12, '/images/room45.jpg', '원성동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(46, '원룸 46', '천안시 동남구 청룡동 32', 360000, 9, '/images/room46.jpg', '청룡동 지역의 원룸입니다. 조용하고 편리한 곳입니다.'),
(47, '원룸 47', '천안시 서북구 성성동 35', 480000, 11, '/images/room47.jpg', '성성동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(48, '원룸 48', '천안시 동남구 봉명동 10', 380000, 11, '/images/room48.jpg', '봉명동 지역의 원룸입니다. 거주 만족도가 높은 곳입니다.'),
(49, '원룸 49', '천안시 서북구 쌍용동 41', 420000, 10, '/images/room49.jpg', '쌍용동 지역의 원룸입니다. 인프라가 좋은 곳입니다.'),
(50, '원룸 50', '천안시 동남구 신방동 82', 410000, 9, '/images/room50.jpg', '신방동 지역의 원룸입니다. 주거 환경이 쾌적한 곳입니다.');

