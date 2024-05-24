<?php
session_start();

if (isset($_SESSION['password'])) {
    $password_session = $_SESSION['password'];

    if (isset($_POST['password'])) {
        $password_form = $_POST['password'];

        if ($password_form === $password_session) {
            if (isset($_POST['libroSeleccionado'])) {
                $libro_seleccionado = $_POST['libroSeleccionado'];

                $connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");

                if (!$connection) {
                    echo "Ha ocurrido un error al conectar a la base de datos.";
                    exit;
                }

                $id_persona = $_SESSION['idPersona'];

                $query_libro = "SELECT id_libro, numerodecopiasdisponibles FROM libros WHERE titulo = $1";
                $result_libro = pg_query_params($connection, $query_libro, array($libro_seleccionado));

                if (!$result_libro) {
                    echo "Error al obtener el ID del libro.";
                    pg_close($connection);
                    exit;
                }

                $row_libro = pg_fetch_assoc($result_libro);
                $id_libro = $row_libro['id_libro'];
                $numeroDeCopiasDisponibles = $row_libro['numerodecopiasdisponibles'];

                if (!$id_libro) {
                    echo "Libro no encontrado.";
                    pg_close($connection);
                    exit;
                }

                $query_eliminar_prestamo = "DELETE FROM prestamos WHERE codigo_libro = $1 AND id_persona = $2 RETURNING id_prestamo";
                $result_eliminar_prestamo = pg_query_params($connection, $query_eliminar_prestamo, array($id_libro, $id_persona));

                if ($result_eliminar_prestamo && pg_affected_rows($result_eliminar_prestamo) > 0) {
                    $nuevo_numeroDeCopiasDisponibles = $numeroDeCopiasDisponibles + 1;

                    $query_update_libro = "UPDATE libros SET numerodecopiasdisponibles = $1 WHERE id_libro = $2";
                    $result_update_libro = pg_query_params($connection, $query_update_libro, array($nuevo_numeroDeCopiasDisponibles, $id_libro));

                    if ($result_update_libro) {
                        header("Location: screen.php?eliminacionExitosa=1");
                        exit;
                    } else {
                        header("Location: screen.php?errorActualizar=1");
                        exit;
                    }
                } else {
                    header("Location: screen.php?errorEliminarPrestamo=1");
                    exit;
                }

                pg_close($connection);
            } else {
                header("Location: screen.php?errorLibroSeleccionado=1");
                exit;
            }
        } else {
            header("Location: screen.php?error=1");
            exit;
        }
    } else {
        header("Location: screen.php?errorContraseña=1");
        exit;
    }
} else {
    header("Location: screen.php?errorContraseñaAlmacenada=1");
    exit;
}
