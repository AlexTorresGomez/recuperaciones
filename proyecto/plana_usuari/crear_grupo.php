<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_user'])) {
    echo "No hay sesión iniciada.";
    exit;
}

$id_usuario = $_SESSION['id_user'];
$group_name = $_POST['group_name'];

// Leer imagen y convertir a binario
$imagen_blob = null;
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $imagen_blob = file_get_contents($_FILES['profile_photo']['tmp_name']);
}

$sql = "INSERT INTO grupo (group_name, profile_photo, id_creador, creation_date) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sbi", $group_name, $imagen_blob, $id_usuario);
$stmt->send_long_data(1, $imagen_blob); // para enviar un BLOB
$stmt->execute();

$id_grupo = $stmt->insert_id; // ID del grupo recién creado

// Insertar al creador como miembro del grupo
$sql_miembro = "INSERT INTO user_group(id_group, id_user) VALUES (?, ?)";
$stmt_miembro = $conn->prepare($sql_miembro);
$stmt_miembro->bind_param("ii", $id_grupo, $id_usuario);
$stmt_miembro->execute();

// Insertar amigos seleccionados
if (isset($_POST['amigos']) && is_array($_POST['amigos'])) {
    foreach ($_POST['amigos'] as $id_amigo) {
        $stmt_miembro->bind_param("ii", $id_grupo, $id_amigo);
        $stmt_miembro->execute();
    }
}

$stmt->close();
$stmt_miembro->close();
$conn->close();
header("Location: grupos.php");
exit;

?>



