<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lectorium - Registro</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <div class="book">
            <div class="cover">
                <img src="../../assets/images/Logo.jpg" alt="logoLectorium">
                <h3>"Únete a Lectorium"</h3>
            </div>
            <div class="page"></div>
            <div class="page"></div>
            <div class="page"></div>
            <div class="page"></div>
            <div class="last-page">
                <div class="last-page-content">
                    <form action="register.php" method="post">
                        <h2>Registro en Lectorium</h2>
                        <div class="input-box">
                            <input type="text" name="nombres" placeholder="Nombres" required>
                            <i class='bx bxs-user'></i>
                        </div>
                        <div class="input-box">
                            <input type="text" name="apellidos" placeholder="Apellidos" required>
                            <i class='bx bxs-user'></i>
                        </div>

                        <div class="input-box">
                            <input type="email" name="email" placeholder="Correo electrónico" required>
                            <i class='bx bxs-envelope'></i>
                        </div>
                        <div class="input-box">
                            <input type="text" name="matricula" placeholder="Matrícula" required>
                            <i class='bx bxs-key'></i>
                        </div>
                        <div class="input-box">
                            <input type="tel" name="telefono" placeholder="Teléfono" required>
                            <i class='bx bxs-phone'></i>
                        </div>
                        <div class="input-box">
                            <input type="password" name="password" placeholder="Contraseña" required>
                            <i class='bx bxs-lock'></i>
                        </div>
                        <button class="btn" type="submit">
                            Registrarse
                        </button>
                        <div class="login-link">
                            <p>
                                Ya tienes una cuenta? <a href="../login/index.php">Inicia sesión</a>
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
