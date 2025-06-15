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
          <li><a class="dropdown-item" href="login.php" style="color:#C9BD98;">Cerrar sesión</a></li>
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
<style>
  .btn-custom-sm {
    font-size: 0.75rem; /* más pequeño pero legible */
    padding: 0.25rem 0.5rem; /* menos espacio dentro del botón */
    line-height: 1;
  }
</style>

<div class="container p-0 m-0">
  <h2 class="text-center titulo p-2">Usuaris Banejats</h2>

  <div class="mb-3 text-center">
    <button class="btn btn-outline-danger me-2 btn-custom-sm" onclick="actualizarUsuarios('baneados')">Mostrar BANEADOS</button>
    <button class="btn btn-outline-success me-2 btn-custom-sm" onclick="actualizarUsuarios('no_baneados')">Mostrar NO BANEADOS</button>
    <button class="btn btn-outline-secondary btn-custom-sm" onclick="actualizarUsuarios('todos')">Mostrar TODOS</button>
  </div>

  <div id="tablaUsuarios" class="d-flex justify-content-center align-items-start mx-auto overflow-auto" style="max-height: 400px; max-width: 1200px;">
    <!-- Aquí se cargará la tabla con los usuarios -->
  </div>
</div>
</div> 
</main>
    </div>
  </div>
  <footer>
    Cluster Role © 2025 - Todos los derechos reservados
  </footer>
</body>

<script>
function actualizarUsuarios(filtro) {
  let url = "usuarios.php";

  if (filtro === 'baneados') {
    url += "?filtro=baneados";
  } else if (filtro === 'no_baneados') {
    url += "?filtro=no_baneados";
  } // si es "todos", no se agrega nada

  fetch(url)
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("tablaUsuarios");
      contenedor.innerHTML = "";

      const tabla = document.createElement("table");
      tabla.className = " text-center";
      tabla.innerHTML = `
        <thead >
          <tr>
            <th>Accio</th>
            <th>ID Usuari</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Data Creació</th>
            <th>Descripció</th>
            <th>Likes</th>
            <th>Dislikes</th>
            <th>Foto Perfil</th>
          </tr>
        </thead>
        <tbody ></tbody>
      `;

      const tbody = tabla.querySelector("tbody");

      data.forEach(u => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
         <td>
  <button 
    class="btn btn-sm btn-super-sm ${u.baneo == 1 ? 'btn-success' : 'btn-danger'}" 
    onclick="cambiarBaneo(${u.id_user}, ${u.baneo ?? 0})">
    ${u.baneo == 1 ? 'Desbanear' : 'Banear'}
  </button>
</td>

          <td>${u.id_user}</td>
          <td>${u.user_name}</td>
          <td>${u.email}</td>
          <td>${u.creation_date}</td>
          <td>${u.estado}</td>
          <td>${u.positivo}</td>
          <td>${u.negativo}</td>
          <td><img src="data:image/jpeg;base64,${u.profile_photo}" alt="Foto" width="50" height="50" class="rounded-circle" /></td>
        `;
        tbody.appendChild(fila);
      });

      contenedor.appendChild(tabla);
    })
    .catch(error => {
      console.error("Error al cargar usuaris:", error);
    });
}

// Cargar todos por defecto al abrir la página
actualizarUsuarios("todos");

function cambiarBaneo(id_user, baneo) {
  const formData = new FormData();
  formData.append("id_user", id_user);
  formData.append("baneo", baneo);

  fetch("cambiar_baneo.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      actualizarUsuarios("todos"); // recarga tabla
    } else {
      alert("Error: " + (data.error || "No se pudo cambiar baneo"));
    }
  })
  .catch(err => {
    console.error("Error al cambiar baneo:", err);
  });
}


</script>

</html>
<script>
  window.onload = function () {
    const mensajes = document.getElementById("mensajes");
    mensajes.scrollTop = mensajes.scrollHeight;
  };
</script>