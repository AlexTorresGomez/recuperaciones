<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cluster_role";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

$id = $_POST['id_user'] ?? null;
$baneo_actual = $_POST['baneo'] ?? null;

if ($id === null || $baneo_actual === null) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$nuevo_baneo = ($baneo_actual == 1) ? 0 : 1;

$stmt = $conn->prepare("UPDATE usuarios SET baneo = ? WHERE id_user = ?");
$stmt->bind_param("ii", $nuevo_baneo, $id);
$stmt->execute();

echo json_encode(["success" => true, "nuevo_baneo" => $nuevo_baneo]);
$conn->close();
?>
