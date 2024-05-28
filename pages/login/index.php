<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lectorium</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <div class="book">
            <div class="cover">
                <img src="../../assets/images/Logo.jpg" alt="logoLectorium">
                <h3>"Siempre nos quedaran los libros"</h3>
            </div>
            <div class="page"></div>
            <div class="page"></div>
            <div class="page"></div>
            <div class="page"></div>
            <div class="last-page">
                <div class="last-page-content">
                    <form action="connect.php" method="post">
                        <h2>Bienvenido a Lectorium</h2>
                        <div class="input-box">
                            <input type="text" name="user" placeholder="Usuario/Matricula" required>
                            <i class='bx bxs-user'></i>
                        </div>
                        <div class="input-box">
                            <input type="password" name="password" placeholder="Contraseña" required>
                            <i class='bx bxs-lock'></i>
                        </div>
                        <div class="error">
                            <?php
                            if (isset($_GET['error']) && $_GET['error'] == 1) {
                                echo "<p>Credenciales incorrectas. Por favor, inténtalo de nuevo.</p>";
                            }
                            ?>
                        </div>
                        <div class="exito">
                            <?php
                            if (isset($_GET['exito']) && $_GET['exito'] == 1) {
                                echo "<p>Registro exitoso</p>";
                            }
                            ?>
                        </div>
                        <div class="remember-forgot">
                            <label>
                                <input type="checkbox">
                                Recuérdame
                            </label>
                            <a href="#">Olvidaste tu contraseña?</a>
                        </div>
                        <button class="btn" type="submit">
                            Ingresar
                        </button>
                        <div class="register-link">
                            <p>
                                No tienes una cuenta?
                                <a href="../register/screen.php">Registrate</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="back-cover"></div>
        </div>
    </div>
</body>

</html>