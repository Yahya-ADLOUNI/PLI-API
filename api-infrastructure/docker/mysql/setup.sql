CREATE DATABASE IF NOT EXISTS laravel;
CREATE DATABASE IF NOT EXISTS archive;

-- create the users for each database
CREATE USER IF NOT EXISTS 'pliuser'@'%' IDENTIFIED BY 'pliuser';

GRANT CREATE, ALTER, INDEX, LOCK TABLES, REFERENCES, UPDATE, DELETE, DROP, SELECT, INSERT ON `laravel`.* TO 'pliuser'@'%';
GRANT CREATE, ALTER, INDEX, LOCK TABLES, REFERENCES, UPDATE, DELETE, DROP, SELECT, INSERT ON `archive`.* TO 'pliuser'@'%';

FLUSH PRIVILEGES;
