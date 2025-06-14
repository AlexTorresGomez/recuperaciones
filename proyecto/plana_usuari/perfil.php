<?php
include 'conexion.php';

$id_usuario = $_SESSION['id_user'];

$query = "SELECT user_name, email, estado, profile_photo FROM usuarios WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = htmlspecialchars($row['user_name']);
    $email = htmlspecialchars($row['email']);
    $desc = htmlspecialchars($row['estado']);
    $profile_photo = $row['profile_photo'];

echo "
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: #1e1e1e;
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: white;
        
    }
    .contenedor-pagina {
        display: flex;
        justify-content: center;
        align-items: center;

        flex-direction: column;
    }
    .perfil-contenedor {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid #aaa;
        padding: 10px;
        width: 800px;
        border-radius: 6px;
        background-color: #3A3A3A;

    }
    .perfil-imagen {
        flex-shrink: 0;
    }
    .perfil-imagen img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #888;
    }
    .perfil-formulario {
        flex-grow: 1;
    }
    .perfil-formulario label {
        font-weight: bold;
        display: block;
        margin-top: 6px;
        margin-bottom: 3px;
        font-size: 11px;
    }
    .perfil-formulario input[type='text'],
    .perfil-formulario input[type='email'],
    .perfil-formulario textarea,
    .perfil-formulario input[type='file'] {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 5px;
        font-size: 11px;
        box-sizing: border-box;
        border: 1px solid #bbb;
        border-radius: 3px;
    }
    .perfil-formulario input[type='submit'] {

        margin-top: 10px;
        padding: 6px 10px;
        font-size: 11px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        width: 250px;
    }
    .perfil-formulario input[type='submit']:hover {
        background-color: #0056b3;
    }
</style>
";

echo "<div class='contenedor-pagina'; style='display: flex; justify-content: center; align-items: center;' >";
echo "<form action='actualizar_perfil.php' method='POST' enctype='multipart/form-data' class='perfil-contenedor'; style='display: flex; justify-content: center; align-items: center;' >";
echo "<div class='perfil-formulario'; style='display: flex; justify-content: center; align-items: center; flex-direction: column;'>";
echo "<div class='perfil-imagen' style='display: flex; justify-content: center; align-items: center; flex-direction:column;'>";
if ($profile_photo) {
    echo "<p><strong>Foto de perfil actual:</strong></p>";
    echo "<img src='data:image/jpeg;base64," . base64_encode($profile_photo) . "' alt='Foto de perfil'>";
} else {
    echo "<p><strong>Foto de perfil:</strong></p>";
    echo "<img src='img/default.png' alt='Foto de perfil predeterminada'>";
}
echo "</div>";


echo "<label for='nombre'>Nombre:</label>";
echo "<input type='text' name='nombre' id='nombre' value='$nombre' required>";

echo "<label for='email'>Email:</label>";
echo "<input type='email' name='email' id='email' value='$email' required>";

echo "<label for='descripcion'>Descripción:</label>";
$desc_limitado = strlen($desc) > 300 ? substr($desc, 0, 300) . "..." : $desc;
echo "<textarea name='descripcion' id='descripcion' rows='4'>$desc_limitado</textarea>";

echo "<label for='foto'>Cambiar foto de perfil (JPG o PNG):</label>";
echo "<input type='file' name='foto' id='foto' accept='image/jpeg, image/png'>";

echo "<input type='submit' value='Guardar cambios'>";
echo "</div>";

echo "</form>";
echo '</div>';

// Script para validar tipo de archivo
echo "
<script>
document.getElementById('foto').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('El archivo seleccionado no es válido. Solo se permiten imágenes JPG o PNG.');
            this.value = ''; // Borrar el archivo
        }
    }
});
</script>
";

}
?>



