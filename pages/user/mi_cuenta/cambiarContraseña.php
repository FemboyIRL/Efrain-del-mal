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
    $idPersona = $_SESSION['idPersona'];
    $password_session = $_SESSION['password'];
    $current_password = $_POST['currentPassword'];
    $new_password = $_POST['newPassword'];
    $confirm_password = $_POST['repeatNewPassword'];

    if ($new_password !== $confirm_password) {
        header('Location: screen.php?errorMatch=1');
        echo "Las nuevas contraseñas no coinciden.";
        exit;
    }

    if ($current_password === $password_session) {
        $update_query = "UPDATE personas SET password = $1 WHERE id_persona = $2";
        $update_result = pg_query_params($connection, $update_query, array($new_password, $idPersona));

        if ($update_result) {
            $_SESSION['password'] = $new_password;
            header('Location: ../../logout/logout.php');
            echo "Contraseña actualizada correctamente.";
            exit;
        } else {
            header('Location: screen.php?errorChange=1');
            exit;
            echo "Error al actualizar la contraseña.";
        }
    } else {
        header('Location: screen.php?errorPassword=1');
        exit;
        echo "La contraseña actual es incorrecta.";
    }
} else {
    header('Location: screen.php?solicitudInvalida=1');
    exit;
    echo "Método de solicitud no válido.";
}

pg_close($connection);
?>
