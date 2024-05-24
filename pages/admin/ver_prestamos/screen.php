<?php
session_start();
$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

$idPersona = $_SESSION['idPersona'];
$params = array();
$query = "SELECT 
    prestamos.id_prestamo,
    prestamos.fecha_prestamo,
    prestamos.fecha_devolucion,
    libros.titulo,
    libros.isbn,
    libros.autor,
    libros.categoria,
    libros.numeroDeCopiasDisponibles,
    libros.numeroDeCopias,
    libros.vecesPrestado,
    libros.calificacion,
    personas.matricula AS matricula_usuario
  FROM 
    prestamos
  JOIN
    libros ON prestamos.codigo_libro = libros.id_libro
  JOIN
    personas ON prestamos.id_persona = personas.id_persona
  ORDER BY 
    prestamos.fecha_prestamo DESC";


$result = pg_prepare($connection, "get_prestamos", $query);

if ($result) {
    $result = pg_execute($connection, "get_prestamos", $params);
}


$query2 = "SELECT DISTINCT categoria FROM libros";
$result2 = pg_query($connection, $query2);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis prestamos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script defer src="script.js"></script>
</head>

<body>
    <div class="container mt-5">
        <nav class="navbar navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="../main_menu/main_menu_admin.php">
                    <img src="../../../assets/images/Logo.jpg" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%;">
                    Lectorium
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">
                            Bienvenido <?php
                                        if (isset($_SESSION['nombres']) && isset($_SESSION['apellidoPaterno']) && isset($_SESSION['apellidoMaterno'])) {
                                            echo htmlspecialchars($_SESSION['nombres']) . " " . htmlspecialchars($_SESSION['apellidoPaterno']) . " " . htmlspecialchars($_SESSION['apellidoMaterno']);
                                        } elseif (isset($_SESSION['nombres']) && isset($_SESSION['apellidoPaterno'])) {
                                            echo htmlspecialchars($_SESSION['nombres']) . " " . htmlspecialchars($_SESSION['apellidoPaterno']);
                                        } else {
                                            echo "Invitado";
                                        }
                                        ?>
                        </h5>
                        <button id="close" type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="main_menu_admin.php">
                                    Lectorium
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../ver_prestamos/screen.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ver prestamos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../ver_sanciones/screen.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ver sanciones
                                </a>
                            </li>
                            <form class="d-flex mt-3" action="search.php" method="POST" role="search">
                                <input class="form-control me-2" name="search_query" type="search" placeholder="Buscar por Libro, Autor o ISBN" aria-label="Search">
                                <button class="btn btn-success" type="submit">
                                    Buscar
                                </button>
                            </form>
                            <div class="offcanvas-footer p-3 bg-dark position-absolute bottom-0 start-0 end-0">
                                <form action="../../logout/logout.php" method="POST">
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="container mt-4" style="padding-top: 50px;">
        <div class="row">
            <div class="col-12">
                <form class="d-flex" method="POST" action="search.php" role="search">
                    <input class="form-control me-2" type="search" name="search_query" placeholder="Buscar por Libro, Autor, Editorial, ISBN" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="error">
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p>Credenciales incorrectas. Por favor, inténtalo de nuevo.</p>";
        }
        if (isset($_GET['errorContraseña']) && $_GET['errorContraseña'] == 1) {
            echo "<p>No se ha enviado la contraseña.</p>";
        }
        if (isset($_GET['errorSearchBarVacia']) && $_GET['errorSearchBarVacia'] == 1) {
            echo "<p>Escribe algun libro, autor o ISBN por favor.</p>";
        }
        if (isset($_GET['errorContraseñaAlmacenada']) && $_GET['errorContraseñaAlmacenada'] == 1) {
            echo "<p>No se ha encontrado una contraseña almacenada en la sesión.</p>";
        }
        if (isset($_GET['errorLibroSeleccionado']) && $_GET['errorLibroSeleccionado'] == 1) {
            echo "<p>No se ha seleccionado ningún libro.</p>";
        }
        if (isset($_GET['noHayCopias']) && $_GET['noHayCopias'] == 1) {
            echo "<p>No hay copias disponibles para el libro</p>";
        }
        if (isset($_GET['errorPrestamo']) && $_GET['errorPrestamo'] == 1) {
            echo "<p>Error al solicitar el préstamo.</p>";
        }
        if (isset($_GET['errorActualizar']) && $_GET['errorActualizar'] == 1) {
            echo "<p>Error al actualizar la información del libro.</p>";
        }
        ?>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="align-right">
                    <div class="card small-card">
                        <div class="card-header" onclick="toggleCardBody()">
                            Excluir Categorías
                        </div>
                        <div class="card-body d-none" id="cardBody">
                            <form method="POST" action="excluirCategorias.php" id="filterForm">
                                <?php
                                pg_result_seek($result2, 0);
                                if (pg_num_rows($result2) > 0) {
                                    $counter = 0;
                                    while ($row = pg_fetch_assoc($result2)) {
                                        echo "<div class='form-check'>";
                                        echo "<input class='form-check-input' type='radio' name='excludeCategories[]' value='" . $row['categoria'] . "' id='excludedCategory" . $counter . "'>";
                                        echo "<label class='form-check-label' for='excludedCategory" . $counter . "'>" . $row['categoria'] . "</label>";
                                        echo "</div>";
                                        $counter++;
                                    }
                                } else {
                                    echo "<p>No hay categorías disponibles</p>";
                                }
                                ?>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="align-right">
                    <div class="card small-card">
                        <div class="card-header" onclick="toggleCardBody2()">
                            Categorías
                        </div>
                        <div class="card-body d-none" id="cardBody2">
                            <form method="POST" action="filtrarCategorias.php" id="filterForm2">
                                <?php
                                pg_result_seek($result2, 0);
                                if (pg_num_rows($result2) > 0) {
                                    $counter = 0;
                                    while ($row = pg_fetch_assoc($result2)) {
                                        echo "<div class='form-check'>";
                                        echo "<input class='form-check-input' type='radio' name='categories[]' value='" . $row['categoria'] . "' id='category" . $counter . "'>";
                                        echo "<label class='form-check-label' for='category" . $counter . "'>" . $row['categoria'] . "</label>";
                                        echo "</div>";
                                        $counter++;
                                    }
                                } else {
                                    echo "<p>No hay categorías disponibles</p>";
                                }
                                ?>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="containerTable">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="col-2">Título</th>
                            <th scope="col" class="col-2">Autor</th>
                            <th scope="col" class="col-2">Matricula</th>
                            <th scope="col" class="col-2">Fecha de prestamo</th>
                            <th scope="col" class="col-2">Fecha de devolución</th>
                            <th scope="col" class="col-1"></th>
                            <th scope="col" class="col-1">Calificación</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12" style="max-height: 400px; overflow-y: auto;">
                <table class="table">
                    <tbody>
                        <?php
                        $fechaActual = date("Y-m-d");
                        if (pg_num_rows($result) > 0) {
                            while ($row = pg_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td class='col-2'>" . $row["titulo"] . "</td>";
                                echo "<td class='col-2'>" . $row["autor"] . "</td>";
                                echo "<td class='col-2'>" . $row["matricula_usuario"] . "</td>";
                                echo "<td class='col-2'>" . $row["fecha_prestamo"] . "</td>";
                                if ($row["fecha_devolucion"] == null) {
                                    echo "<td class='col-2'>Indefinido</td>";
                                } elseif ($row["fecha_devolucion"] < $fechaActual) {
                                    echo "<td class='col-2'>Vencido</td>";
                                } else {
                                    echo "<td class='col-2'>" . $row["fecha_devolucion"] . "</td>";
                                }
                                echo "<td class='col-1'>" . $row["calificacion"] . " <i class='bi bi-star-fill'></i></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Aun no has solicitado ningún préstamo</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Solicitar prestamo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="prestamo.php" method="POST">
                    <div class="modal-body">
                        <p>Libro a devolver: <span id="nombreLibro"></span></p>
                        <p>Fecha de devolución: <i class='bx bxs-calendar'></i>
                        </p>
                        <div class="input-box">
                            <span class="real-time"></span>
                        </div>
                        <p>Ingrese su contraseña:</p>
                        <div class="input-box">
                            <input type="password" name="password" placeholder="Contraseña" required>
                            <i class='bx bxs-lock'></i>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="libroSeleccionado" name="libroSeleccionado">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Solicitar devolución</button>
                    </div>
                </form>
            </div>
        </div>
</body>

</html>