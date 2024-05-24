<?php
session_start();
if (!isset($_SESSION['idPersona'])) {
    header('Location: ../../login/index.php');
    exit;
}

$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'];
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    
    $name_parts = explode(" ", $name);
    $nombres = $name_parts[0];
    $apellidoPaterno = isset($name_parts[1]) ? $name_parts[1] : '';
    $apellidoMaterno = isset($name_parts[2]) ? $name_parts[2] : '';

    $update_query = "UPDATE personas SET correo = $1, nombres = $2, apellidoPaterno = $3, apellidoMaterno = $4 WHERE matricula = $5";
    $result = pg_query_params($connection, $update_query, array($email, $nombres, $apellidoPaterno, $apellidoMaterno, $matricula));

    if ($result) {
        header('Location: ../../logout/logout.php');
    } else {
        echo "Error al actualizar los datos.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
