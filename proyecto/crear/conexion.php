<?php
$servername = "localhost";  // O el servidor que estés utilizando
$username = "root";         // El nombre de usuario de la base de datos
$password = "";             // La contraseña de la base de datos (si la tienes)
$dbname = "cluster_role"; // El nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
