CREATE DATABASE Ropales;
USE Ropales;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_es VARCHAR(100),
    nombre_en VARCHAR(100),
    precio DECIMAL(6,2),
    imagen VARCHAR(255)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50),
    password VARCHAR(255),
    rol ENUM('admin','normal')
);

select * from productos;
