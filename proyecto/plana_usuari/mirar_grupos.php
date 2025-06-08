<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    // Si no hay sesión iniciada, redirige al login
    header("Location: /Cluster_Role/proyecto/login/login.html");
    exit();
}

include("conexion.php");


$id_usuario = $_SESSION['id_user'];

$query = "
    SELECT id_group, group_name, profile_photo
    FROM `grupo`
    WHERE id_creador = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$grupos = [];
while ($row = $resultado->fetch_assoc()) {
    $grupos[] = $row;
}


