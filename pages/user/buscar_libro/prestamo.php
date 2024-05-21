<?php
session_start();

if (isset($_SESSION['password'])) {
    $password_session = $_SESSION['password'];

    if (isset($_POST['password'])) {
        $password_form = $_POST['password'];

        if ($password_form === $password_session) {
            if (isset($_POST['libroSeleccionado'])) {
                $libro_seleccionado = $_POST['libroSeleccionado'];
                $dias_devuelta = $_POST['diasDevuelta'];

                $connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");

                if (!$connection) {
                    echo "Ha ocurrido un error al conectar a la base de datos.";
                    exit;
                }

                $id_persona = $_SESSION['idPersona'];

                $query_libro = "SELECT id_libro, numerodecopiasdisponibles, vecesprestado FROM libros WHERE titulo = $1";
                $result_libro = pg_query_params($connection, $query_libro, array($libro_seleccionado));

                if (!$result_libro) {
                    echo "Error al obtener el ID del libro.";
                    pg_close($connection);
                    exit;
                }

                $row_libro = pg_fetch_assoc($result_libro);
                $id_libro = $row_libro['id_libro'];
                $numeroDeCopiasDisponibles = $row_libro['numerodecopiasdisponibles'];
                $vecesPrestado = $row_libro['vecesprestado'];

                if (!$id_libro) {
                    echo "Libro no encontrado.";
                    pg_close($connection);
                    exit;
                }

                if ($numeroDeCopiasDisponibles > 0) {

                    $fecha_devolucion = date('Y-m-d', strtotime("+$dias_devuelta days"));
                    $query_prestamo = "INSERT INTO prestamos (fecha_prestamo, fecha_devolucion, codigo_libro, id_persona) VALUES (NOW(), '$fecha_devolucion', $1, $2)";
                    $result_prestamo = pg_query_params($connection, $query_prestamo, array($id_libro, $id_persona));

                    if ($result_prestamo) {
                        $nuevo_numeroDeCopiasDisponibles = $numeroDeCopiasDisponibles - 1;
                        $nuevo_vecesPrestado = $vecesPrestado + 1;

                        $query_update_libro = "UPDATE libros SET numerodecopiasdisponibles = $1, vecesprestado = $2 WHERE id_libro = $3";
                        $result_update_libro = pg_query_params($connection, $query_update_libro, array($nuevo_numeroDeCopiasDisponibles, $nuevo_vecesPrestado, $id_libro));

                        if ($result_update_libro) {
                            header("Location: screen.php?prestamoExitoso=1");
                            exit;
                        } else {
                            header("Location: screen.php?errorActualizar=1");
                            exit;
                        }
                    } else {
                        header("Location: screen.php?errorPrestamo=1");
                        exit;
                    }
                } else {
                    header("Location: screen.php?noHayCopias=1");
                    exit;
                    echo "No hay copias disponibles para el libro: " . htmlspecialchars($libro_seleccionado);
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
