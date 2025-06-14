<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    // Si no hay sesión iniciada, redirige al login
    header("Location: /Cluster_Role/proyecto/login/login.html");
    exit();
}

include("conexion.php");

$id_partida = $_GET['id_partida'] ?? 0;
if (!$id_partida) {
    die("ID de partida no especificado.");
}

// Consulta de la partida
$stmt_partida = $conn->prepare("SELECT id_partida, id_group, id_creador, mapa, fecha_inicio, fecha_fin, estado FROM partida WHERE id_partida = ?");
$stmt_partida->bind_param("i", $id_partida);
$stmt_partida->execute();
$stmt_partida->store_result();

if ($stmt_partida->num_rows == 0) {
    die("Partida no encontrada.");
}

$stmt_partida->bind_result($id_partida, $id_group, $id_creador, $mapa, $fecha_inicio, $fecha_fin, $estado);
$stmt_partida->fetch();
$stmt_partida->close();

// Consulta de la foto de perfil del usuario
$id_user = $_SESSION['id_user'] ?? 0;
$profile_photo = null;

if ($id_user) {
    $stmt_usuario = $conn->prepare("SELECT profile_photo FROM usuarios WHERE id_user = ?");
    $stmt_usuario->bind_param("i", $id_user);
    $stmt_usuario->execute();
    $stmt_usuario->bind_result($profile_photo);
    $stmt_usuario->fetch();
    $stmt_usuario->close();
}

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
    <header class="border-bottom border-secondary py-3 px-4">
  <div class="d-flex align-items-center justify-content-evenly" style="width: 100%;">
    
    <!-- Izquierda: Grupos y Partida -->
    <div class="d-flex justify-content aling-item-center gap-4 fs-3">
      <a href="grupos.php" class="text-decoration-none fw-semibold titulo">Grupos</a>
      <a href="#" class="text-decoration-none fw-semibold titulo">Partida</a>
    </div>

    <!-- Centro: Cluster Role -->
    <div class="fw-bold fs-2 text-center titulo">
      <a href="usuario.php" class="text-decoration-none">Cluster Role</a>
    </div>

    <!-- Derecha: Amigos y Usuario -->
    <div class="d-flex align-items-center gap-4">
      
      <!-- Dropdown Amigos -->
      <div class="dropdown">
        <a class="text-decoration-none fw-semibold fs-3 dropdown-toggle titulo d-flex justify-content aling-item-center " href="#" role="button" id="dropdownAmigos" data-bs-toggle="dropdown" aria-expanded="false">
          Amigos
        </a>
        <ul class="dropdown-menu " aria-labelledby="dropdownAmigos">
          <li><a class="dropdown-item" href="#">Ver amigos</a></li>
          <li><a class="dropdown-item" href="#">Buscar amigos</a></li>
        </ul>
      </div>

      <!-- Dropdown Usuario (foto) -->
      <div class="dropdown d-flex justify-content aling-item-center">
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

    </div>
  </div>
</header>
    <div class="container-fluid mt-4 px-5">
  <div class="row g-4">
    <div class="col-md-6 d-flex justify-content-center align-items-start">
      <?php if ($mapa): ?>
        <div id="mapa-container" style="position: relative; width: fit-content;">
          <img src="data:image/jpeg;base64,<?= base64_encode($mapa) ?>" 
               alt="Mapa de la partida"
               class="img-fluid rounded shadow mapa-img"
               id="mapa" />

          <!-- Imágenes de personajes -->
          <img src="/Cluster_Role/proyecto/foto/personajes/guerrero1.png" class="personaje" style="top: 10px; left: 10px;">
          <img src="/Cluster_Role/proyecto/foto/personajes/guerrero2.png" class="personaje" style="top: 10px; left: 80px;">
          <img src="/Cluster_Role/proyecto/foto/personajes/guerrero3.png" class="personaje" style="top: 10px; left: 150px;">
          <img src="/Cluster_Role/proyecto/foto/personajes/guerrero4.png" class="personaje" style="top: 80px; left: 10px;">
          <img src="/Cluster_Role/proyecto/foto/personajes/guerrero5.png" class="personaje" style="top: 80px; left: 80px;">
          <img src="/Cluster_Role/proyecto/foto/personajes/guerrero6.png" class="personaje" style="top: 80px; left: 150px;">
        </div>
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

<style>
  #mapa-container {
    position: relative;
  }

  .personaje {
    position: absolute;
    width: 50px;
    height: 50px;
    cursor: move;
    user-select: none;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const personajes = document.querySelectorAll('.personaje');
    personajes.forEach(personaje => {
      personaje.addEventListener('mousedown', dragStart);
    });

    let offsetX, offsetY, currentImg;

    function dragStart(e) {
      currentImg = e.target;
      offsetX = e.offsetX;
      offsetY = e.offsetY;

      document.addEventListener('mousemove', dragging);
      document.addEventListener('mouseup', dragEnd);
    }

    function dragging(e) {
      if (!currentImg) return;

      const contenedor = document.getElementById('mapa-container');
      const rect = contenedor.getBoundingClientRect();

      let x = e.clientX - rect.left - offsetX;
      let y = e.clientY - rect.top - offsetY;

      // Limita dentro del contenedor
      const maxX = rect.width - currentImg.offsetWidth;
      const maxY = rect.height - currentImg.offsetHeight;

      x = Math.max(0, Math.min(x, maxX));
      y = Math.max(0, Math.min(y, maxY));

      currentImg.style.left = x + 'px';
      currentImg.style.top = y + 'px';
    }

    function dragEnd() {
      document.removeEventListener('mousemove', dragging);
      document.removeEventListener('mouseup', dragEnd);
      currentImg = null;
    }
  });
</script>

</body>
</html>


