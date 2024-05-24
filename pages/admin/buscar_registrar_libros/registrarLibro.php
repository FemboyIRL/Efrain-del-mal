<?php
// Iniciar sesión (si aún no está iniciada)
session_start();

// Conectar a la base de datos
$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error al conectar a la base de datos";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $isbn = $_POST['isbn'];
    $autor = $_POST['autor'];
    $categoria = $_POST['categoria'];
    $numCopias = $_POST['numCopias'];
    $numCopiasDisp = $_POST['numCopiasDisp']; 

    $query = "INSERT INTO libros (titulo, isbn, autor, categoria, numeroDeCopiasDisponibles, numeroDeCopias ) VALUES ('$titulo', '$isbn', '$autor', '$categoria', $numCopiasDisp, $numCopias)";
    $result = pg_query($connection, $query);

    if ($result) {
        header("Location: screen.php?registroExitoso=1");
        exit;
    } else {
        header("Location: screen.php?errorRegistro=1");
        exit;
    }
} else {
    header("Location: main_menu_admin.php");
    exit;
}
?>
