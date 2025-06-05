<?php
session_start();
require_once 'conexion.php';

$id_grupo = $_GET['id_group'] ?? 0;
$id_usuario = $_SESSION['id_user'] ?? 0;

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
<meta charset="UTF-8" />
<title>Crear Partida</title>
<style>
.galeria {
    display: flex; flex-wrap: wrap; gap: 10px;
}
.imagen {
    cursor: pointer; border: 2px solid transparent;
    transition: border-color 0.3s;
}
.imagen:hover {
    border-color: blue;
}
.seleccionada {
    border-color: red !important;
}
#mapa {
    width: 100%; height: 300px; margin-top: 20px;
    background-position: center; background-size: cover;
    border: 1px solid #ccc;
}
</style>
</head>
<body>

<h2>Selecciona la imagen para el mapa</h2>

<div class="galeria" id="galeria">
    <?php foreach ($imagenes as $img): ?>
        <img src="data:image/jpeg;base64,<?= $img['imagen_base64'] ?>" 
             data-id="<?= $img['id_imagen'] ?>" 
             class="imagen" width="200" />
    <?php endforeach; ?>
</div>

<div id="mapa"></div>

<form method="post" action="guardar_partida.php">
    <input type="hidden" name="id_group" value="<?= htmlspecialchars($id_grupo) ?>" />
    <input type="hidden" name="id_imagen" id="id_imagen" />
    <input type="submit" value="Crear Partida" />
</form>

<script>
const galeria = document.getElementById('galeria');
const mapa = document.getElementById('mapa');
const inputImagen = document.getElementById('id_imagen');
let imgSeleccionada = null;

galeria.addEventListener('click', e => {
    if (e.target.classList.contains('imagen')) {
        if (imgSeleccionada) {
            imgSeleccionada.classList.remove('seleccionada');
        }
        imgSeleccionada = e.target;
        imgSeleccionada.classList.add('seleccionada');

        mapa.style.backgroundImage = `url('${imgSeleccionada.src}')`;

        inputImagen.value = imgSeleccionada.getAttribute('data-id');
    }
});
</script>

</body>
</html>

