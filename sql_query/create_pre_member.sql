CREATE TABLE pre_member(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    urltoken VARCHAR(128) NOT NULL,
    mail VARCHAR(50) NOT NULL,
    date DATETIME NOT NULL,
    flag TINYINT(1) NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;


