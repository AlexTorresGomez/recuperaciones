<?php
session_start();
require_once 'conexion.php'; 

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT id_partida FROM partida WHERE id_creador = ? AND estado = 'activa' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $id_partida = $row['id_partida'];
    header("Location: mostrar_partida.php?id_partida=$id_partida");
    exit();
} else {
    echo "No tienes ninguna partida activa creada.";
    
}
?>

