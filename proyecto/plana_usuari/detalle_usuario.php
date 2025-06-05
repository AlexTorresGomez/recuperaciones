<?php
include 'conexion.php';

$id = $_GET['id'];

$voto_cookie = "voto_usuario_" . $id;

if (isset($_GET['accion']) && !isset($_COOKIE[$voto_cookie])) {
    $accion = $_GET['accion'];

    if ($accion === 'like') {
        $conn->query("UPDATE usuarios SET positivo = positivo + 1 WHERE id_user = $id");
        setcookie($voto_cookie, 'like', time() + (86400 * 365)); // guarda cookie por 1 año
    } elseif ($accion === 'dislike') {
        $conn->query("UPDATE usuarios SET negativo = negativo + 1 WHERE id_user = $id");
        setcookie($voto_cookie, 'dislike', time() + (86400 * 365));
    }

    header("Location: detalle_usuario.php?id=$id");
    exit;
}

$stmt = $conn->prepare("SELECT user_name, creation_date, estado, positivo, negativo, profile_photo FROM usuarios WHERE id_user = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nombre, $fecha, $des, $likes, $dislikes, $foto);
$stmt->fetch();
$stmt->close();

if (!isset($nombre)) {
    echo "<p>Usuario no encontrado.</p>";
    return;
}
?>

<div class="user-profile" style="text-align: center; color:  #c0c0c0 ;">
  <img class="profile" 
       src="data:image/png;base64,<?php echo base64_encode($foto); ?>" 
       alt="Foto de perfil" 
       style="
  width: 200px;
  height: 200px;
  border-radius: 50%;
  object-fit: contain;
  display: block;
  margin: 0 auto;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
"
>
  
  <h2><?php echo htmlspecialchars($nombre); ?></h2>
  <p>Fecha de creación: <?php echo htmlspecialchars($fecha); ?></p>
  <p><?php echo htmlspecialchars($des); ?></p>
  <p>👍 Likes: <?php echo $likes; ?> | 👎 Dislikes: <?php echo $dislikes; ?></p>

  <div class="reaction-buttons" style="margin-top: 20px;">
    <?php if (!isset($_COOKIE[$voto_cookie])): ?>
      <a href="?id=<?php echo $id; ?>&accion=like">
        <img src="../foto/icons/like.png" alt="Like" style="width: 40px; margin-right: 20px;">
      </a>
      <a href="?id=<?php echo $id; ?>&accion=dislike">
        <img src="../foto/icons/dislike.png" alt="Dislike" style="width: 40px;">
      </a>
    <?php else: ?>
      <p>Ya has votado (<?php echo $_COOKIE[$voto_cookie]; ?>).</p>
    <?php endif; ?>
  </div>
</div>
