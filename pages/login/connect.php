<?php
$connection = pg_connect("host=localhost dbname=biblioteca_escolar user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["user"];
    $contraseña = $_POST["password"];

    $query = "SELECT * FROM personas WHERE matricula = '$usuario' AND contraseña = '$contraseña'";
    $result = pg_query($connection, $query);

    if (pg_num_rows($result) > 0) {
        echo "Inicio de sesión exitoso<br>";
        $row = pg_fetch_assoc($result);

        $tipo_persona = $row['tipo_persona'];
        echo "Tipo de persona: $tipo_persona<br>";

        if ($tipo_persona == "Profesor") {
            header("Location: ../user/main_menu/main_menu_user.html");
        } else if ($tipo_persona == "Usuario") {
            header("Location: ../user/main_menu/main_menu_user.html");
        } else if ($tipo_persona == "Administrativo") {
            header("Location: ../admin/main_menu/main_menu_admin.html");
        }
    } else {
        $error_message = "Credenciales incorrectas";
        header("Location: index.php?error=" . urlencode($error_message));
        exit;
    }
}
