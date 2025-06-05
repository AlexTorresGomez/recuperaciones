<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: perfil_usuario.php');
    exit();
}

$id_usuario = $_SESSION['id_user'];
$id_amigo = $_POST['id_amigo'] ?? null;

if (!$id_amigo || $id_usuario == $id_amigo) {
    echo "Solicitud inválida.";
    exit();
}

// Comprobar si ya son amigos
$stmt = $conn->prepare("SELECT * FROM usuario_amigos WHERE id_usuario = ? AND id_amigo = ?");
$stmt->bind_param("ii", $id_usuario, $id_amigo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt_insert = $conn->prepare("INSERT INTO usuario_amigos (id_usuario, id_amigo) VALUES (?, ?), (?, ?)");
    $stmt_insert->bind_param("iiii", $id_usuario, $id_amigo, $id_amigo, $id_usuario);
    $stmt_insert->execute();
}

// Redirige de nuevo al perfil
header("Location: usuario.php?id=$id_amigo");
exit();
?>

