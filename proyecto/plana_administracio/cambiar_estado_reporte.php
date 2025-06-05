<?php
include 'conexion.php';

$id = $_POST['id_report'] ?? null;
$resolved = $_POST['resolved'] ?? null;

if ($id === null || $resolved === null) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

if ($resolved == 1) {
    $sql = "UPDATE report SET resolved = 0, resolved_date = NULL WHERE id_report = ?";
} else {
    $sql = "UPDATE report SET resolved = 1, resolved_date = NOW() WHERE id_report = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["success" => true]);
$conn->close();
