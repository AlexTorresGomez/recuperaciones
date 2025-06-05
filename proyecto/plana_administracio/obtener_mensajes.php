<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';

$tiempo_inactividad = 900;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $tiempo_inactividad)) {
    session_unset();
    session_destroy();
    header("Location: login.php?mensaje=sesion_caducada");
    exit();
}

$id_usuario_actual = $_SESSION['id_user'] ?? 0;

$consulta = "
  SELECT chat.text, chat.date, usuarios.user_name, usuarios.id_user, usuarios.administrador
  FROM chat
  JOIN chat_usuarios ON chat.id_chat = chat_usuarios.id_chat
  JOIN usuarios ON chat_usuarios.id_usuarios = usuarios.id_user
  ORDER BY chat.id_chat ASC
  
";

$resultado = $conn->query($consulta);

if ($resultado && $resultado->num_rows > 0) {
  while ($row = $resultado->fetch_assoc()) {
    $es_usuario_actual = $row['id_user'] == $id_usuario_actual;
    $es_admin = $row['administrador'] == 1;

    $estilos = "padding: 10px; margin-bottom: 10px; border-radius: 10px; background-color: #444;";
    $clase = "chat-bubble";
   
    if ($es_usuario_actual) {
    
        // Verde y alineado a la derecha
        $estilos .= " background-color: rgb(100,96,79); color: rgb(201,189,152); margin-left: auto; text-align: right;";
    } else {
        // Alineado a la izquierda por defecto
       
        $estilos .= " margin-right: auto; background-color: #C9BD98;";
    }

    $nombre_estilo = $es_admin ? 'color: #AC844C' : '';

    echo '<div class="w-75 ' . $clase . '" style="' . $estilos . '">';
    echo '<div style="' . $nombre_estilo . '">' . htmlspecialchars($row['user_name']) . ':</div>';
    echo '<div style="' . $nombre_estilo . '">' . htmlspecialchars($row['text']) . '</div>';
    echo '<div style="font-size: 0.75em; color: #ffffff;">' . date('H:i', strtotime($row['date'])) . '</div>';
    echo '</div>';
  }
} else {
  echo '<div class="alerta mb-2">No hay mensajes aún.</div>';
}

$_SESSION['LAST_ACTIVITY'] = time();
?>

