<?php
session_start();

if (isset($_SESSION['password'])) {
    $password_session = $_SESSION['password'];

    if (isset($_POST['password'])) {
        $password_form = $_POST['password'];

        if ($password_form === $password_session) {
            if (isset($_POST['idSancion'])) {
                $idSancion = $_POST['idSancion'];
                
                $connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
                
                $query = "DELETE FROM sanciones WHERE id_sancion = $idSancion";
                
                $result = pg_query($connection, $query);
                
                if ($result) {
                    header("Location: screen.php?sancionEliminada=1");
                    exit;
                    echo "La sanción ha sido eliminada correctamente.";
                } else {
                    header("Location: screen.php?errorEliminar=1");
                    exit;
                    echo "Ha ocurrido un error al eliminar la sanción.";
                }
            } else {
                header("Location: screen.php?errorId=1");
                exit;
                echo "La ID de la sanción no se ha proporcionado.";
            }
        } else {
            header("Location: screen.php?error=1");
            exit;
        }
    } else {
        header("Location: screen.php?errorContraseña=1");
        exit;
    }
}
?>
