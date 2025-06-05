<?php
include 'conexion.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id_user'] ?? 0; 
$query = "SELECT g.group_name 
          FROM grupo g
          JOIN user_group ug ON g.id_group = ug.id_group
          JOIN usuarios u ON ug.id_user = u.id_user
          WHERE u.id_user = ? 
          ORDER BY g.creation_date DESC 
          LIMIT 5";

$stmt = $conn->prepare($query); 
$stmt->bind_param("i", $id_usuario); 
$stmt->execute(); 
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No estás asignado a ningún grupo.</p>";
} else {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $group_name = htmlspecialchars($row['group_name'] ?? '');
        echo "<li>" . $group_name . "</li>"; 
    }
    echo "</ul>";
}

$stmt->close(); 
$conn->close(); 
?>
