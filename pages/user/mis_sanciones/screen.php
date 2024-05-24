<?php
session_start();
$connection = pg_connect("host=localhost dbname=BibliotecaFinal user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if (isset($_SESSION['idPersona'])) {
    $idPersona = $_SESSION['idPersona'];

    $query = "SELECT * FROM sanciones WHERE id_persona = $1 ORDER BY fecha_sancion DESC";
    $result = pg_prepare($connection, "get_sanciones", $query);
    $result = pg_execute($connection, "get_sanciones", array($idPersona));

    if (!$result) {
        echo "Error en la consulta: " . pg_last_error($connection);
    }
} else {
    echo "No se ha encontrado el ID del usuario en la sesión.";
}
$query2 = "SELECT DISTINCT categoria FROM libros";
$result2 = pg_query($connection, $query2);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis sanciones</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css">
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
                                            echo "<li><a class='dropdown-item' href='#'>" . $row['categoria'] . "</a></li>";
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
                        <form class="d-flex mt-3" action="search.php" method="POST" role="search">
                            <input class="form-control me-2" name="search_query" type="search" placeholder="Buscar por Libro, Autor o ISBN" aria-label="Search">
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


    <div class="container" style="padding-top: 50px;">
        <div class="error">
            <?php
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo "<p>Credenciales incorrectas. Por favor, inténtalo de nuevo.</p>";
            }
            if (isset($_GET['errorContraseña']) && $_GET['errorContraseña'] == 1) {
                echo "<p>No se ha enviado la contraseña.</p>";
            } 
            if (isset($_GET['errorEliminar']) && $_GET['errorEliminar'] == 1) {
                echo "<p>Ha ocurrido un error al eliminar la sanción.</p>";
            } 
            if (isset($_GET['errorId']) && $_GET['errorId'] == 1) {
                echo "<p>La ID de la sanción no se ha proporcionado.</p>";
            } 
            ?>
        </div>
        <div class="confirmed">
            <?php
            if (isset($_GET['sancionEliminada']) && $_GET['sancionEliminada'] == 1) {
                echo "<p>La sanción ha sido eliminada correctamente.</p>";
            }
            ?>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="containerTable">
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="col-2">Razón de la sanción</th>
                                        <th scope="col" class="col-2">Fecha</th>
                                        <th scope="col" class="col-2">Multa a pagar</th>
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
                                            echo "<td class='col-2'>" . $row["id_sancion"] . "</td>";
                                            echo "<td class='col-2'>" . $row["razon_sancion"] . "</td>";
                                            echo "<td class='col-2'>" . $row["fecha_sancion"] . "</td>";
                                            echo "<td class='col-2'>" . $row["multa"] . "</td>";
                                            echo "<td>  
                                            <button type='button' class='btn btn-dark' data-bs-toggle='modal' data-bs-target='#staticBackdrop' data-multa='" . $row["multa"] . "' data-idSancion='" . $row["id_sancion"] . "'>
                                                <i class='bi bi-cash-stack'></i>
                                            </button>
                                        </td>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Muy bien, no tienes ni una sanción.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Pagar Multa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="pagarMulta.php" method="POST">
                    <div class="modal-body">
                        <p>Cantidad a pagar: <span id="multaPagar"></span></p>
                        <p>Metodo de pago:</p>
                        <div class="input-box">
                            <select name="metodoPago" required>
                                <option value="visa"> Visa</option>
                                <option value="mastercard">Mastercard</option>
                                <option value="efectivo">Efectivo</option>
                            </select>
                            <i class="bi bi-credit-card-fill"></i>
                        </div>
                        <p>Ingrese su contraseña:</p>
                        <div class="input-box">
                            <input type="password" name="password" placeholder="Contraseña" required>
                            <i class='bx bxs-lock'></i>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="multaEnPago" name="multaEnPago">
                        <input type="hidden" id="idSancion" name="idSancion">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Solicitar préstamo</button>
                    </div>
                </form>
            </div>
        </div>
</body>
<script src="script.js"></script>

</html>