<?php
session_start();
include 'conexion.php';

$id_usuario = $_SESSION['id_user'] ?? null;
$id_amigo = $_POST['id_amigo'] ?? null;

if ($id_usuario && $id_amigo) {
    $stmt = $conn->prepare("DELETE FROM usuario_amigos WHERE id_usuario = ? AND id_amigo = ?");
    $stmt->bind_param("ii", $id_usuario, $id_amigo);
    $stmt->execute();
    
    // Opcional: también eliminar la relación inversa si existe (amistad mutua)
    $stmt = $conn->prepare("DELETE FROM usuario_amigos WHERE id_usuario = ? AND id_amigo = ?");
    $stmt->bind_param("ii", $id_amigo, $id_usuario);
    $stmt->execute();
}

// Redirige de nuevo a la página de amigos
header("Location: ver_amigos.php"); // Cambia esto por el nombre real de tu archivo
exit;
