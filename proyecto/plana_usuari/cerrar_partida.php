<?php
session_start();
require_once 'conexion.php';

$id_partida = $_POST['id_partida'] ?? 0;
$id_user_actual = $_SESSION['id_user'] ?? 0;

if ($id_partida <= 0 || $id_user_actual <= 0) {
    echo "Datos inválidos";
    exit;
}

// Verificar que el usuario es creador de la partida
$stmt = $conn->prepare("SELECT id_creador FROM partida WHERE id_partida = ?");
$stmt->bind_param("i", $id_partida);
$stmt->execute();
$stmt->bind_result($id_creador);
if (!$stmt->fetch()) {
    echo "Partida no encontrada";
    exit;
}
$stmt->close();

if ($id_creador != $id_user_actual) {
    echo "No tienes permiso para cerrar esta partida";
    exit;
}

// Actualizar estado a cerrada y fecha_fin
$stmt = $conn->prepare("UPDATE partida SET estado = 'cerrada', fecha_fin = NOW() WHERE id_partida = ?");
$stmt->bind_param("i", $id_partida);
if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al cerrar la partida";
}
$stmt->close();
$conn->close();
