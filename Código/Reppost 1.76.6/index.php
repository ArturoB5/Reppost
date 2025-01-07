<?php include('Config/dbcon.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Reppost</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Reppost" />
    <link rel="shortcut icon" href="View/Images/app_images/logo.ico">
    <link rel="stylesheet" type="text/css" href="View/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="View/css/style2.css" />
    <link rel="stylesheet" type="text/css" href="View/css/animate-custom.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="clr">
            <div class="title"></div>
            <a href="index.php">
                <img class="logo" src="View/Images/app_images/logo.png" alt="Logo" width="150">
            </a>
        </div>
        <section>
            <div id="container_demo">
                <a class="hiddenanchor" id="toregister"></a>
                <a class="hiddenanchor" id="tologin"></a>
                <div id="wrapper">
                    <div id="login" class="animate form">
                        <form method="post" action="login.php">
                            <div class="title">Reppost</div>
                            <hr>
                            <p style="position: relative;">
                                <label for="username"> Usuario o Correo electrónico</label>
                                <input id="username" name="username" required="required" type="text" />
                                <i class="fa fa-user" style="position: absolute; left: 10px; top: 34px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="password"> Contraseña </label>
                                <input id="password" name="password" required="required" type="password" />
                                <i class="fa fa-key" style="position: absolute; left: 10px; top: 34px; color: rgb(97 160 159)"></i>
                                <span id="toggle-password" class="fa fa-eye" style="position: absolute; right: 10px; top: 35px; cursor: pointer; color: rgb(97 160 159)"></span>
                            </p>
                            <p class="login button">
                                <input type="submit" name="login" value="Ingresar" />
                            </p>
                            <p>
                                <center>
                                    <a href="forgot_password.php">¿Olvidaste tu contraseña?</a>
                                </center>
                            </p>
                            <p class="change_link">
                                Registrate
                                <a href="#toregister" class="to_register">Crear cuenta</a>
                            </p>
                        </form>
                        <?php
                        if (isset($_GET['error'])) {
                            $error_message = '';
                            switch ($_GET['error']) {
                                case 'invalid_password':
                                    $error_message = 'La contraseña es incorrecta.';
                                    break;
                                case 'user_not_found':
                                    $error_message = 'El usuario no existe.';
                                    break;
                                default:
                                    $error_message = 'Hubo un error desconocido.';
                                    break;
                            }
                            echo "<div class='error-message'>$error_message</div>";
                        }
                        ?>
                    </div>
                    <div id="register" class="animate form">
                        <form action="signup_save.php" method="post" autocomplete="on">
                            <h4>REGISTRATE</h4>
                            <hr>
                            <p style="position: relative;">
                                <label for="usernamesignup" class="uname">Nombre de usuario</label>
                                <input id="usernamesignup" name="username" required="required" type="text" placeholder="Usuario" />
                                <i class="fa fa-user" style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="emailsignup" class="youmail">Correo electronico</label>
                                <input id="emailsignup" name="email" required="required" type="email" placeholder="Correo electrónico" />
                                <i class="fa fa-envelope" style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="firstname">Nombre/s</label>
                                <input id="firstname" name="firstname" required="required" type="text" placeholder="Nombre" />
                                <i class="fa fa-pencil" style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="lastname">Apellido/s</label>
                                <input id="lastname" name="lastname" required="required" type="text" placeholder="Apellido" />
                                <i class="fa fa-pencil" style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="birthdate">Fecha de Nacimiento</label>
                                <input id="birthdate" name="birthdate" required="required" type="date" />
                                <i class="fa fa-calendar" style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="gender">Género</label>
                            <div class="gender-options">
                                <input type="checkbox" id="hombre" name="gender" value="Hombre" onclick="onlyOne(this)" class="gender-checkbox">
                                <label for="hombre">Hombre</label>
                                <input type="checkbox" id="mujer" name="gender" value="Mujer" onclick="onlyOne(this)" class="gender-checkbox">
                                <label for="mujer">Mujer</label>
                                <input type="checkbox" id="otro" name="gender" value="Otro" onclick="onlyOne(this)" class="gender-checkbox">
                                <label for="otro">Otro</label>
                            </div>
                            </p>
                            <script>
                                function onlyOne(checkbox) {
                                    var checkboxes = document.getElementsByName('gender');
                                    checkboxes.forEach((item) => {
                                        if (item !== checkbox) item.checked = false;
                                    });
                                }
                            </script>
                            <p style="position: relative;">
                                <label for="mobile">Número de celular</label>
                                <input id="mobile" name="mobile" required="required" type="tel" placeholder="Móvil" />
                                <i class="fa fa-phone" style="position: absolute; left: 10px; top: 33px; color: rgb(97 160 159)"></i>
                            </p>
                            <p style="position: relative;">
                                <label for="passwordsignup" style="position: relative; display: inline-block;">
                                    Contraseña
                                    <i class="fa fa-info-circle"
                                        style="margin-left: 5px; color: #61A09F; cursor: pointer;"
                                        title="La contraseña debe tener al menos:
- 8 caracteres.
- Una letra mayúscula.
- Una letra minúscula.
- Un número.
- Un carácter especial (@, #, $, etc.).">
                                    </i>
                                </label>
                                <input id="passwordsignup" name="password" required="required" type="password" placeholder="Contraseña" />
                                <i class="fa fa-key" style="position: absolute; left: 10px; top: 33px; color: rgb(97 160 159)"></i>
                                <i id="toggle-password-signup" class="fa fa-eye" style="position: absolute; right: 10px; top: 33px; cursor: pointer; color: rgb(97 160 159)"></i>
                                <!-- Semáforo -->
                            <div id="password-strength" style="margin-top: 10px; height: 10px; width: 100%; background-color: #ccc; border-radius: 5px;">
                                <div id="strength-bar" style="height: 100%; width: 0%; border-radius: 5px;"></div>
                            </div>
                            <!-- Etiqueta de dificultad -->
                            <span id="strength-text" style="font-size: 14px; color: #555; margin-top: 5px; display: inline-block;">
                                Dificultad:
                            </span>
                            </p>
                            <p style="position: relative;">
                                <label for="confirm_password">Confirmar contraseña
                                    <i class="fa fa-info-circle"
                                        style="margin-left: 5px; color: #61A09F; cursor: pointer;"
                                        title="La contraseña debe ser igual que la anterior">
                                    </i>
                                </label>
                                <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirmar contraseña" required="required">
                                <i class="fa fa-key" style="position: absolute; left: 10px; top: 34px; color: rgb(97 160 159)"></i>
                                <i id="toggle-password-confirm" class="fa fa-eye" style="position: absolute; right: 10px; top: 35px; cursor: pointer; color: rgb(97 160 159)"></i>
                            </p>
                            <p class="signin button">
                                <input type="submit" value="Registrate" />
                            </p>
                            <p class="change_link">
                                ¿Ya estás registrado?
                                <a href="#tologin" class="to_register">Ingresar</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
<footer>
    <script src="View/JS/pass_diff.js"></script>
    <script src="View/JS/view_pass_form.js"></script>
</footer>

</html>