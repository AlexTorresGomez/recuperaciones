<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cluster_role";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión"]);
    exit();
}

$sql = "SELECT id_update, title, version, short_explanation, long_explanation, date FROM update_log ORDER BY date DESC";
$result = $conn->query($sql);

$updates = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $updates[] = $row;
    }
}

$conn->close();

echo json_encode($updates);
?>



