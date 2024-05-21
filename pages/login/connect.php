<?php
session_start(); 
$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["user"];
    $contraseña = $_POST["password"];

    $query = "SELECT * FROM personas WHERE matricula = '$usuario' AND password = '$contraseña'";
    $result = pg_query($connection, $query);

    if (pg_num_rows($result) > 0) {
        echo "Inicio de sesión exitoso<br>";
        $row = pg_fetch_assoc($result);

        $_SESSION['idPersona'] = $row['id_persona'];
        $_SESSION['usuario'] = $row['matricula'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['nombres'] = $row['nombres'];
        $_SESSION['apellidoPaterno'] = $row['apellidopaterno'];
        $_SESSION['apellidoMaterno'] = $row['apellidomaterno'];


        session_regenerate_id(true); 

        $tipo_persona = $row['tipo_persona'];
        echo "Tipo de persona: $tipo_persona<br>";

        if ($tipo_persona == "Profesor") {
            header("Location: ../user/main_menu/main_menu_user.php");
            exit;
        } else if ($tipo_persona == "Usuario") {
            header("Location: ../user/main_menu/main_menu_user.php");
            exit;
        } else if ($tipo_persona == "Admin") {
            header("Location: ../admin/main_menu/main_menu_admin.php");
            exit;
        }
    } else {
        header("Location: index.php?error=1");
        exit;
    }
}
