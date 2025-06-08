<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cluster_role";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;

if ($filtro === "baneados") {
    $sql = "SELECT * FROM usuarios WHERE baneo = 1 AND (administrador = 0 OR administrador IS NULL)";
} elseif ($filtro === "no_baneados") {
    $sql = "SELECT * FROM usuarios WHERE (baneo IS NULL OR baneo = 0) AND (administrador = 0 OR administrador IS NULL)";
} else {
    $sql = "SELECT * FROM usuarios WHERE (administrador = 0 OR administrador IS NULL)";
}


$result = $conn->query($sql);

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    if (isset($row['profile_photo'])) {
        $row['profile_photo'] = base64_encode($row['profile_photo']);
    }
    $usuarios[] = $row;
}

$conn->close();

echo json_encode($usuarios);


