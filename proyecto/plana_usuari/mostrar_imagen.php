<?php
require_once 'conexion.php';

if (!isset($_GET['id_imagen']) || !is_numeric($_GET['id_imagen'])) {
    http_response_code(400);
    exit('ID de imagen no válido');
}

$id_imagen = intval($_GET['id_imagen']);

$stmt = $conn->prepare("SELECT imagen FROM imagenes_defaukt WHERE id_imagen = ?");
$stmt->bind_param("i", $id_imagen);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    http_response_code(404);
    exit('Imagen no encontrada');
}

$stmt->bind_result($imagen);
$stmt->fetch();

header("Content-Type: image/jpeg"); 
echo $imagen;

$stmt->close();
$conn->close();
?>
