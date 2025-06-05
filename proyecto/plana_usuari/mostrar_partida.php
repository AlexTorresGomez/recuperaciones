<?php
require_once 'conexion.php';

$id_partida = $_GET['id_partida'] ?? 0;
if (!$id_partida) {
    die("ID de partida no especificado.");
}

$stmt = $conn->prepare("SELECT id_partida, id_group, id_creador, mapa, fecha_inicio, fecha_fin, estado FROM partida WHERE id_partida = ?");
$stmt->bind_param("i", $id_partida);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Partida no encontrada.");
}
$id_user = $_SESSION['id_user'] ?? 0;
$profile_photo = null;

if ($id_user) {
    $stmt = $conn->prepare("SELECT profile_photo FROM usuarios WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $stmt->bind_result($profile_photo);
    $stmt->fetch();
    $stmt->close();
}
$stmt->bind_result($id_partida, $id_group, $id_creador, $mapa, $fecha_inicio, $fecha_fin, $estado);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
 <title>Cluster Role - Layout Discord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
   <header class="border-bottom border-secondary py-3 px-4 d-flex justify-content-center align-items-center">
    <div class="d-flex justify-content-between align-items-center px-4" style="width: 100%; position: relative;">
      <div class="dropdown ms-auto order-2">
        <?php if ($profile_photo): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($profile_photo) ?>"
             class="rounded-circle dropdown-toggle"
             id="dropdownMenuButton"
             data-bs-toggle="dropdown"
             aria-expanded="false"
             style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" />
        <?php else: ?>
        <img src="/Cluster_Role/proyecto/foto/icons/default_profile.png"
             class="rounded-circle dropdown-toggle"
             id="dropdownMenuButton"
             data-bs-toggle="dropdown"
             aria-expanded="false"
             style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" />
        <?php endif; ?>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="background-color:#525D5A; color: #C9BD98;">
          <li><a class="dropdown-item" href="configuracio_usuaris.php" style="color:#C9BD98;">Usuario</a></li>
          <li><a class="dropdown-item" href="reportes.php" style="color:#C9BD98;">Reportes</a></li>
          <li><a class="dropdown-item" href="logout.php" style="color:#C9BD98;">Cerrar sesión</a></li>
        </ul>
      </div>

      <div class="position-absolute start-50 translate-middle-x titulo fw-bold fs-2 text-center">
        <a href="usuario.php"> Cluster Role</a>

      </div>
    </div>
  </header>
    <div class="container-fluid mt-4 px-5">
  <div class="row g-4">
    <div class="col-md-6 d-flex justify-content-center align-items-start">
      <?php if ($mapa): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($mapa) ?>"
             alt="Mapa de la partida"
             class="img-fluid rounded shadow mapa-img" />
      <?php else: ?>
        <p class="text-center">No hay imagen de mapa disponible.</p>
      <?php endif; ?>
    </div>

    <div class="col-md-6">
      <iframe src="chat_partida_enviar.php?id_group=<?= $id_group ?>"
              frameborder="0"
              class="w-100 rounded shadow chat-frame"
              style="height: 500px; background-color: #464E47;"></iframe>
    </div>
  </div>
</div>


</body>
</html>


