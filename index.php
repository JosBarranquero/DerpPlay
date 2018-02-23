<?php
include_once('php/app.php');
global $app;
$app = new App();
if ($app->isLogged()) { // User is logged, redirect to main screen
    header('Location: php/main.php');
} else {    // User not logged, redirect to login screen
    header('Location: php/login.php');
}
?>