<?php
include_once('app.php');
global $app;
$app = new App();
$app->start_session();
$app->head('Subida de video');
$app->nav();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form-signin">
        <input type="text" name="title" id="title" class="form-control" placeholder="Título del video" autofocus required/>
        <input type="file" name="video" id="video" class="form-control" accept="video/*" required/>
        <input type="hidden" name="id" value="<?php echo $app->getUserId($app->getUserEmail()) ?>"/>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Subir video" id="submit"/>
    </form>
<?php
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

}

$app->foot();
?>
