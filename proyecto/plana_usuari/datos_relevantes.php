<?php
include 'conexion.php';

$query = "SELECT user_name, positivo 
FROM usuarios 
WHERE positivo >= 8;
";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['user_name']) . " (" . $row['positivo'] . " 👍)</li>";
  }
} else {
  echo "<li>No hay usuarios destacados</li>";
}
?>

