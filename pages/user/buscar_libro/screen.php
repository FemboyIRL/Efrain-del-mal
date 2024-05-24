<?php
session_start();
$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if (isset($_GET['searchBar']) && $_GET['searchBar'] != '') {
    $searchBar = pg_escape_string($_GET['searchBar']);
    $query = "SELECT * FROM libros WHERE autor LIKE '%$searchBar%' OR titulo LIKE '%$searchBar%' OR isbn LIKE '%$searchBar%' ORDER BY calificacion DESC";
} elseif (isset($_GET['categories']) && $_GET['categories'] != '') {
    $category = pg_escape_string($_GET['categories']);
    $query = "SELECT * FROM libros WHERE categoria = '$category' ORDER BY calificacion DESC";
} elseif (isset($_GET['excludedCategories']) && $_GET['excludedCategories'] != '') {
    $category = pg_escape_string($_GET['excludedCategories']);
    $query = "SELECT * FROM libros WHERE categoria != '$category' ORDER BY calificacion DESC";
} else {
    $query = "SELECT * FROM libros ORDER BY calificacion DESC";
}

$result = pg_query($connection, $query);

$query2 = "SELECT DISTINCT categoria FROM libros";
$result2 = pg_query($connection, $query2);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Libros</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-5">
        <nav class="navbar navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="../main_menu/main_menu_user.php">
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
                                <a class="nav-link active" aria-current="page" href="../main_menu/main_menu_user.php">
                                    Lectorium
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Categorías
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php
                                    if (pg_num_rows($result2) > 0) {
                                        while ($row = pg_fetch_assoc($result2)) {
                                            echo "<li><a class='dropdown-item' href='../buscar_libro/screen.php?categories=" . urlencode($row['categoria']) . "'>" . $row['categoria'] . "</a></li>";
                                        }
                                    } else {
                                        echo "<li><a class='dropdown-item' href='#'>No hay categorías disponibles</a></li>";
                                    }
                                    ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Mi cuenta
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item" href="../mi_cuenta/screen.php">
                                            Mi cuenta
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="../mis_prestamos/screen.php">
                                            Mis prestamos
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="../mis_sanciones/screen.php">
                                            Mis sanciones
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <form class="d-flex mt-3" role="search">
                            <input class="form-control me-2" type="search" placeholder="Buscar por Libro, Autor o ISBN" aria-label="Search">
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
                            <th scope="col" class='col-2'>Título</th>
                            <th scope="col" class='col-2'>ISBN</th>
                            <th scope="col" class='col-2'>Autor</th>
                            <th scope="col" class='col-2'>Categoría</th>
                            <th scope="col" class='col-2'>Pedir Prestamo</th>
                            <th scope="col" class='col-1'>Copias</th>
                            <th scope="col" class='col-1'>Calificación</th>
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
                        if (pg_num_rows($result) > 0) {
                            while ($row = pg_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td class='col-2'>" . $row["titulo"] . "</td>";
                                echo "<td class='col-2'>" . $row["isbn"] . "</td>";
                                echo "<td class='col-2'>" . $row["autor"] . "</td>";
                                echo "<td class='col-2'>" . $row["categoria"] . "</td>";
                                echo "<td class='col-2'>  <button type='button' class='btn btn-dark' data-bs-toggle='modal' data-bs-target='#staticBackdrop' data-libro='" . $row["titulo"] . "'>
                            <i class='bi bi-bookmark-plus-fill'></i> 
                          </button>
                           </td>";
                                echo "<td class='col-1'>" . $row["numerodecopiasdisponibles"] . "/" . $row["numerodecopias"] . "</td>";
                                echo "<td class='col-1'>" . $row["calificacion"] . " <i class='bi bi-star-fill'></i>         </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No se encontraron libros</td></tr>";
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
                        <p>Libro: <span id="nombreLibro"></span></p>
                        <p>Fecha de devolución:</p>
                        <div class="input-box">
                            <select name="diasDevuelta" required>
                                <option value="15">15 días</option>
                                <option value="30">30 días</option>
                                <option value="45">45 días</option>
                                <option value="60">60 días</option>
                            </select>
                            <i class='bx bxs-calendar'></i>
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
                        <button type="submit" class="btn btn-primary">Solicitar préstamo</button>
                    </div>
                </form>
            </div>
        </div>
</body>

</html>