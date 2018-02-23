<?php
include_once('app.php');
session_start();
$app = new App();
$app->destroy_session();    /* We log out */
?>