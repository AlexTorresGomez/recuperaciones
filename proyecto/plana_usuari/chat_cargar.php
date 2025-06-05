<?php
session_start();
require_once 'conexion.php';

$id_partida = isset($_GET['id_partida']) ? intval($_GET['id_partida']) : 0;

if ($id_partida <= 0) {
    die("Partida inválida");
}

// Obtener mensajes con usuario
$sql = "SELECT c.mensaje, u.user_name, c.fecha FROM chat_partida c JOIN usuarios u ON c.id_user = u.id_user WHERE c.id_partida = ? ORDER BY c.fecha ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_partida);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $nombre = htmlspecialchars($row['user_name']);
    $mensaje = htmlspecialchars($row['mensaje']);
    $fecha = $row['fecha'];
    echo "<p><strong>{$nombre}</strong> ({$fecha}): {$mensaje}</p>";
}

$stmt->close();
$conn->close();
