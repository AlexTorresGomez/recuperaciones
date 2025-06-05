<?php
session_start();
include 'conexion.php';


$tiempo_inactividad = 900;  
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $tiempo_inactividad)) {
  
    session_unset();    
    session_destroy();  
    header("Location: login.php?mensaje=sesion_caducada"); 
    exit();
}


$id_user = $_SESSION['id_user'];
$mensaje = trim($_POST['mensaje'] ?? '');

if ($mensaje !== '') {

  $stmt = $conn->prepare("INSERT INTO chat (id_user, text, date) VALUES (?, ?, NOW())");
  $stmt->bind_param("is", $id_user, $mensaje);
  $stmt->execute();
  $id_chat = $stmt->insert_id;

  $stmt2 = $conn->prepare("INSERT INTO chat_usuarios (id_chat, id_usuarios) VALUES (?, ?)");
  $stmt2->bind_param("ii", $id_chat, $id_user);
  $stmt2->execute();
}

header("Location: usuario.php");
$_SESSION['LAST_ACTIVITY'] = time();
exit;
