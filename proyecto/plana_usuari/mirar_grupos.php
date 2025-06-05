<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexion.php';

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


