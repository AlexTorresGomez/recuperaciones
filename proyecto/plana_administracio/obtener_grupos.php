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

$sql = "SELECT id_group, group_name, id_creador, creation_date, users, profile_photo FROM grupo ORDER BY creation_date DESC";
$result = $conn->query($sql);

$grupos = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Asegura que la foto esté en base64
        if ($row['profile_photo']) {
            $row['profile_photo'] = base64_encode($row['profile_photo']);
        }
        $grupos[] = $row;
    }
}

$conn->close();

echo json_encode($grupos);
?>
