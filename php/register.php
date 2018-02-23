<?php
include_once('app.php');
global $app;
$app = new App();
if ($app->isLogged())
    header('Location: main.php');
$app->head("Registro");
$app->nav();
echo "<script src=\"../js/jquery.validate.min.js\"></script>
<script src=\"../js/register.js\"></script>
<center>";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = 0;
    
    if (isset($_GET['res'])) {
        $res = $_GET['res'];
    }
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form-signin" id="signup">
    <input type="text" placeholder="Nombre de usuario" name="username" id="username" autofocus class="form-control"/>
<?php
    if ($res == 2) {
        echo "<label id='username-error' class='error' for='username'>El nombre de usuario ya existe</label>";
    }
?>
    <input type="text" placeholder="Correo electrónico" name="email" id="email" class="form-control"/>
<?php
    if ($res == 1) {
        echo "<label id='email-error' class='error' for='email'>La dirección de correo electrónico ya está en uso</label>";
    }
?>
    <input type="password" placeholder="Contraseña" name="password" id="password" class="form-control"/>
    <input type="password" placeholder="Confirmar contraseña" name="passwordConf" id="passwordConf" class="form-control"/>
    <input type="hidden" name="nono" value="420coolness">
    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Registrarse" id="submit"/>
</form>

<?php
    if ($res == 3) {
        echo "<label class='correct'>No hacker 😶</label>";
    }
    if ($res == 4) {
        echo "<label class='error'>El proceso de registro ha fallado. Vuelve a intentarlo</label>";
    }
    if ($res == 5) {
        $app->showError();
    }
    if ($res == 6) {
        echo "<label class='error'>Algo ha salido mal</label>";
    }
} else {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash = substr(hash("sha256", microtime()), 0, 32);
    $nono = $_POST['nono'];

    if ($nono == "420coolness") {
        if (isset($username) && isset($email) && isset($password)) {
            if ($app->checkEmail($email)) {
                header('Location: register.php?res=1');
            } elseif ($app->checkUsername($username)) {
                header('Location: register.php?res=2');
            } else {
                if (!$app->userSignup($username, $email, $password, $hash)) {
                    header('Location: register.php?res=5');
                } else {
                    if ($app->sendEmail($email, $username, $hash))
                        header('Location: login.php?res=1');
                    else
                        header('Location: register.php?res=6');
                }
            }
        } else {
            header('Location: register.php?res=4');
        }
    } else {
        header('Location: register.php?res=3');
    }
}
echo "</center>";
$app->foot();
?>