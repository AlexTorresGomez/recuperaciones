<?php
include 'conexion.php';

$filtro = $_GET['filtro'] ?? null;

$where = "";
if ($filtro === "resueltos") {
    $where = "WHERE r.resolved = 1";
} elseif ($filtro === "no_resueltos") {
    $where = "WHERE r.resolved IS NULL OR r.resolved = 0";
}

$sql = "
SELECT 
    r.id_report,
    ru.user_name AS reportador,
    rr.user_name AS reportado,
    r.motive,
    r.explanation,
    r.report_date,
    r.resolved,
    r.resolved_date
FROM report r
JOIN usuarios ru ON r.id_user = ru.id_user
JOIN usuarios rr ON r.id_user_reported = rr.id_user
$where
ORDER BY r.report_date DESC";

$result = $conn->query($sql);
$reportes = [];

while ($row = $result->fetch_assoc()) {
    $row['resolved'] = (bool) $row['resolved'];
    $reportes[] = $row;
}

echo json_encode($reportes);
$conn->close();
?>
