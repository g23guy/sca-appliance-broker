CREATE USER 'sdbroker'@'localhost' IDENTIFIED BY PASSWORD '*8702E7FE2CCE5AE7E6A3A938A31F6B1DF58F3E6A';
CREATE USER 'sdbroker'@'127.0.0.1' IDENTIFIED BY PASSWORD '*8702E7FE2CCE5AE7E6A3A938A31F6B1DF58F3E6A';
CREATE USER 'sdbroker'@'::1' IDENTIFIED BY PASSWORD '*8702E7FE2CCE5AE7E6A3A938A31F6B1DF58F3E6A';
CREATE USER 'sdagent'@'localhost' IDENTIFIED BY PASSWORD '*915864856F9281AFB9BA3A950E5D5E3777D09F61';
CREATE USER 'sdagent'@'127.0.0.1' IDENTIFIED BY PASSWORD '*915864856F9281AFB9BA3A950E5D5E3777D09F61';
CREATE USER 'sdagent'@'::1' IDENTIFIED BY PASSWORD '*915864856F9281AFB9BA3A950E5D5E3777D09F61';
CREATE USER 'sdagent'@'%' IDENTIFIED BY PASSWORD '*915864856F9281AFB9BA3A950E5D5E3777D09F61';
GRANT ALL PRIVILEGES ON `ServerDiagnostics` . *  TO 'sdbroker'@'localhost';
GRANT ALL PRIVILEGES ON `ServerDiagnostics` . *  TO 'sdbroker'@'127.0.0.1';
GRANT ALL PRIVILEGES ON `ServerDiagnostics` . *  TO 'sdbroker'@'::1';
GRANT LOCK TABLES, SELECT, INSERT, UPDATE, DELETE ON `ServerDiagnostics` . * TO 'sdagent'@'localhost';
GRANT LOCK TABLES, SELECT, INSERT, UPDATE, DELETE ON `ServerDiagnostics` . * TO 'sdagent'@'127.0.0.1';
GRANT LOCK TABLES, SELECT, INSERT, UPDATE, DELETE ON `ServerDiagnostics` . * TO 'sdagent'@'::1';
GRANT LOCK TABLES, SELECT, INSERT, UPDATE, DELETE ON `ServerDiagnostics` . * TO 'sdagent'@'%';

