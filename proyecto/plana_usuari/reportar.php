<?php
session_start();
require_once 'conexion.php'; 

if (!isset($_SESSION['id_user'])) {
    die("Sessió no iniciada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $id_user_reported = $_POST['id_user_reported'];
    $motive = trim($_POST['motive']);
    $explanation = trim($_POST['explanation']);
    $report_date = date('Y-m-d H:i:s');

    if (empty($id_user_reported) || empty($motive) || empty($explanation)) {
        die("Falten dades obligatòries.");
    }

    $stmt = $conn->prepare("INSERT INTO report (id_user, id_user_reported, motive, explanation, report_date, resolved) 
                            VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("iisss", $id_user, $id_user_reported, $motive, $explanation, $report_date);

    if ($stmt->execute()) {
        echo "Report enviat correctament.";
    } else {
        echo "Error al enviar el report: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
