<?php
include 'conexion.php';

$id_usuario = $_SESSION['id_user'];

$query = "SELECT user_name, profile_photo 
          FROM usuarios u 
          JOIN usuario_amigos f ON f.id_amigo = u.id_user 
          WHERE f.id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No tienes amigos aún.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        $nombre = htmlspecialchars($row['user_name']);
        $profile_photo = $row['profile_photo']; 
        if ($profile_photo) {
            echo "<div class='d-flex align-items-center mb-2'>
                    <img src='data:image/jpeg;base64," . base64_encode($profile_photo) . "' class='rounded-circle me-2' width='40' height='40'>
                    <span>$nombre</span>
                  </div>";
        } else {
            echo "<div class='d-flex align-items-center mb-2'>
                    <img src='img/default.png' class='rounded-circle me-2' width='40' height='40'>
                    <span>$nombre</span>
                  </div>";
        }
    }
}
?>
