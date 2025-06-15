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
<body style="height: 100vh; overflow-x: hidden; margin: 0;">
  <header class="border-bottom border-secondary py-3 px-4">
  <div class="d-flex align-items-center justify-content-evenly" style="width: 100%;">
    <!-- Izquierda: Grupos y Partida -->
    <div class="d-flex justify-content aling-item-center gap-4 fs-3">
      <a href="updates.php" class="text-decoration-none fw-semibold titulo">updates</a>
    </div>
    <!-- Centro: Cluster Role -->
    <div class="fw-bold fs-2 text-center titulo">
      <a href="admin.php" class="text-decoration-none">Cluster Role</a>
    </div>
    <!-- Derecha: Amigos y Usuario -->
    <div class="d-flex align-items-center gap-4">
      <!-- Dropdown Amigos -->
    <div class="d-flex justify-content aling-item-center gap-4 fs-3">
      <a href="usuaris.php" class="text-decoration-none fw-semibold titulo">Usuaris</a>
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
          <li><a class="dropdown-item" href="logout.php" style="color:#C9BD98;">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </div>
</header>
  <div class="container-fluid">
    <div class="row row-full-height">
      <aside class="col-md-2 p-3 d-flex flex-column justify-content-around overflow-x: hidden;" style="background-color: rgb(82,93,90); height: 100%;">
      <div id="mensajes" class="overflow-x: hidden;">
<?php include 'obtener_mensajes.php'; ?>
</div>
<form action="enviar_mensaje.php" method="POST" class="mt-3 d-flex justify-content-center align-items-center gap-2">
  <textarea class="form-control" name="mensaje" rows="2" placeholder="Escribe un mensaje..." required style="resize: none; width: 80%;"></textarea>
  <button type="submit" class="btn btn-enviar rounded-circle d-flex align-items-center justify-content-center">
    <i class="bi bi-send-fill"></i>
  </button>
  </form>
      </aside>
      <main class="col-md-10 d-flex  justify-content-center align-items-center p-4" >
        <div class="container mt-5">
          <p>Crear un Nou Update</p>
          <form method="POST" action="">
              <div class="mb-3">
                  <label class="form-label">Títol</label>
                  <input type="text" name="title" class="form-control"style="width: 80vw;"required />
              </div>
              <div class="mb-3">
                  <label class="form-label">Versió</label>
                  <input type="text" name="version" class="form-control"style="width: 80vw;"required />
              </div>
              <div class="mb-3">
                  <label class="form-label">Explicació Curta</label>
                  <textarea name="short_explanation" class="form-control" style="width: 80vw;"required></textarea>
              </div>
              <div class="mb-3">
                  <label class="form-label">Explicació Llarga</label>
                  <textarea name="long_explanation" class="form-control" style="width: 80vw;"required></textarea>
              </div>
              <div class="text-center">
                  <button type="submit" class="btn btn-primary" style="width: 400px; height: 40px;">Guardar Update</button>
                  <a href="updates.php" class="btn btn-secondary" style="width: 400px; height: 40px;">Cancel·lar</a>
              </div>
          </form>
      </div> 
      </main>
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