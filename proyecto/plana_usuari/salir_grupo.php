<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id_user'])) {
    die("Debes iniciar sesión.");
}

$id_user = $_SESSION['id_user'];

if (isset($_GET['id'])) {
    $id_group = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM user_group WHERE id_user = ? AND id_group = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("ii", $id_user, $id_group);
    $stmt->execute();
    $stmt->close();

    header("Location: grupos.php");
    exit;
}
?>

