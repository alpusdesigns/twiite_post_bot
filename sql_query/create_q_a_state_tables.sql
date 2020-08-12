CREATE TABLE q_and_a_tables (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user  varchar(50) DEFAULT NULL,
  question_id int(10) NOT NULL,
  question varchar(200) DEFAULT NULL,
  answer varchar(200) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;


