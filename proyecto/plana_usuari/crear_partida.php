<?php
session_start();
require_once 'conexion.php';

// Verificar si el grupo está definido
$id_grupo = $_GET['id_group'] ?? 0;
$id_usuario = $_SESSION['id_user'] ?? 0;

// Obtener imágenes de la base de datos
$sql = "SELECT id_imagen, imagen FROM imagenes_defaukt";
$result = $conn->query($sql);
$imagenes = [];

while ($row = $result->fetch_assoc()) {
    $imagenes[] = [
        'id_imagen' => $row['id_imagen'],
        'imagen_base64' => base64_encode($row['imagen']) // Convertir imagen a base64
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Partida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .galeria {
            display: flex;
            flex-wrap: wrap;
        }
        .imagen {
            margin: 10px;
            border: 2px solid #ccc;
            cursor: pointer;
            transition: 0.3s;
            max-width: 200px;
            max-height: 200px;
        }
        .imagen:hover {
            border-color: blue;
        }
        #mapa {
            width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
            margin-top: 20px;
            border: 2px dashed #aaa;
        }
    </style>
</head>
<body>

<h2>Selecciona una imagen para el mapa</h2>

<div class="galeria">
    <?php foreach ($imagenes as $img): ?>
        <img class="imagen"
             src="data:image/jpeg;base64,<?= $img['imagen_base64'] ?>"
             onclick="seleccionarImagen(<?= $img['id_imagen'] ?>, this)">
    <?php endforeach; ?>
</div>

<div id="mapa"></div>

<form id="formulario" method="post" action="guardar_partida.php">
    <input type="hidden" name="id_imagen" id="id_imagen_seleccionada">
    <input type="hidden" name="id_group" value="<?= htmlspecialchars($id_grupo) ?>">
    <input type="submit" value="Confirmar y Crear Partida">
</form>

<script>
function seleccionarImagen(idImagen, elemento) {
    document.getElementById('id_imagen_seleccionada').value = idImagen;

    document.getElementById('mapa').style.backgroundImage = `url('${elemento.src}')`;
}
</script>

</body>
</html>

