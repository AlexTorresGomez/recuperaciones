<?php
session_start();
require_once 'conexion.php';

$id_grupo = $_GET['id_group'] ?? 0;
$id_usuario = $_SESSION['id_user'] ?? 0;

// Obtener imágenes
$sql = "SELECT id_imagen, imagen FROM imagenes_defaukt";
$result = $conn->query($sql);
$imagenes = [];

while ($row = $result->fetch_assoc()) {
    $imagenes[] = [
        'id_imagen' => $row['id_imagen'],
        'imagen_base64' => base64_encode($row['imagen'])
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Mapa</title>
    <style>
        body {
            background-color: #5e726c;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            margin: 0;
        }

        .galeria {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            justify-items: center;
        }

        .imagen {
            width: 100%;
            max-width: 350px;
            height: auto;
            border-radius: 8px;
            border: 4px solid transparent;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .imagen:hover {
            transform: scale(1.02);
            border-color: #caa76c;
        }

        .imagen.seleccionada {
            border-color: black;
            box-shadow: 0 0 15px rgba(0,0,0,0.8);
        }

        #mapa {
            margin-top: 30px;
            width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
            border: 3px dashed #ccc;
            border-radius: 12px;
        }

        .boton {
            display: block;
            margin: 30px auto 0;
            padding: 12px 24px;
            background-color: #6d7269;
            color: #f2f2f2;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .boton:hover {
            background-color: #8a9288;
        }
    </style>
</head>
<body>

<h2 style="text-align: center; color: #f2f2f2;">Selecciona un Mapa para Crear la Partida</h2>

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
    <input class="boton" type="submit" value="Selecciona Mapa y Crear Partida">
</form>

<script>
function seleccionarImagen(idImagen, elemento) {
    document.getElementById('id_imagen_seleccionada').value = idImagen;
    document.getElementById('mapa').style.backgroundImage = `url('${elemento.src}')`;

    // Quitar clase a todas
    document.querySelectorAll('.imagen').forEach(img => {
        img.classList.remove('seleccionada');
    });

    // Marcar seleccionada
    elemento.classList.add('seleccionada');
}
</script>

</body>
</html>


