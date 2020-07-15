-- 質問を記録するテーブル作成SQL
CREATE TABLE q_and_a_table(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(200),
    answer VARCHAR(200),
    states BOOLEAN
);