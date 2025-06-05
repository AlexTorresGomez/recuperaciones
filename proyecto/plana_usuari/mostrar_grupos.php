<?php
include 'conexion.php'; // Incluye la conexión a la base de datos con $conn

// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que el usuario esté logueado
if (!isset($_SESSION['id_user'])) {
    echo "Debes iniciar sesión.";
    exit;
}

$id_user = $_SESSION['id_user'];

// Obtener grupos a los que pertenece el usuario
$sql = "SELECT g.id_group, g.group_name, g.profile_photo
        FROM grupo g
        INNER JOIN user_group ug ON g.id_group = ug.id_group
        WHERE ug.id_user = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows > 0) {
    while ($grupo = $resultado->fetch_assoc()) {
echo "<div style='padding:6px; margin:6px; font-size: 0.7em; width: fit-content;'>";

echo "<h3 style='font-size: 0.9em; margin-bottom:4px;'>" . htmlspecialchars($grupo['group_name']) . "</h3>";

$imagen = base64_encode($grupo['profile_photo']);
echo "<a href='entrar_grupo.php?id=" . $grupo['id_group'] . "' style='display:inline-block; margin-right:6px;'>";
echo "<img src='data:image/jpeg;base64,$imagen' width='60' height='60' alt='Grupo'>";
echo "</a>";

echo "<a href='salir_grupo.php?id=" . $grupo['id_group'] . "' style='font-size:0.7em; line-height:1; display:inline-block; vertical-align: middle;'>Salir del grupo</a>";

echo "</div>";




    }
} else {
    echo "No estás en ningún grupo.";
}

$stmt->close();
$conn->close();
?>

