<?php
// Conexión a la base de datos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';
$sql = "SELECT * FROM update_log ORDER BY date DESC";
$result = $conn->query($sql);

$updates = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $updates[] = $row;
    }
} else {
    echo "<p>No hay versiones registradas.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizaciones</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        .navegacion {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        .flecha {
            font-size: 2em;
            cursor: pointer;
            user-select: none;
        }
        .update-content {
            text-align: left;
            max-width: 600px;
            margin: auto;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>

<div class="navegacion">
    <div class="flecha" onclick="mostrarAnterior()">
          <i class="fa-solid fa-arrow-left"></i>
    </div>
    <div class="update-content" id="contenedorActualizacion" ></div>
    <div class="flecha" onclick="mostrarSiguiente()">
   <i class="fa-solid fa-arrow-right"></i>
    </div>
</div>

<script>
    const updates = <?php echo json_encode($updates); ?>;
    let index = 0;

    function renderizarUpdate(i) {
        const contenedor = document.getElementById('contenedorActualizacion');
        const update = updates[i];

        contenedor.innerHTML = `
            <strong>${update.version} - ${update.title}</strong><br>
             <small>${update.date}</small><br>
            <em>${update.short_explanation}</em><br>
           
          
        `;
    }

    function mostrarAnterior() {
        if (index < updates.length - 1) {
            index++;
            renderizarUpdate(index);
        }
    }

    function mostrarSiguiente() {
        if (index > 0) {
            index--;
            renderizarUpdate(index);
        }
    }

    // Mostrar el más reciente al principio
    renderizarUpdate(index);
</script>

</body>
</html>
