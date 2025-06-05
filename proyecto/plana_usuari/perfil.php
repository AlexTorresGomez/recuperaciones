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
        echo "<img src='data:image/jpeg;base64," . base64_encode($profile_photo) . "' alt='Foto de perfil' width='100px' height='100px' style='border-radius: 25%'> <br>";
    } else {
        echo "<p><strong>Foto de perfil:</strong></p>";
        echo "<img src='img/default.png' alt='Foto de perfil predeterminada' width='100%' height='100%'>";
    }

    echo "<label for='nombre'><strong>Nombre:</strong></label>";
    echo "<input type='text' name='nombre' value='$nombre' required>";

    echo "<label for='email'><strong>Email:</strong></label><br>";
    echo "<input type='email' name='email' value='$email' required>";

    echo "<label for='descripcion'><strong>Descripción:</strong></label>";
    echo "<textarea name='descripcion' rows='4' cols='40'>$desc</textarea>";

    echo "<label for='foto'><strong>Cambiar foto de perfil:</strong></label>";
    echo "<input type='file' name='foto'>";

    echo "<input type='submit' value='Guardar cambios'>";
    echo "</form>";

} else {
     "<p>No se ha encontrado el perfil de usuario.</p>";
}
?>

