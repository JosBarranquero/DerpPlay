<?php
include_once('app.php');
global $app;
$app = new App();
$app->start_session();
$app->head('Ajustes de la cuenta');
$app->nav();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = 0;
    if (isset($_GET['res']))
        $res = $_GET['res'];
?>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form-signin">
        <input type="file" name="img" id="img" class="form-control" accept="image/*" required/>
        <input type="hidden" name="id" value="<?php echo $app->getUserId($app->getUserEmail()) ?>"/>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Actualizar imagen" id="submit"/>
    </form>
<?php
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

}
$app->foot();
?>