<?php
session_start();
session_unset();    
session_destroy();  
header("Location: /Cluster_Role/proyecto/pagina_main/index.html");
exit();
?>
