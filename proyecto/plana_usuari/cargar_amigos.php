<?php
session_start();
$id_usuario = $_SESSION['id_user']; // Asegúrate de tener la sesión iniciada

include 'conexion.php'; // tu archivo de conexión

$sql = "SELECT u.id_user, u.user_name
        FROM usuario_amigos ua
        JOIN usuarios u ON u.id_user = ua.id_amigo
        WHERE ua.id_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$checkboxes = "";
while ($row = $result->fetch_assoc()) {
    $id = $row['id_user'];
    $nombre = htmlspecialchars($row['user_name']);
    $checkboxes .= "
    <div class='form-check'>
      <input class='form-check-input' type='checkbox' name='amigos[]' value='$id' id='amigo$id'>
      <label class='form-check-label' for='amigo$id'>$nombre</label>
    </div>";
}
$stmt->close();
$conn->close();
?>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("listaAmigos").innerHTML = `<?= $checkboxes ?>`;
  });
</script>
