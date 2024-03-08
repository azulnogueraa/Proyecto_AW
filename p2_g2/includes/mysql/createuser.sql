CREATE USER 'learnique'@'%' IDENTIFIED BY 'learnique';
GRANT ALL PRIVILEGES ON `learnique`.* TO 'learnique'@'%';

CREATE USER 'learnique'@'localhost' IDENTIFIED BY 'learnique';
GRANT ALL PRIVILEGES ON `learnique`.* TO 'learnique'@'localhost';