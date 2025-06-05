<?php
include 'conexion.php';


if (!isset($_SESSION['id_user'])) {
  die("Usuario no autenticado.");
}

$id_usuario = $_SESSION['id_user'];  

$query = "SELECT u.user_name 
          FROM usuario_amigos f
          JOIN usuarios u ON f.id_amigo = u.id_user 
          WHERE f.id_usuario = ?";  
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);  

$stmt->execute();

// Obtén el resultado
$result = $stmt->get_result();

// Muestra los amigos
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['user_name']) . "</li>";
  }
} else {
  echo "<li>No hay amigos</li>";
}

$stmt->close();
$conn->close();
?>

