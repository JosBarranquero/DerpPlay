<?php
include_once('app.php');
global $app;
$app = new App();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['user']) || !isset($_GET['hash'])) {
        header('Location: login.php');
    }

    if ($app->userVerify($_GET['user'], $_GET['hash'])) {
        header('Location: login.php?res=3');
    } else {
        header('Location: login.php?res=4');
    }
}
?>