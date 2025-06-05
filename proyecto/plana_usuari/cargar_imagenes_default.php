<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'conexion.php';

header('Content-Type: application/json');

$sql = "SELECT id_imagen, imagen FROM imagenes_defaukt";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$imagenes = [];
while ($row = $result->fetch_assoc()) {
    $imagenes[] = [
        'id_imagen' => $row['id_imagen'],
        'imagen_base64' => base64_encode($row['imagen'])
    ];
}

echo json_encode($imagenes);

$conn->close();

