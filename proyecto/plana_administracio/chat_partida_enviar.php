<?php
$conn = new mysqli("localhost", "root", "", "cluster_role");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_group = $_GET['id_group'] ?? null;
if (!$id_group) {
    die("ID de grupo no especificado.");
}

$check = $conn->prepare("SELECT group_name FROM grupo WHERE id_group = ?");
$check->bind_param("i", $id_group);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    die("Grupo no encontrado.");
}
$check->bind_result($group_name);
$check->fetch();
$check->close();

$stmt = $conn->prepare("
    SELECT cg.mensaje, cg.fecha_envio, u.user_name 
    FROM chat_game cg
    LEFT JOIN usuarios u ON cg.id_user = u.id_user
    WHERE cg.id_group = ?
    ORDER BY cg.fecha_envio ASC
");
$stmt->bind_param("i", $id_group);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat del Grupo <?= htmlspecialchars($group_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-4">
    <h3 class="text-center mb-4">Chat de: <?= htmlspecialchars($group_name) ?> (#<?= $id_group ?>)</h3>

    <div class="border p-3 mb-4 bg-white" style="height: 400px; overflow-y: auto;">
        <?php if (empty($mensajes)): ?>
            <p class="text-muted">No hay mensajes en este grupo.</p>
        <?php else: ?>
            <?php foreach ($mensajes as $msg): ?>
                <div class="mb-2">
                    <strong><?= htmlspecialchars($msg['user_name'] ?? 'Anónimo') ?>:</strong>
                    <?= htmlspecialchars($msg['mensaje']) ?>
                    <small class="text-muted float-end"><?= htmlspecialchars($msg['fecha_envio']) ?></small>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form action="enviar_mensaje.php" method="POST">
        <input type="hidden" name="id_group" value="<?= $id_group ?>">
        <div class="mb-3">
            <textarea name="mensaje" class="form-control" placeholder="Escribe tu mensaje..." rows="3" required></textarea>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>
</body>
</html>

