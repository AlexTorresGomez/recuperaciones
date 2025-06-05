<?php
include 'conexion.php';
session_start();

$id_usuario = $_SESSION['id_user'];

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$descripcion = $_POST['descripcion'];

// Foto (opcional)
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $foto = file_get_contents($_FILES['foto']['tmp_name']);

    $query = "UPDATE usuarios SET user_name=?, email=?, estado=?, profile_photo=? WHERE id_user=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $email, $descripcion, $foto, $id_usuario);
} else {
    $query = "UPDATE usuarios SET user_name=?, email=?, estado=? WHERE id_user=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nombre, $email, $descripcion, $id_usuario);
}

if ($stmt->execute()) {
    // Redirección automática a usuario.php
    header("Location: usuario.php");
    exit;
} else {
    echo "Error al actualizar el perfil.";
}
?>

