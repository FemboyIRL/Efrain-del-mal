<?php
$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}


$query2 = "SELECT DISTINCT categoria FROM libros";
$result2 = pg_query($connection, $query2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>
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
                            <input class="form-control me-2" type="search" placeholder="Buscar por Libro, Autor, Editorial, ISBN" aria-label="Search">
                            <button class="btn btn-success" type="submit">
                                Buscar
                            </button>
                        </form>
                        <div class="offcanvas-footer p-3 bg-dark position-absolute bottom-0 start-0 end-0">
                            <form action="logout.php" method="POST">
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
</body>
</html>