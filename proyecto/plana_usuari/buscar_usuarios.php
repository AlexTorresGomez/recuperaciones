<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';

$buscar = trim($_POST['buscar'] ?? '');

if ($buscar === '') {
    echo "<p>Introduce un nombre para buscar.</p>";
    return;
}

$id_usuario = $_SESSION['id_user'] ?? null;

if ($id_usuario === null) {
    echo "<p>Debes iniciar sesión para buscar usuarios.</p>";
    return;
}

$query = "SELECT user_name, id_user, profile_photo 
          FROM usuarios 
          WHERE id_user != ? 
            AND (administrador IS NULL OR administrador = 0) 
            AND user_name LIKE ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "<p>Error en la base de datos.</p>";
    return;
}

$like = "%" . $buscar . "%";
$stmt->bind_param("is", $id_usuario, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No se encontraron usuarios con ese nombre.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        $nombre = htmlspecialchars($row['user_name']);
        $id_user = $row['id_user'];
        $profile_photo = $row['profile_photo'];

        if ($profile_photo) {
            echo "<div class='d-flex align-items-center mb-2'>
                    <a href='perfil_usuario.php?id=$id_user'>
                        <img src='data:image/jpeg;base64," . base64_encode($profile_photo) . "' class='rounded-circle me-2' width='40' height='40'>
                        <span>$nombre</span>
                    </a>
                  </div>";
        } else {
            echo "<div class='d-flex align-items-center mb-2'>
                    <a href='perfil_usuario.php?id=$id_user'>
                        <img src='img/default.png' class='rounded-circle me-2' width='40' height='40'>
                        <span>$nombre</span>
                    </a>
                  </div>";
        }
    }
}
?>
