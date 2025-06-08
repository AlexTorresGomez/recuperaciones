<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST['user_name']));
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Verificar campos vacíos o con espacios
    if (
        empty($username) || empty($email) || empty($password) || empty($confirm_password) ||
        preg_match('/\s/', $username) || preg_match('/\s/', $email) ||
        preg_match('/\s/', $password) || preg_match('/\s/', $confirm_password)
    ) {
        echo "<script>alert('Se han encontrado campos vacíos o con espacios.'); window.history.back();</script>";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE user_name = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('El nombre de usuario o correo electrónico ya están en uso.'); window.history.back();</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (user_name, email, contraseña) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado correctamente. Redirigiendo al login.'); window.location.href = '/Cluster_Role/proyecto/login/login.html';</script>";
            exit();
        } else {
            echo "<script>alert('Error al registrar el usuario.'); window.history.back();</script>";
        }
    }
}
?>
