<?php
require_once 'conexion.php';

$id_group = $_GET['id'] ?? 0;
$id_group = intval($id_group);

if (!$id_group) {
    die("ID de grupo no especificado.");
}

$stmt = $conn->prepare("SELECT id_partida, id_group, id_creador, mapa, fecha_inicio, fecha_fin, estado FROM partida WHERE id_group = ? LIMIT 1");
$stmt->bind_param("i", $id_group);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "<script>
        alert('No se encontró ninguna partida para el grupo especificado.');
        window.location.href = 'usuario.php';
    </script>";
    exit();
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
  <div class="container-fluid mt-4 px-5">
    <h1 class="mb-4">Detalles de la Partida del Grupo #<?= htmlspecialchars($id_group) ?></h1>

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
    </div>

      <div class="col-md-6">
      <iframe src="chat_partida_enviar.php?id_group=<?= $id_group ?>"
              frameborder="0"
              class="w-100 rounded shadow chat-frame"
              style="height: 500px; background-color: #464E47;"></iframe>
    </div>
  </div>
</body>
</html>


