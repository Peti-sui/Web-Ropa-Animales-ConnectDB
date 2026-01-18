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

CREATE TABLE listaDeseos (
    id INT(2) PRIMARY KEY,
    nombre_es VARCHAR(100),
    nombre_en VARCHAR(100),
    precio DECIMAL(6,2)
);

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
	fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre_es VARCHAR(100),
    nombre_en VARCHAR(100),
    cantidad INT(3),
    precio_total DECIMAL(6,2)
);

truncate table compras;
select * from compras;
select * from productos;

INSERT INTO productos (nombre_es, nombre_en, precio, imagen) VALUES
-- GATOS
('Chaqueta marrón con camisa vaquera y pajarita para gato', 'Brown Jacket with Denim Shirt and Bow Tie for Cat', 24.99, './src/images/gatos/Gato1.png'),
('Abrigo azul marino con bufanda roja para gato', 'Navy Coat with Red Scarf for Cat', 26.50, './src/images/gatos/Gato2.png'),
('Chaqueta acolchada rosa y azul para gato', 'Pink and Blue Puffer Jacket for Cat', 29.90, './src/images/gatos/Gato3.png'),
('Parka verde con gorro de lana para gato', 'Green Parka with Knit Beanie for Cat', 31.00, './src/images/gatos/Gato4.png'),
('Chaqueta de pana con sudadera naranja y gafas para gato', 'Corduroy Jacket with Orange Hoodie and Glasses for Cat', 34.75, './src/images/gatos/Gato5.png'),

-- PERROS
('Chaqueta marrón con sudadera naranja y gafas para perro', 'Brown Jacket with Orange Hoodie and Sunglasses for Dog', 27.99, './src/images/perros/Perro1.png'),
('Abrigo verde con bufanda de punto para perro', 'Green Coat with Knit Scarf for Dog', 28.50, './src/images/perros/Perro2.png'),
('Chaqueta acolchada rosa y azul para perro', 'Pink and Blue Puffer Jacket for Dog', 32.90, './src/images/perros/Perro3.png'),
('Abrigo verde con bufanda de cuadros para perro', 'Green Coat with Plaid Scarf for Dog', 30.00, './src/images/perros/Perro4.png'),
('Parka verde con sudadera amarilla y gafas para perro', 'Green Parka with Yellow Hoodie and Glasses for Dog', 33.75, './src/images/perros/Perro5.png'),

-- HÁMSTERS
('Chaqueta marrón con sudadera mostaza y gafas para hámster', 'Brown Jacket with Mustard Hoodie and Glasses for Hamster', 14.99, './src/images/hamsters/Hamster1.png'),
('Abrigo verde con gorro y bufanda para hámster', 'Green Coat with Beanie and Scarf for Hamster', 15.50, './src/images/hamsters/Hamster2.png'),
('Chaqueta acolchada rosa y azul para hámster', 'Pink and Blue Puffer Jacket for Hamster', 16.90, './src/images/hamsters/Hamster3.png'),
('Abrigo azul marino con bufanda naranja para hámster', 'Navy Coat with Orange Scarf for Hamster', 15.00, './src/images/hamsters/Hamster4.png'),
('Chaqueta marrón con camisa vaquera y pajarita para hámster', 'Brown Jacket with Denim Shirt and Bow Tie for Hamster', 17.25, './src/images/hamsters/Hamster5.png');


