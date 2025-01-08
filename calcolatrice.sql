CREATE DATABASE IF NOT EXISTS calcolatrice_db;

USE calcolatrice_db;

CREATE TABLE calcoli (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero1 FLOAT NOT NULL,
    numero2 FLOAT NOT NULL,
    operazione VARCHAR(50) NOT NULL,
    risultato VARCHAR(255) NOT NULL,
    data_calcolo TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
