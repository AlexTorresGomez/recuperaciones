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
      <a href="#" class="text-decoration-none">Cluster Role</a>
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
      <main class="col-md-10 p-4" >
            <div class="container p-0 m-0">
                <h2 class="text-center titulo p-2">Reports</h2>
            <div class="mb-3 text-center">
            <button class="btn btn-outline-success me-2 p-1" onclick="actualizarReportes('resueltos')">Mostrar Resueltos</button>
            <button class="btn btn-outline-danger me-2 p-1" onclick="actualizarReportes('no_resueltos')">Mostrar No Resueltos</button>
            <button class="btn btn-outline-secondary p-1" onclick="actualizarReportes('todos')">Mostrar Todos</button>
          </div>
            <div id="tablaReportes" style=" d-flex justify-content-center align-items-center overflow-y: scroll; max-height: 400px; max-width: 1200px;"></div>
          </div>
      </main>
    </div>
  </div>
  <footer>
    Cluster Role © 2025 - Todos los derechos reservados
  </footer>
</body>
<script>
function actualizarReportes(filtro = "todos") {
  let url = "reporte.php";
  if (filtro !== "todos") {
    url += `?filtro=${filtro}`;
  }
  fetch(url)
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("tablaReportes");
      contenedor.innerHTML = "";
      const tabla = document.createElement("table");
      tabla.className = " text-center";
      tabla.innerHTML = `
        <thead >
          <tr>
            <th style="width: ;">Acción</th>
            <th style="width: ;">ID Reporte</th>
            <th style="width: ;">Reportador</th>
            <th style="width: ;">Reportado</th>
            <th style="width: ;">Motivo</th>
            <th style="width: ;">Explicación</th>
            <th style="width: ;">Fecha Reporte</th>
            <th style="width: ;">Resuelto</th>
            <th style="width: ;">Fecha Resolución</th>
          </tr>
        </thead>
        <tbody></tbody>
      `;
      const tbody = tabla.querySelector("tbody");
      data.forEach(r => {
        const fila = document.createElement("tr");
        const resuelto = r.resolved;
        const fechaRes = r.resolved_date ?? "-";
        fila.innerHTML = `
          <td>
            <button class="p-1 btn btn-sm ${resuelto ? 'btn-success' : 'btn-success'}"
                    onclick="cambiarEstadoReporte(${r.id_report}, ${resuelto ? 1 : 0})">
              ${resuelto ? 'Revisar' : 'Solucionar'}
            </button>
          </td>
          <td>${r.id_report}</td>
          <td>${r.reportador}</td>
          <td>${r.reportado}</td>
          <td>${r.motive}</td>
          <td>${r.explanation}</td>
          <td>${r.report_date}</td>
          <td>${resuelto ? 'Sí' : 'No'}</td>
          <td>${fechaRes}</td>
        `;
        tbody.appendChild(fila);
      });
      contenedor.appendChild(tabla);
    })
    .catch(err => console.error("Error al cargar reportes:", err));
}
function cambiarEstadoReporte(id, estado) {
  const formData = new FormData();
  formData.append("id_report", id);
  formData.append("resolved", estado);
  fetch("cambiar_estado_reporte.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        actualizarReportes(); 
      } else {
        alert("Error al actualizar reporte");
      }
    })
    .catch(err => console.error("Error:", err));
}
actualizarReportes();
</script>
</html>
<script>
  window.onload = function () {
    const mensajes = document.getElementById("mensajes");
    mensajes.scrollTop = mensajes.scrollHeight;
  };
</script>