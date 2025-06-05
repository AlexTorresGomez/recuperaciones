<?php
session_start();
require_once 'conexion.php';

$id_partida = intval($_GET['id_partida'] ?? 0);
$id_user_actual = $_SESSION['id_user'] ?? 0;

if ($id_partida <= 0) {
    die("ID de partida no válido.");
}

// Obtener datos de la partida y validar permisos (ejemplo básico)
$stmt = $conn->prepare("SELECT id_creador, id_imagen, estado FROM partida WHERE id_partida = ?");
$stmt->bind_param("i", $id_partida);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Partida no encontrada.");
}

$stmt->bind_result($id_creador, $id_imagen, $estado);
$stmt->fetch();

if ($estado !== 'activa') {
    die("La partida está cerrada.");
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Partida #<?= $id_partida ?></title>
<style>
  body { display: flex; height: 100vh; margin: 0; }
  #mapa { flex: 3; border: 1px solid #ccc; position: relative; }
  #chat { flex: 1; border-left: 1px solid #ccc; padding: 10px; display: flex; flex-direction: column; }
  #mensajes { flex: 1; overflow-y: auto; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px; }
  #inputMensaje { display: flex; }
  #inputMensaje input { flex: 1; padding: 5px; }
  #inputMensaje button { padding: 5px; }
</style>
</head>
<body>

<div id="mapa">
  <img src="mostrar_imagen.php?id_imagen=<?= $id_imagen ?>" alt="Mapa partida" style="width: 100%; height: 100%; object-fit: contain;">
</div>

<div id="chat">
  <div id="mensajes"></div>
  <form id="formMensaje" onsubmit="enviarMensaje(event)">
    <div id="inputMensaje">
      <input type="text" id="mensaje" placeholder="Escribe tu mensaje..." autocomplete="off" required>
      <button type="submit">Enviar</button>
    </div>
  </form>
  <?php if ($id_creador === $id_user_actual): ?>
    <button onclick="cerrarPartida()">Cerrar Partida</button>
  <?php endif; ?>
</div>

<script>
const idPartida = <?= $id_partida ?>;

function enviarMensaje(event) {
    event.preventDefault();
    const mensajeInput = document.getElementById('mensaje');
    const mensaje = mensajeInput.value.trim();
    if (!mensaje) return;

    fetch('enviar_mensaje.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id_partida: idPartida, mensaje})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            mensajeInput.value = '';
            cargarMensajes();
        } else {
            alert('Error al enviar mensaje');
        }
    });
}

function cargarMensajes() {
    fetch('cargar_mensajes.php?id_partida=' + idPartida)
    .then(res => res.json())
    .then(data => {
        const mensajesDiv = document.getElementById('mensajes');
        mensajesDiv.innerHTML = '';
        data.forEach(m => {
            const div = document.createElement('div');
            div.textContent = m.usuario + ": " + m.mensaje;
            mensajesDiv.appendChild(div);
        });
        mensajesDiv.scrollTop = mensajesDiv.scrollHeight;
    });
}

function cerrarPartida() {
    if (!confirm('¿Estás seguro que quieres cerrar la partida?')) return;

    fetch('cerrar_partida.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id_partida: idPartida})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert('Partida cerrada');
            window.location.href = 'index.php'; // O a donde quieras redirigir
        } else {
            alert('Error al cerrar la partida');
        }
    });
}

// Carga mensajes cada 3 segundos
setInterval(cargarMensajes, 3000);
cargarMensajes();

</script>

</body>
</html>

