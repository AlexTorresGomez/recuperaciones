<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cluster_role";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexió fallida: " . $conn->connect_error);
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $version = $_POST["version"];
    $short_explanation = $_POST["short_explanation"];
    $long_explanation = $_POST["long_explanation"];
    $date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO update_log (title, version, short_explanation, long_explanation, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $version, $short_explanation, $long_explanation, $date);

    if ($stmt->execute()) {
        header("Location: updates.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
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
      <aside class="col-md-2 p-3 d-flex flex-column justify-content-around" style="background-color: rgb(82,93,90); height: 100%;">
        <div id="mensajes" style="overflow-y: scroll; background-color: rgb(113,124,110); color: rgb(201,189,152); padding: 10px; border-radius: 6px;">
          <?php include 'obtener_mensajes.php'; ?>
        </div>
        <form action="enviar_mensaje.php" method="POST" class="mt-3 d-flex flex-column align-items-center">
          <textarea class="form-control" name="mensaje" rows="3" placeholder="Escribe un mensaje..." required></textarea>
          <button type="submit" class="btn btn-primary mt-2 w-50">Enviar</button>
        </form>
      </aside>

      <main class="col-md-8 d-flex  justify-content-center align-items-center p-4" >
            <div class="container mt-5">
    <h2 class="text-center mb-4">Crear un Nou Update</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Títol</label>
            <input type="text" name="title" class="form-control" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Versió</label>
            <input type="text" name="version" class="form-control" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Explicació Curta</label>
            <textarea name="short_explanation" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Explicació Llarga</label>
            <textarea name="long_explanation" class="form-control" rows="5" required></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Guardar Update</button>
            <a href="updates.php" class="btn btn-secondary">Cancel·lar</a>
        </div>
    </form>
</div>
        
      </main>

    <aside class="col-md-2 p-3 d-flex flex-column justify-content-around" style="background-color: rgb(82,93,90); height: 100%;">

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
          <a href="usuarios.php">
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
</html>