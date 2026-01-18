# Web Ropa para Animales

Aplicación web desarrollada en **PHP** que simula una tienda en línea de ropa para animales.
El proyecto implementa autenticación de usuarios, carrito de compras, lista de productos deseados y conexión a una base de datos **MySQL**.

---

## Objetivo del Proyecto

Desarrollar un conjunto de páginas web que integren el uso de bases de datos para almacenar y gestionar la información de la aplicación, permitiendo que los cambios realizados en la web queden registrados y puedan ser reutilizados.

---

## Conceptos Aplicados

* Conexión a base de datos MySQL
* Manejo de sesiones (usuario y contraseña)
* Almacenamiento y recuperación de información
* Uso de imágenes desde la base de datos
* Registro de modificaciones en la aplicación
* Organización de un proyecto web dinámico

---

## Funcionalidades

* Inicio y cierre de sesión de usuarios
* Visualización de productos
* Carrito de compras
* Lista de productos deseados
* Preferencias del usuario

---

## Tecnologías Utilizadas

* PHP
* MySQL
* HTML5
* CSS3
* Apache (XAMPP)

---

## Instalación

1. Clonar el repositorio:

   ```bash
   git clone https://github.com/usuario/Web-Ropa-Animales-ConnectDB.git
   ```

2. Copiar el proyecto en el directorio `htdocs` del servidor local.

3. Iniciar Apache y MySQL desde XAMPP.

4. Crear la base de datos en phpMyAdmin con el nombre:

   ```
   Ropales
   ```

5. Configurar la conexión en el archivo:

   ```
   Pagina/config/conexion.php
   ```

   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $db = "Ropales";
   ```

6. Acceder al proyecto desde el navegador:

   ```
   http://localhost/Web-Ropa-Animales-ConnectDB-main/Pagina/login.php
   ```

---
   
## Estructura General

```
Pagina/
├── actions/
├── config/
├── DB/
├── imagenes_db/
├── includes/
├── src/
├── carrito.php
├── eliminar.php
├── guardar.php
├── listaDeseados.php
└── otros archivos PHP
```

---

## Autores

<div align="center">

| Rau00 | Peti-sui (Kevin) |
| :---: | :---: |
| <img src="https://i.postimg.cc/FzXKcZGS/dragonite-esp.png" width="450"> | <img src="https://i.postimg.cc/nzBFHmZ0/kindpng-246018.png" width="320"> |

</div>




