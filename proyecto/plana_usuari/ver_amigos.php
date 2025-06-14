<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_user'])) {
    header("Location: /Cluster_Role/proyecto/login/login.html");
    exit();
}

include("conexion.php");

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
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cluster Role - Layout Discord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Uncial+Antiqua&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


</head>



<body style="height: 100vh; overflow: hidden; margin: 0;">

  <header class="border-bottom border-secondary py-3 px-4">
  <div class="d-flex align-items-center justify-content-evenly" style="width: 100%;">
    
    <!-- Izquierda: Grupos y Partida -->
    <div class="d-flex justify-content aling-item-center gap-4 fs-3">
      <a href="grupos.php" class="text-decoration-none fw-semibold titulo">Grupos</a>
      <a href="partidas.php" class="text-decoration-none fw-semibold titulo">Partida</a>
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
          <li><a class="dropdown-item" href="ver_amigos.php">Ver amigos</a></li>
          <li><a class="dropdown-item" href="buscar_amigos.php">Buscar amigos</a></li>
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


  <div class="container-fluid">
    <div class="row row-full-height">
      <aside class="col-md-2 p-3 d-flex flex-column justify-content-around" style="background-color: rgb(82,93,90); height: 100%;">
      <div id="mensajes">
  <?php include 'obtener_mensajes.php'; ?>
</div>

<form action="enviar_mensaje.php" method="POST" class="mt-3 d-flex justify-content-center align-items-center gap-2">
  <textarea class="form-control" name="mensaje" rows="2" placeholder="Escribe un mensaje..." required style="resize: none; width: 80%;"></textarea>
  <button type="submit" class="btn btn-enviar rounded-circle d-flex align-items-center justify-content-center">
    <i class="bi bi-send-fill"></i>
  </button>
        </form>

      </aside>

     <main class="col-md-8 p-4">

  <div class="mb-4 p-3 border rounded">
    <h5> Tus amigos</h5>
    <?php include 'obtener_amigos.php'; ?>
  </div>



</main>

   <aside class="col-md-2 p-2 d-flex flex-column justify-content-around" style="background-color: rgb(82,93,90); height: 100%;">
  
  <div class="group-box mt-2">
    <a href="grupos.php">
      <button style="width: 100%; background-color: rgb(104,116,108); color: rgb(201,189,152); border:none; border-radius: 4px; padding: 4px;">
        <h3 class="text-center titulo pt-1" style="font-size: 0.85rem;">Grupos</h3>
      </button>
    </a>
    <div class="card mt-1" style="background-color: rgb(100,96,79);">
      <div class="card-body p-2" style="color: rgb(201,189,152); font-size: 0.85rem;">
        <ul class="list-unstyled" id="lista-grupos"><?php include 'datos_grupos.php'; ?></ul>
      </div>
    </div>
  </div>

  <div class="group-box mt-2">
    <button style="width: 100%; background-color: rgb(104,116,108); color: rgb(201,189,152); border:none; border-radius: 4px; padding: 4px;">
      <h3 class="text-center titulo pt-1" style="font-size: 0.85rem;">Ranking</h3>
    </button>
    <div class="card mt-1" style="background-color: rgb(100,96,79);">
      <div class="card-body p-2" style="color: rgb(201,189,152); font-size: 0.85rem;">
        <ul class="list-unstyled" id="lista-relevantes"><?php include 'datos_relevantes.php'; ?></ul>
      </div>
    </div>
  </div>

  <div class="group-box mt-2">
    <a href="ver_amigos.php">
      <button style="width: 100%; background-color: rgb(104,116,108); color: rgb(201,189,152); border:none; border-radius: 4px; padding: 4px;">
        <h3 class="text-center titulo pt-1" style="font-size: 0.85rem;">Amigos</h3>
      </button>
    </a>
    <div class="card mt-1" style="background-color: rgb(100,96,79);">
      <div class="card-body p-2" style="color: rgb(201,189,152); font-size: 0.85rem;">
        <ul class="list-unstyled" id="lista-amigos"><?php include 'datos_amigos.php'; ?></ul>
      </div>
    </div>
  </div>
</aside>

    </div>
  </div>
  <footer>
    Cluster Role © 2025 - Todos los derechos reservados
  </footer>
</body>
</html>
<script>
  window.onload = function () {
    const mensajes = document.getElementById("mensajes");
    mensajes.scrollTop = mensajes.scrollHeight;
  };
</script>