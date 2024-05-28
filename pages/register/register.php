<?php
session_start();

$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["email"];
    $matricula = $_POST["matricula"];
    $telefono = $_POST["telefono"];
    $password = $_POST["password"];

    $apellidoArray = explode(" ", $apellidos);
    $apellidoPaterno = $apellidoArray[0];
    $apellidoMaterno = isset($apellidoArray[1]) ? $apellidoArray[1] : "";

    $query = "INSERT INTO personas (tipo_persona, nombres, apellidoPaterno, apellidoMaterno, correo, matricula, telefono, password) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

    $stmt = pg_prepare($connection, "", $query);

    $result = pg_execute($connection, "", array('Usuario', $nombres, $apellidoPaterno, $apellidoMaterno, $correo, $matricula, $telefono, $password));

    if ($result) {
        echo "Registro exitoso. ¡Bienvenido a Lectorium!";
        header("Location: ../login/index.php?exito=1");
        exit;
    } else {
        echo "Error al registrar el usuario: " . pg_last_error($connection);
    }
}
?>
