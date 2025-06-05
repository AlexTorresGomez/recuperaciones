<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id_user'])) {
    die("Debes iniciar sesión.");
}

$id_user = $_SESSION['id_user'];
$user_name = $_SESSION['user_name'] ?? 'Usuario';
$id_group = isset($_GET['id_group']) ? intval($_GET['id_group']) : 0;

if ($id_group <= 0) {
    die("Grupo no válido.");
}

// Verificar si el usuario está en el grupo
$stmt = $conn->prepare("SELECT 1 FROM user_group WHERE id_user = ? AND id_group = ?");
$stmt->bind_param("ii", $id_user, $id_group);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("No estás en el grupo, no puedes enviar mensajes.");
}
$stmt->close();

// Si se ha enviado un mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    $mensaje = trim($_POST['mensaje']);
    if ($mensaje !== '') {
        $stmt = $conn->prepare("INSERT INTO chat_game (id_user, id_group, mensaje, fecha_envio) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $id_user, $id_group, $mensaje);
        $stmt->execute();
        $stmt->close();

        // Recarga para mostrar el mensaje
        header("Location: chat_partida_enviar.php?id_group=" . $id_group);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Cluster Role - Layout Discord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .chat-box { border: 1px solid #ccc; height: 300px; overflow-y: auto; padding: 10px; margin-bottom: 10px; }
        .mensaje { margin-bottom: 5px; }
        .form-chat { display: flex; gap: 10px; align-items: flex-start; }
        .form-chat textarea { flex-grow: 1; resize: none; }
        .dropdown.dropup { margin-right: 10px; }
    </style>
</head>
<body>

<div class="chat-box" id="chatBox">
    <?php
    $stmt = $conn->prepare("
        SELECT u.user_name, c.mensaje, c.fecha_envio
        FROM chat_game c
        JOIN usuarios u ON u.id_user = c.id_user
        WHERE c.id_group = ?
        ORDER BY c.fecha_envio ASC
    ");
    $stmt->bind_param("i", $id_group);
    $stmt->execute();
    $stmt->bind_result($nombre, $mensaje, $fecha);

    while ($stmt->fetch()) {
        echo "<div class='mensaje'><strong>" . htmlspecialchars($nombre) . ":</strong> " . htmlspecialchars($mensaje) . " <small>[" . $fecha . "]</small></div>";
    }
    $stmt->close();
    ?>
</div>

<form id="formChat" class="form-chat" method="post">
    <textarea name="mensaje" rows="2" placeholder="Escribe tu mensaje..."></textarea>

    <!-- Dropdown dados -->
    <div class="dropdown dropup">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tirar dado
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="tirarDado(6); return false;">d6</a></li>
            <li><a class="dropdown-item" href="#" onclick="tirarDado(10); return false;">d10</a></li>
            <li><a class="dropdown-item" href="#" onclick="tirarDado(20); return false;">d20</a></li>
            <li><a class="dropdown-item" href="#" onclick="tirarDado(100); return false;">d100</a></li>
        </ul>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

<script>
    // Usuario desde PHP para mostrar en el mensaje
    const userName = <?= json_encode($user_name) ?>;

    function tirarDado(lados) {
        const numero = Math.floor(Math.random() * lados) + 1;
        const mensaje = `${userName} ha tirado un d${lados} y ha sacado un ${numero}`;
        const textarea = document.querySelector('textarea[name="mensaje"]');
        textarea.value = mensaje;
        // Envía el formulario automáticamente para insertar el mensaje en la base
        document.getElementById('formChat').submit();
    }
</script>

</body>
</html>
