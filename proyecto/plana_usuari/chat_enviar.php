<?php
session_start();
require_once 'conexion.php';

$id_partida = $_POST['id_partida'] ?? 0;
$mensaje = trim($_POST['mensaje'] ?? '');
$id_user = $_SESSION['id_user'] ?? 0;

if ($id_partida <= 0 || empty($mensaje) || $id_user <= 0) {
    echo "Datos inválidos";
    exit;
}

// Verificar que la partida está activa
$stmt = $conn->prepare("SELECT estado FROM partida WHERE id_partida = ?");
$stmt->bind_param("i", $id_partida);
$stmt->execute();
$stmt->bind_result($estado);
if (!$stmt->fetch() || $estado !== 'activa') {
    echo "La partida no está activa";
    exit;
}
$stmt->close();

// Insertar mensaje
$stmt = $conn->prepare("INSERT INTO chat_partida (id_partida, id_user, mensaje, fecha) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $id_partida, $id_user, $mensaje);
if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al enviar el mensaje";
}
$stmt->close();
$conn->close();
