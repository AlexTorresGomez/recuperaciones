<?php
include 'conexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id_user'];

$query = "SELECT u.user_name, u.profile_photo, u.id_user 
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
        $id_amigo = $row['id_user'];
        $profile_photo = $row['profile_photo']; 

        echo "<div class='d-flex align-items-center justify-content-between mb-2'>";
        
        echo "<div class='d-flex align-items-center'>";
        if ($profile_photo) {
            echo "<img src='data:image/jpeg;base64," . base64_encode($profile_photo) . "' class='rounded-circle me-2' width='40' height='40'>";
        } else {
            echo "<img src='img/default.png' class='rounded-circle me-2' width='40' height='40'>";
        }
        echo "<span>$nombre</span>";
        echo "</div>";

        echo "<form method='POST' action='eliminar_amigo.php' onsubmit='return confirm(\"¿Estás seguro de que quieres eliminar a $nombre?\");'>
                <input type='hidden' name='id_amigo' value='$id_amigo'>
                <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
              </form>";
        
        echo "</div>";
    }
}
?>

