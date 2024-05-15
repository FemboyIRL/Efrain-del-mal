<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Realizar la conexión a la base de datos
    $db = new SQLite3("../../assets/db/biblioteca.db");

    // Verificar si la conexión fue exitosa
    if (!$db) {
        die("No se pudo conectar a la base de datos");
    }

    // Realizar la consulta para autenticar al usuario (ejemplo)
    $consulta = "SELECT * FROM personas WHERE usuario='$usuario' AND contraseña='$contraseña'";
    $resultado = $db->query($consulta);

    // Verificar si se encontró un usuario válido
    if ($resultado->fetchArray()) {
        // Usuario autenticado correctamente
        echo "Usuario autenticado correctamente";
    } else {
        // Usuario no autenticado
        echo "Usuario o contraseña incorrectos";
    }

    // Cerrar la conexión a la base de datos
    $db->close();
}
?>
