<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

$id_user = $_SESSION['id_user'];
$id_group = isset($_GET['id_group']) ? intval($_GET['id_group']) : 0;

if ($id_group === 0) {
    echo json_encode([]);
    exit;
}

// Validar usuario en grupo
$stmt = $conn->prepare("SELECT 1 FROM user_group WHERE id_user = ? AND id_group = ?");
$stmt->bind_param("ii", $id_user, $id_group);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode([]);
    exit;
}
$stmt->close();

// Obtener mensajes
$stmt = $conn->prepare("SELECT cg.mensaje, cg.fecha_envio, u.nombre FROM chat_game cg JOIN users u ON cg.id_user = u.id_user WHERE cg.id_group = ? ORDER BY cg.fecha_envio ASC");
$stmt->bind_param("i", $id_group);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = [
        'mensaje' => htmlspecialchars($row['mensaje']),
        'fecha_envio' => $row['fecha_envio'],
        'usuario' => htmlspecialchars($row['nombre']),
    ];
}
$stmt->close();

header('Content-Type: application/json');
echo json_encode($mensajes);
?>
