<?php
session_start();
header('Content-Type: application/json');

include('conexion.php');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST['user_name'] ?? '');
    $password = $_POST['pass'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Nombre de usuario o contraseña vacíos.']);
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE user_name = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();

        if (!empty($usuario['contraseña']) && password_verify($password, $usuario['contraseña'])) {
            $_SESSION['user_name'] = $usuario['user_name'];
            $_SESSION['id_user'] = $usuario['id_user'];

            $redirect = ($usuario['administrador'] == 1)
                ? "/Cluster_Role/proyecto/plana_administracio/admin.php"
                : "/Cluster_Role/proyecto/plana_usuari/usuario.php";

            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit();
    }
}
?>



