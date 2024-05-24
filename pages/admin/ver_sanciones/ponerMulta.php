<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
    if (!$connection) {
        echo "Ha ocurrido un error";
        exit;
    }

    $id_persona = $_POST["userId"]; 
    $razon_sancion = $_POST["reason"]; 
    $multa = $_POST["amount"];

    $query = "INSERT INTO sanciones (id_persona, razon_sancion, fecha_sancion, multa) VALUES ($id_persona, '$razon_sancion', CURRENT_DATE, $multa)";

    $result = pg_query($connection, $query);

    if ($result) {
        header('Location: screen.php?multaPuesta=1');
        exit;
        echo "Multa registrada correctamente.";
    } else {
        echo "Error al registrar la multa: " . pg_last_error($connection);
    }

    pg_close($connection);
}
?>
