<?php
include 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    echo "Debes iniciar sesión.";
    exit;
}

$id_user = $_SESSION['id_user'];

// Obtener grupos del usuario
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

$grupos = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($grupo = $resultado->fetch_assoc()) {
        $grupos[] = $grupo;
    }
}

// Mostrar hasta 8 cuadros
$totalCuadros = 8;
$gruposMostrados = 0;

// Número total fijo de cuadros (8 en total)
$totalCuadros = 8;

// Mostrar los grupos que tiene el usuario, con estilo
echo "<div style='width: 100%; display: flex; flex-wrap: wrap; justify-content: center; gap: 24px; max-width: 700px; margin: 0 auto;'>";

$gruposMostrados = 0;
foreach ($grupos as $grupo) {
    echo "<div style='width: 150px; height: 150px; background-color: white; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1); padding: 6px; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;'>";

    

    $imagen = base64_encode($grupo['profile_photo']);
    echo "<a href='entrar_grupo.php?id=" . $grupo['id_group'] . "' style='margin-bottom: 6px;'>";
    echo "<img src='data:image/jpeg;base64,$imagen' width='100%' height='100%' alt='Grupo' style='border-radius: 8px;'>";
    echo "</a>";

    echo "<a href='salir_grupo.php?id=" . $grupo['id_group'] . "' style='font-size: 0.7em; color: #555;'>Salir del grupo</a>";

    echo "</div>";

    $gruposMostrados++;
}

// Calcular cuántos cuadros vacíos faltan para llegar a 8
$cuadrosVacios = $totalCuadros - $gruposMostrados;

// Añadir cuadros vacíos para completar 8 en total
for ($i = 0; $i < $cuadrosVacios; $i++) {
    echo "<div style='width: 150px; height: 150px; background-color: white; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1); padding: 6px; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;'>";

    echo "<h3 style='font-size: 0.95em; margin: 6px 0; color: #aaa;'>Espacio libre</h3>";

    echo "<img src='/Cluster_Role/proyecto/foto/icons/mas.png' width='70' height='70' alt='Vacío' style='opacity: 0.4; transform: rotate(135deg); border-radius: 8px;'>";

    echo "</div>";
}

echo "</div>";


$stmt->close();
$conn->close();
?>
