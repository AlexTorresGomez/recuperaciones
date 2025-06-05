<?php
session_start();
require_once 'conexion.php';

$id_grupo = $_POST['id_group'] ?? 0;
$id_imagen = $_POST['id_imagen'] ?? 0;
$id_creador = $_SESSION['id_user'] ?? 0;

if (!$id_grupo || !$id_imagen || !$id_creador) {
    die("Faltan datos para crear la partida.");
}

$stmt = $conn->prepare("SELECT imagen FROM imagenes_defaukt WHERE id_imagen = ?");
$stmt->bind_param("i", $id_imagen);
$stmt->execute();
$stmt->bind_result($imagen_binaria);
if (!$stmt->fetch()) {
    die("Imagen no encontrada.");
}
$stmt->close();

$fecha_inicio = date('Y-m-d H:i:s');
$estado = 'activa';

$stmt = $conn->prepare("INSERT INTO partida (id_group, id_creador, mapa, fecha_inicio, estado) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iibss", $id_grupo, $id_creador, $null, $fecha_inicio, $estado);

$stmt->send_long_data(2, $imagen_binaria);

if ($stmt->execute()) {
    $id_partida = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    header("Location: mostrar_partida.php?id_partida=" . $id_partida);
    exit();
} else {
    echo "Error al crear partida: " . $stmt->error;
}
?>

