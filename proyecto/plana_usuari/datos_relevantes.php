<?php
include 'conexion.php';

$query = "SELECT user_name, positivo 
FROM usuarios 
WHERE positivo >= 8;";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row['user_name']) . "</p>";
  }
} else {
  echo "<p>No hay usuarios destacados</p>";
}
?>


