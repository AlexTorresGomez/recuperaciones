<?php
include 'conexion.php'; // Ruta a tu conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_group = $_POST['id_group'] ?? null;
    $nuevo_nombre = $_POST['group_name'] ?? '';
    $imagen = $_FILES['profile_photo'] ?? null;

    if (!$id_group || !$nuevo_nombre) {
        die("Datos incompletos.");
    }

    if ($imagen && $imagen['size'] > 0) {
        $imagenContenido = file_get_contents($imagen['tmp_name']);

        $stmt = $conn->prepare("UPDATE grupo SET group_name = ?, profile_photo = ? WHERE id_group = ?");
        $null = NULL; 
        $stmt->bind_param("sbi", $nuevo_nombre, $null, $id_group);
        $stmt->send_long_data(1, $imagenContenido);
    } else {
        $stmt = $conn->prepare("UPDATE grupo SET group_name = ? WHERE id_group = ?");
        $stmt->bind_param("si", $nuevo_nombre, $id_group);
    }

    if ($stmt->execute()) {
        header("Location: grupos.php?modificado=1");
        exit;
    } else {
        echo "Error al modificar el grupo: " . $stmt->error;
    }
}
?>


