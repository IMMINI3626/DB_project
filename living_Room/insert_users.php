<?php
include 'db.php';

$users = [
    ['User1', 'user1@naver.com', '010-0000-0001', '$2b$12$l.Bpf9vIWxngZhVHItlJJOE1aOPbPhpGX0U4maGK..qQvEtpdOvLm', 'user'],
    ['User2', 'user2@naver.com', '010-0000-0002', '$2b$12$qRLYQ.IUu87S4Eg6hCUbLuerb.grc4e6A2O2IYtVOaFTHte4jUx.y', 'user'],
    ['User3', 'user3@naver.com', '010-0000-0003', '$2b$12$pzSFMlr408dQSbxhmBt/iOmgxjj.m3ZmWYOJ7s4VFxTBKjzUmKM5G', 'user'],
    ['User4', 'user4@naver.com', '010-0000-0004', '$2b$12$DaoFR9KXS98fNZI70CrZRufpZuh8G8htsP61shkib5ih6bOyINj.i', 'user'],
    ['User5', 'user5@naver.com', '010-0000-0005', '$2b$12$AgBAhFJcneBjwjYJV4pzYetqsufx8QtHmuFrvdn8xwq96x3zkEfOW', 'user'],
    ['User6', 'user6@naver.com', '010-0000-0006', '$2b$12$lEIL2jPiDhXnKukP9bofuulJpKOrqQurTOjvODsv/psK99Xud5j5y', 'user'],
    ['User7', 'user7@naver.com', '010-0000-0007', '$2b$12$/6IfEoV9AoR4T38Ufh9tUestjrbEUxrdfUb14op6XzUnR7JfMRxKW', 'user'],
    ['User8', 'user8@naver.com', '010-0000-0008', '$2b$12$qubf459t6JZPHDDdW53HbeXsHqQGEMHbhJ.MCCgxZu9/YNB4yBL5G', 'user'],
    ['User9', 'user9@naver.com', '010-0000-0009', '$2b$12$YRTWBzuTF4u3P0tEsAu7ReZcePSF0bDOn5kJkIUAy5W7mM2CY3QXa', 'user'],
    ['User10', 'user10@naver.com', '010-0000-0010', '$2b$12$GfNyTzdXhHet8Uj3149o3.bk9GUrbnXPRdajxE1czyAzdIIht/BBK', 'user'],
    ['User11', 'user11@naver.com', '010-0000-0011', '$2b$12$8oNzM/TzIt16RzkNLxbIiu8029LtuD08z4zyzRqIbC.X4CqM3fzw6', 'user'],
    ['User12', 'user12@naver.com', '010-0000-0012', '$2b$12$TaBzmCZrrS8YOH12P7./TuXQkZxuykUOGLhUmQqUN8B9TBrcrM8h.', 'user'],
    ['User13', 'user13@naver.com', '010-0000-0013', '$2b$12$Dnnohw8C23oFeVXq6lLU...C7nPGpAt9PlgMKoPBode81B6ETPwYG', 'user'],
    ['User14', 'user14@naver.com', '010-0000-0014', '$2b$12$/IrXL2yaV.T5TIPJSrRSnOOMqy7r8K8eWsZ3wMwO0WGuDfZnFizHG', 'user'],
    ['User15', 'user15@naver.com', '010-0000-0015', '$2b$12$bodn7qWGxjT1M8b45OSXreu4H2LSaFF8TFZr0jgUV3129uhaDqU8.', 'user'],
    ['User16', 'user16@naver.com', '010-0000-0016', '$2b$12$ES4ZaXiXZn/AHkjhFEdSBuwtcuCjYe2Ndj3RB2qCoQe8bLcL0hXR.', 'user'],
    ['User17', 'user17@naver.com', '010-0000-0017', '$2b$12$vmd6twwaVYESwoNFgBobgewYI.PdigC7ThqYXmOQ10h5UFdmkL.hW', 'user'],
    ['User18', 'user18@naver.com', '010-0000-0018', '$2b$12$IAXbja84LQN8VrVuzH6q9u.oGGJ8ZmOyTihs4h5RiKXh0VcthPQSq', 'user'],
    ['User19', 'user19@naver.com', '010-0000-0019', '$2b$12$oel/LZdMBlbjZvSd/7BbF.MeyY6zZolInCfY3/w5I51jR1Ef6oRaO', 'user'],
    ['User20', 'user20@naver.com', '010-0000-0020', '$2b$12$aF9dWeQ8ksI/FJgSMTLiFOdiYJTvyoQ1gjZnZ94df57N/u8Ii2jcm', 'user'],
    ['User21', 'user21@naver.com', '010-0000-0021', '$2b$12$TRXmIM75Dku7A8ioNxI35.u/nHtW5BfYm.OPLkfkWKC7snFiOWJVm', 'user'],
    ['User22', 'user22@naver.com', '010-0000-0022', '$2b$12$GeA06NsOII9XcYVT.5LVmur/qfAplrVvtu90ccjE54lgydeX4j.l2', 'user'],
    ['User23', 'user23@naver.com', '010-0000-0023', '$2b$12$t45SBMyY8lcYR4Xp5DCWGOd/Dp7MbMcX291CGk/So/QEfaXNPIj4.', 'user'],
    ['User24', 'user24@naver.com', '010-0000-0024', '$2b$12$2tIi5UZEByo6W3UgS4F6teFaO8c5cTxezOzgE1Z7JE2.swYoGGmGy', 'user'],
    ['User25', 'user25@naver.com', '010-0000-0025', '$2b$12$90a7zxs/GDeYpSE6VQ/QXeQ2.QJkswUsidJRdl6XZ2SSomepVgwzm', 'user'],
    ['User26', 'user26@naver.com', '010-0000-0026', '$2b$12$oSd/ueMjQESQn.4BbA5tIeZhEnfk1ZX6RQ3/x2DJiCf5vDXeDd7e.', 'user'],
    ['User27', 'user27@naver.com', '010-0000-0027', '$2b$12$CDAAwVEF07jJaQogqF1.h.EKbHMDcfLrxdvrPWEhpTThCUNUKprzK', 'user'],
    ['User28', 'user28@naver.com', '010-0000-0028', '$2b$12$QGY85I3p7MSjE0wGhwIdY.5O3UD0zsbbxY13W3VayELQGbGw3LcgS', 'user'],
    ['User29', 'user29@naver.com', '010-0000-0029', '$2b$12$xluS35LczlDGpwffrq248eZxIFke61SrLGWU0GKX/VgrWp1l9jAoy', 'user'],
    ['User30', 'user30@naver.com', '010-0000-0030', '$2b$12$8PhrrPuXtitM0oan13qIXOUyeYehblf3POU1/iK4qS0T5msyGOx0a', 'user'],
    ['User31', 'user31@naver.com', '010-0000-0031', '$2b$12$lAOPEtNILlB8BQC.wvvMLem37vS0yR4qA8DOAiw1dwAseW9bmt1Pa', 'user'],
    ['User32', 'user32@naver.com', '010-0000-0032', '$2b$12$oWR0h62gZd3H8vAnXtp0jusTQo9CmMi7oDzKUJHBhZjz98oxezAYu', 'user'],
    ['User33', 'user33@naver.com', '010-0000-0033', '$2b$12$zSMWPyXPW.Ry9qeB9R2b1O9j1aAOWqINM0BCPtCB.VuMjuUhgGage', 'user'],
    ['User34', 'user34@naver.com', '010-0000-0034', '$2b$12$HCwmAu7nQjpv9Qr8QYwTbeo6FYRQaKv56cQsaWe6ujhsyL96ph6/S', 'user'],
    ['User35', 'user35@naver.com', '010-0000-0035', '$2b$12$HSkNvuq3gL45OG7vpR/2p.iPaolbru4dw9vBozV9pyILP8JJlXKVC', 'user'],
    ['User36', 'user36@naver.com', '010-0000-0036', '$2b$12$OcZzYRlw5PBb0amVMVwypOM13VohGJyHKP.YGPPIK2eguYP1kT2ry', 'user'],
    ['User37', 'user37@naver.com', '010-0000-0037', '$2b$12$VSTot2GH826ExIFOJwOA5OQKWb7OkofnLBIH4T7UuOVNO4g9/rPYm', 'user'],
    ['User38', 'user38@naver.com', '010-0000-0038', '$2b$12$s3v9PEZrdoIStbNgdZEsR.VwD6G62gHlTSSo3JphMhO6uYSsZi3Q.', 'user'],
    ['User39', 'user39@naver.com', '010-0000-0039', '$2b$12$IQC1891yJkr.6.oZ49rfo.KtT1f7VttYPsoWLcHtQ0yrj/wYrW6bq', 'user'],
    ['User40', 'user40@naver.com', '010-0000-0040', '$2b$12$OGhAl1FPSZM.1Wk3/g1JPOTr5YAJH8QTn1or7TMnLcuh3h94/Nw7G', 'user'],
    ['User41', 'user41@naver.com', '010-0000-0041', '$2b$12$idS.wYuvDDFpn23OBM8L0.OlC1CnXNjj.JaPH9TGuKbTsZufZb7V2', 'user'],
    ['User42', 'user42@naver.com', '010-0000-0042', '$2b$12$4lrCM1OAPqsFkgLcmNR8SOrze35Jk8FQezaEnXobNROwYXQldEJUW', 'user'],
    ['User43', 'user43@naver.com', '010-0000-0043', '$2b$12$4JYkgAn/flrTgXape9vjieAGAIRpq06HzMVaQB1ueUgIXK2.Ob0r6', 'user'],
    ['User44', 'user44@naver.com', '010-0000-0044', '$2b$12$g1C69KNOyxJ2PK0jssdDHuzRHhnyp1sF1ugVQBPiCwxO3lxxwWvZq', 'user'],
    ['User45', 'user45@naver.com', '010-0000-0045', '$2b$12$UQ2sUrHBSWt71OLNm/XBnuvJfPZ6pSMMwmLfsjsQQoE6tmaIGn8Iu', 'user'],
    ['User46', 'user46@naver.com', '010-0000-0046', '$2b$12$xuMQNnOXQHZGyyW6LHQ6FuNeMXqX06J/eK2X9SDyTjRMSLOuPAb9y', 'user'],
    ['User47', 'user47@naver.com', '010-0000-0047', '$2b$12$lyifvDjnm09wRTzecbF/tOAQ9oTPrfCvmlPu4GVJXJOD7sonVNEUW', 'user'],
    ['User48', 'user48@naver.com', '010-0000-0048', '$2b$12$pN97DMrTH6A1Lkk6F0snsuEdB6slQYHQTdm6qbVshm/o1UWaHIKC6', 'user'],
    ['User49', 'user49@naver.com', '010-0000-0049', '$2b$12$axOn4PNUj2PX9mPoIePVte6Ab84ajXjiOv4RlF3FsafGdmDbDef7a', 'user'],
    ['User50', 'user50@naver.com', '010-0000-0050', '$2b$12$ENaey31o5maIYZtqqJ/yu.Tx7L.xdzQfQcLs4vWgYQtwLHBhb4LZq', 'user'],
    ['관리자', 'jeongmin@sch.ac.kr', '010-9999-9999', '$2b$12$EFASvrVM.LuqDvd.997tVuOWOK/YS4.1/LsGzGPC358/FaBEdTb/C', 'admin']
];

$stmt = $conn->prepare("INSERT INTO User (name, email, phone, password, user_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssss", $name, $email, $phone, $password, $user_type);

foreach ($users as $user) {
    list($name, $email, $phone, $password, $user_type) = $user;
    $stmt->execute();
}

$stmt->close();
$conn->close();
echo "삽입 완료";
?>