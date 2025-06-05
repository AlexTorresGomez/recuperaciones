<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
          <li><a class="dropdown-item" href="logout.php" style="color:#C9BD98;">Cerrar sesión</a></li>
        </ul>
      </div>

      <div class="position-absolute start-50 translate-middle-x titulo fw-bold fs-2 text-center">
        <a href="admin.php"> Cluster Role</a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row row-full-height">
      <aside class="col-md-2 p-3 d-flex flex-column justify-content-around align-items-center" style="background-color: rgb(82,93,90); height: 100%;">
        <div id="mensajes" style="overflow-y: scroll; background-color: rgb(113,124,110); color: rgb(201,189,152); padding: 10px; border-radius: 6px;">
          <?php include 'obtener_mensajes.php'; ?>
        </div>
        <form action="enviar_mensaje.php" method="POST" class="mt-3 d-flex flex-column align-items-center">
          <textarea class="form-control" name="mensaje" rows="3" placeholder="Escribe un mensaje..." required></textarea>
          <button type="submit" class="btn btn-primary mt-2 w-50">Enviar</button>
        </form>
      </aside>

      
      <main class="col-md-8 d-flex  justify-content-center align-items-center flex-column p-4" >
            <div class="container p-0 m-0">
                <h2 class="text-center titulo p-2">Updates</h2>

            <div id="tablaUpdates" style=" d-flex justify-content-center align-items-center overflow-y: scroll; max-height: 400px; max-width: 1200px;"></div>
            
          </div>
        <a href="formulari_update.php" class="btn btn-success">Fer Update</a>
      </main>

    <aside class="col-md-2 p-3 d-flex flex-column justify-content-around " style="background-color: rgb(82,93,90); height: 100%;">

        <div class="group-box mt-3">
          <a href="updates.php">
          <button style="width: 100%; background-color: rgb(104,116,108); color: rgb(201,189,152); border:none; border-radius: 4px;">
            <h3 class="text-center titulo pt-2" style="font-size: 1rem;">Updates</h3>
          </button>
          </a>
        </div>

        <div class="group-box mt-3">
          <a href="grups.php">
            <button style="width: 100%; background-color: rgb(104,116,108); color: rgb(201,189,152); border:none; border-radius: 4px;">
              <h3 class="text-center titulo pt-2" style="font-size: 1rem;">Grups</h3>
            </button>
          </a>
        </div>

        <div class="group-box mt-3">
          <a href="usuaris.php">
            <button style="width: 100%; background-color: rgb(104,116,108); color: rgb(201,189,152); border:none; border-radius: 4px;">
              <h3 class="text-center titulo pt-2" style="font-size: 1rem;">Usuaris</h3>
            </button>
          </a>
        </div>
      </aside>
    </div>
  </div>

  <footer>
    Cluster Role © 2025 - Todos los derechos reservados
  </footer>
</body>
<script>

function actualizarUpdates() {
  fetch("update_log.php")
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("tablaUpdates");
      contenedor.innerHTML = ""; // limpiar

      const tabla = document.createElement("table");
     
      tabla.className = " text-center";
      tabla.innerHTML = `
        <thead class="table-light">
          <tr>
            <th>ID Update</th>
            <th>Títol</th>
            <th>Versió</th>
            <th>Explicació Curta</th>
            <th>Explicació Llarga</th>
            <th>Data Update</th>
          </tr>
        </thead>
        <tbody></tbody>
      `;

      const tbody = tabla.querySelector("tbody");

      data.forEach(u => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
        
          <td>${u.id_update}</td>
          <td>${u.title}</td>
          <td>${u.version}</td>
          <td>${u.short_explanation}</td>
          <td>${u.long_explanation}</td>
          <td>${u.date}</td>`;
        tbody.appendChild(fila);
      });

      contenedor.appendChild(tabla);
    })
    .catch(error => {
      console.error("Error al cargar updates:", error);
    });
}

// Llamada inicial
actualizarUpdates();

// Recarga automática cada 5 segundos (opcional)
setInterval(actualizarUpdates, 5000);


</script>
</html>