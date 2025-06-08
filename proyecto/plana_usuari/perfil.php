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

    echo "<form action='actualizar_perfil.php' method='POST' enctype='multipart/form-data'>";

    if ($profile_photo) {
        echo "<p><strong>Foto de perfil actual:</strong></p>";
        echo "<img src='data:image/jpeg;base64," . base64_encode($profile_photo) . "' alt='Foto de perfil' width='100px' height='100px' style='border-radius: 25%'> <br><br>";
    } else {
        echo "<p><strong>Foto de perfil:</strong></p>";
        echo "<img src='img/default.png' alt='Foto de perfil predeterminada' width='100px' height='100px' style='border-radius: 25%'><br><br>";
    }

    echo "<label for='nombre'><strong>Nombre:</strong></label><br>";
    echo "<input type='text' name='nombre' value='$nombre' required><br><br>";

    echo "<label for='email'><strong>Email:</strong></label><br>";
    echo "<input type='email' name='email' value='$email' required><br><br>";

    echo "<label for='descripcion'><strong>Descripción:</strong></label><br>";
    echo "<textarea name='descripcion' rows='4' cols='40'>$desc</textarea><br><br>";

    echo "<label for='foto'><strong>Cambiar foto de perfil (JPG o PNG):</strong></label><br>";
    echo "<input type='file' name='foto' id='foto' accept='image/jpeg, image/png'><br><br>";

    echo "<input type='submit' value='Guardar cambios'>";
    echo "</form>";

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
} else {
    echo "<p>No se ha encontrado el perfil de usuario.</p>";
}
?>


