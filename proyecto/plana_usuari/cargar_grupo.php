<?php

require_once 'conexion.php'; // Incluye tu conexión a la base de datos

// Obtener id del grupo (ojo que el parámetro es 'id' en la URL, no 'id_group')
$id_group = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Mostrar el ID que estamos recibiendo (para debug)


// Obtener id del usuario actual desde sesión
$id_user_actual = $_SESSION['id_user'] ?? 0;

if ($id_group <= 0) {
    die("ID de grupo no válido.");
}

// Preparar y ejecutar consulta del grupo
$stmt = $conn->prepare("SELECT group_name, id_creador, creation_date, users, profile_photo FROM grupo WHERE id_group = ?");
$stmt->bind_param("i", $id_group);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Grupo no encontrado.");
}

$stmt->bind_result($group_name, $id_creador, $creation_date, $users, $profile_photo);
$stmt->fetch();

if ($id_creador != $id_user_actual) {
    die("No tienes permiso para ver la información de este grupo.");
}

// Mostrar info del grupo
echo "<h1>Grupo: " . htmlspecialchars($group_name ?? '') . "</h1>";
echo "<p>Fecha de creación: " . htmlspecialchars($creation_date ?? '') . "</p>";
echo "<p>Usuarios (cantidad): " . htmlspecialchars($users ?? '') . "</p>";

// Mostrar foto del grupo
if (!empty($profile_photo)) {
    $mime = 'image/jpeg'; // Ajusta según corresponda
    $fotoBase64 = base64_encode($profile_photo);
    echo '<img src="data:' . $mime . ';base64,' . $fotoBase64 . '" alt="Foto del grupo" style="max-width:200px;"><br>';
} else {
    echo "<p>Sin foto de grupo.</p>";
}

$stmt->close();

// Obtener usuarios participantes
$stmt_users = $conn->prepare("
    SELECT u.user_name
    FROM usuarios u
    INNER JOIN user_group ug ON u.id_user = ug.id_user
    WHERE ug.id_group = ?
");
$stmt_users->bind_param("i", $id_group);
$stmt_users->execute();
$result_users = $stmt_users->get_result();

echo "<h2>Participantes:</h2>";
echo "<ul>";
while ($row = $result_users->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['user_name'] ?? '') . "</li>";
}
echo "</ul>";

$stmt_users->close();
$conn->close();
?>
