<?php
include_once('app.php');
session_start();
global $app;
$app = new App();
if ($app->isLogged())
    header('Location: main.php');
$app->head('Inicio de sesión');
$app->nav();
echo "<script src=\"../js/jquery.validate.min.js\"></script>
<script src=\"../js/login.js\"></script>
<center>";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = 0;

    if (isset($_GET['res']))
        $res = $_GET['res'];
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form-signin" id="login">
    <input type="text" placeholder="Correo electrónico" name="email" id="email" autofocus class="form-control"/>
    <input type="password" placeholder="Contraseña" name="password" id="password" class="form-control"/>
    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Iniciar sesión" id="submit"/>
</form>

<?php
if ($res == 1)
    echo "<label class='correct'>Deberás confirmar tu cuenta. Revisa tu correo electrónico</label>";
if ($res == 2)
    echo "<label class='error'>Usuario o contraseña incorrectos</label>";
if ($res == 3)
    echo "<label class='correct'>Cuenta confirmada. Ya puedes iniciar sesión</label>";
    $app->showError();
if ($res == 4)
    echo "<label class='error'>Ha ocurrido un error en la validación</label>";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['email'];
    $pass = $_POST['password'];
    if (!($app->isConnected())) {
        $app->showError();
    } else {
        if ($app->userLogin($user, $pass)) {
            $app->init_session($user);
            header('Location: main.php');
        } else {
            header('Location: login.php?res=2');
        }
    }
}

echo "</center>";
$app->foot();
?>