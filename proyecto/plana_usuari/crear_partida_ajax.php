<?php
require_once 'conexion.php';

header('Content-Type: application/json');

// Leer JSON del cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);

$id_group = intval($input['id_group'] ?? 0);
$id_imagen = intval($input['id_imagen'] ?? 0);

if ($id_group <= 0 || $id_imagen <= 0) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Obtener la imagen blob de la tabla imagenes_default
$stmt = $conn->prepare("SELECT imagen FROM imagenes_default WHERE id_imagen = ?");
$stmt->bind_param("i", $id_imagen);
$stmt->execute();
$stmt->bind_result($imagen_blob);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'error' => 'Imagen no encontrada']);
    exit;
}
$stmt->close();

// Insertar la partida con el mapa (imagen blob)
$stmt2 = $conn->prepare("INSERT INTO partidas (id_group, mapa, estado) VALUES (?, ?, 'activa')");
$stmt2->send_long_data(1, $imagen_blob);
$stmt2->bind_param("ib", $id_group, $null); // Nota: send_long_data se usa para blobs, bind_param requiere "i" y "b", pero en MySQLi se hace un poco diferente
// Como workaround, usaremos mysqli_stmt::send_long_data antes de ejecutar:

$stmt2->bind_param("i", $id_group);
$stmt2->send_long_data(1, $imagen_blob);
if ($stmt2->execute()) {
    $id_partida = $stmt2->insert_id;
    echo json_encode(['success' => true, 'id_partida' => $id_partida]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al crear la partida']);
}
$stmt2->close();
$conn->close();
?>
